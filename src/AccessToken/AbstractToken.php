<?php
declare(strict_types = 1);

namespace Kahu\SDK\AccessToken;

use Kahu\SDK\Contracts\AccessTokenInterface;
use RuntimeException;

abstract class AbstractToken implements AccessTokenInterface {
  protected string $accessToken;
  protected string|null $refreshToken;
  protected int|null $expires;

  public function __construct(
    string $accessToken,
    string|null $refreshToken = null,
    int|null $expires = null
  ) {
    $this->accessToken = $accessToken;
    $this->refreshToken = $refreshToken;
    $this->expires = $expires;
  }

  public function getToken(): string {
    return $this->accessToken;
  }

  public function getRefreshToken(): string|null {
    return $this->refreshToken;
  }

  public function getExpires(): int|null {
    return $this->expires;
  }

  public function hasExpired(): bool {
    $expires = $this->getExpires();

    if ($expires === null) {
      throw new RuntimeException('"expires" is not set on the token');
    }

    return $expires < time();
  }
}
