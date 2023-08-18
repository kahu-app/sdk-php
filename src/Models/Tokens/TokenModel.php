<?php
declare(strict_types = 1);

namespace Kahu\SDK\Models\Tokens;

use DateTimeInterface;
use Kahu\SDK\Contracts\ModelInterface;

final class TokenModel implements ModelInterface {
  public readonly string $id;
  public readonly string $description;
  public readonly string $token;
  public readonly DateTimeInterface $expiresAt;

  public function __construct(
  string $id,
  string $description,
  string $token,
  DateTimeInterface $expiresAt
  ) {
    $this->id = $id;
    $this->description = $description;
    $this->token = $token;
    $this->expiresAt = $expiresAt;
  }
}
