<?php
declare(strict_types = 1);

namespace Kahu\SDK\Endpoints;

use CuyZ\Valinor\Mapper\Source\JsonSource;
use CuyZ\Valinor\Mapper\TreeMapper;
use InvalidArgumentException;
use Jay\Json;
use Kahu\SDK\AccessToken\BearerToken;
use Kahu\SDK\AccessToken\PersonalToken;
use Kahu\SDK\Contracts\AccessTokenInterface;
use Kahu\SDK\Contracts\ModelInterface;
use Kahu\SDK\Exceptions\AccessTokenExpiredException;
use Kahu\SDK\Exceptions\BodyParsingFailureException;
use Kahu\SDK\Exceptions\InvalidResponseFormatException;
use Kahu\SDK\Models\ErrorModel;
use Kahu\SDK\Version;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Ramsey\Collection\Collection;
use Ramsey\Collection\CollectionInterface;

abstract class AbstractEndpoint {
  /**
   * The GET method requests a representation of the specified resource.
   * Requests using GET should only retrieve data.
   */
  protected const HTTP_GET = 'GET';
  /**
   * The POST method submits an entity to the specified resource, often
   * causing a change in state or side effects on the server.
   */
  protected const HTTP_POST = 'POST';
  /**
   * The PUT method replaces all current representations of the target
   * resource with the request payload.
   */
  protected const HTTP_PUT = 'PUT';
  /**
   * The DELETE method deletes the specified resource.
   */
  protected const HTTP_DELETE = 'DELETE';
  /**
   * The PATCH method applies partial modifications to a resource.
   */
  protected const HTTP_PATCH = 'PATCH';

  protected const BASE_URL = 'http://api.localhost';
  // protected const BASE_URL = 'https://api.kahu.app';

  protected ClientInterface $httpClient;
  protected RequestFactoryInterface $requestFactory;
  protected StreamFactoryInterface $streamFactory;
  protected AccessTokenInterface|null $accessToken = null;
  protected TreeMapper $mapper;

  private function prepareRequest(string $method, string $path, array $options = []): RequestInterface {
    $request = $this->requestFactory->createRequest(
      $method,
      sprintf('%s/%s', self::BASE_URL, ltrim($path, '/'))
    );

    if ($this->accessToken !== null) {
      if ($this->accessToken->getExpires() !== null && $this->accessToken->hasExpired() === true) {
        throw new AccessTokenExpiredException();
      }

      if ($this->accessToken instanceof BearerToken) {
        $options['headers'] = array_merge(
          $options['headers'] ?? [],
          ['Authorization' => 'Bearer ' . $this->accessToken->getToken()]
        );
      }

      if ($this->accessToken instanceof PersonalToken) {
        $options['headers'] = array_merge(
          $options['headers'] ?? [],
          ['Authorization' => 'Token ' . $this->accessToken->getToken()]
        );
      }
    }

    if ($method === self::HTTP_GET && isset($options['queryParams']) === true) {
      $uri = $request->getUri();
      $request = $request->withUri(
        $uri->withQuery(http_build_query($options['queryParams']))
      );
    }

    if (
      in_array($method, [self::HTTP_POST, self::HTTP_PUT, self::HTTP_PATCH], true) === true &&
      isset($options['body']) === true
    ) {
      if (is_array($options['body']) === true) {
        $options['body'] = json_encode($options['body']);
        $options['headers'] = array_merge(
          $options['headers'] ?? [],
          ['Content-Type' => 'application/json; charset=utf-8']
        );
      }

      $request = $request->withBody(
        $this->streamFactory->createStream($options['body'])
      );
    }

    if (isset($options['headers']) === true) {
      foreach ($options['headers'] as $name => $value) {
        $request = $request->withHeader($name, $value);
      }
    }

    $request = $request
      ->withHeader('Accept', 'application/json')
      ->withHeader(
        'User-Agent',
        sprintf('kahu-sdk/%s (PHP/%s; %s)', Version::toString(), PHP_VERSION, PHP_OS_FAMILY)
      );

    return $request;
  }

  protected function mapObject(array $object, string $model): ModelInterface {
    return $this->mapper->map($model, $object);
  }

  protected function mapCollection(array $list, string $model): CollectionInterface {
    $col = new Collection($model);
    foreach ($list as $object) {
      $col->add($this->mapObject($object, $model));
    }

    return $col;
  }

  protected function authenticatedRequest(
    string $method,
    string $path,
    string $model,
    array $options = []
  ): ModelInterface|CollectionInterface {
    if (class_exists($model) === false) {
      throw new InvalidArgumentException(
        "Model class \"{$model}\" does not exist"
      );
    }

    $request = $this->prepareRequest($method, $path, $options);
    $response = $this->httpClient->sendRequest($request);

    try {
      $parsedBody = Json::fromString($response->getBody()->getContents(), true);
    } catch (InvalidArgumentException $exception) {
      throw new BodyParsingFailureException(previous: $exception);
    }

    /**
     * Invalid response formats:
     *  - missing "status" key;
     *  - missing "error" key, when "status" is "false";
     *  - not having either "data" or "list" keys, when "status" is "true".
     */
    if (
      isset($parsedBody['status']) === false ||
      ($parsedBody['status'] === false && isset($parsedBody['error']) === false) ||
      (
        $parsedBody['status'] === true &&
        isset($parsedBody['data']) === false &&
        isset($parsedBody['list']) === false
      )
    ) {
      var_dump($response, $parsedBody);exit;


      throw new InvalidResponseFormatException();
    }

    if (isset($parsedBody['error'])) {
      return $this->mapObject($parsedBody['error'], ErrorModel::class);
    }

    if (isset($parsedBody['data']) === true) {
      return $this->mapObject($parsedBody['data'], $model);
    }

    return $this->mapCollection($parsedBody['list'], $model);
  }

  public function __construct(
    ClientInterface $httpClient,
    RequestFactoryInterface $requestFactory,
    StreamFactoryInterface $streamFactory,
    AccessTokenInterface|null $accessToken = null,
    TreeMapper $mapper
  ) {
    $this->httpClient = $httpClient;
    $this->requestFactory = $requestFactory;
    $this->streamFactory = $streamFactory;
    $this->accessToken = $accessToken;
    $this->mapper = $mapper;
  }
}
