<?php
declare(strict_types = 1);

namespace Kahu\SDK;

use InvalidArgumentException;
use Kahu\SDK\AccessToken\BearerToken;
use Kahu\SDK\AccessToken\PersonalToken;
use Kahu\SDK\Contracts\AccessTokenInterface;
use PsrDiscovery\Discover;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class Factory {
  public static function createAccessToken(
    string $type,
    string $accessToken,
    string|null $refreshToken = null,
    int|null $expires = null
  ): AccessTokenInterface {
    $type = strtolower($type);
    if (in_array($type, ['bearer', 'personal'], true) === false) {
      throw new InvalidArgumentException("Invalid access token type \"{$type}\"");
    }

    return match ($type) {
      'bearer' => new BearerToken($accessToken, $refreshToken, $expires),
      'personal' => new PersonalToken($accessToken, $refreshToken, $expires)
    };
  }

  public static function createClient(
    AccessTokenInterface|null $accessToken = null,
    ClientInterface $httpClient = null,
    RequestFactoryInterface $requestFactory = null,
    StreamFactoryInterface $streamFactory = null
  ): Client {
    $client = new Client(
      $httpClient ?? Discover::httpClient(),
      $requestFactory ?? Discover::httpRequestFactory(),
      $streamFactory ?? Discover::httpStreamFactory()
    );

    $client->setAccessToken($accessToken);

    return $client;
  }
}
