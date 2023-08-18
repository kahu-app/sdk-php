<?php
declare(strict_types = 1);

namespace Kahu\SDK;

use CuyZ\Valinor\MapperBuilder;
use CuyZ\Valinor\Mapper\TreeMapper;
use Kahu\SDK\Contracts\AccessTokenInterface;
use Kahu\SDK\Contracts\ReportsEndpointInterface;
use Kahu\SDK\Contracts\SelfEndpointInterface;
use Kahu\SDK\Contracts\TokensEndpointInterface;
use Kahu\SDK\Endpoints\ReportsEndpoint;
use Kahu\SDK\Endpoints\SelfEndpoint;
use Kahu\SDK\Endpoints\TokensEndpoint;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class Client {
  private ClientInterface $httpClient;
  private RequestFactoryInterface $requestFactory;
  private StreamFactoryInterface $streamFactory;
  private AccessTokenInterface|null $accessToken = null;
  private TreeMapper $mapper;


  public function __construct(
    ClientInterface $httpClient,
    RequestFactoryInterface $requestFactory,
    StreamFactoryInterface $streamFactory
  ) {
    $this->httpClient = $httpClient;
    $this->requestFactory = $requestFactory;
    $this->streamFactory = $streamFactory;

    $this->mapper = (
      (new MapperBuilder())
        ->allowSuperfluousKeys()
    )->mapper();
  }

  public function setAccessToken(AccessTokenInterface|null $accessToken): void {
    $this->accessToken = $accessToken;
  }

  public function getAccessToken(): AccessTokenInterface|null {
    return $this->accessToken;
  }

  public function reports(): ReportsEndpointInterface {
    static $endpoint = null;
    if ($endpoint === null) {
      $endpoint = new ReportsEndpoint(
        $this->httpClient,
        $this->requestFactory,
        $this->streamFactory,
        $this->accessToken,
        $this->mapper
      );
    }

    return $endpoint;
  }

  public function self(): SelfEndpointInterface {
    static $endpoint = null;
    if ($endpoint === null) {
      $endpoint =  new SelfEndpoint(
        $this->httpClient,
        $this->requestFactory,
        $this->streamFactory,
        $this->accessToken,
        $this->mapper
      );
    }

    return $endpoint;
  }

  public function tokens(): TokensEndpointInterface {
    static $endpoint = null;
    if ($endpoint === null) {
      $endpoint =  new TokensEndpoint(
        $this->httpClient,
        $this->requestFactory,
        $this->streamFactory,
        $this->accessToken,
        $this->mapper
      );
    }

    return $endpoint;
  }
}
