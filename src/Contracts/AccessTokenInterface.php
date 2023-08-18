<?php
declare(strict_types = 1);

namespace Kahu\SDK\Contracts;

interface AccessTokenInterface {
  /**
   * Returns the access token string of this instance.
   */
  public function getToken(): string;
  /**
   * Returns the refresh token, if defined.
   */
  public function getRefreshToken(): string|null;
  /**
   * Returns the expiration timestamp in seconds, if defined.
   */
  public function getExpires(): int|null;
  /**
   * Checks if this token has expired.
   *
   * @return boolean true if the token has expired, false otherwise.
   *
   * @throws RuntimeException if 'expires' is not set on the token.
   */
  public function hasExpired(): bool;
}
