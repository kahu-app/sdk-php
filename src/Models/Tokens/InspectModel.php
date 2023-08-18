<?php
declare(strict_types = 1);

namespace Kahu\SDK\Models\Tokens;

use DateTimeInterface;
use Kahu\SDK\Contracts\ModelInterface;

final class InspectModel implements ModelInterface {
  public readonly string $id;
  public readonly string $description;
  public readonly DateTimeInterface $createdAt;
  public readonly DateTimeInterface $expiresAt;

  public function __construct(
  string $id,
  string $description,
  DateTimeInterface $createdAt,
  DateTimeInterface $expiresAt
  ) {
    $this->id = $id;
    $this->description = $description;
    $this->createdAt = $createdAt;
    $this->expiresAt = $expiresAt;
  }
}
