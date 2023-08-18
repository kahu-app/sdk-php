<?php
declare(strict_types = 1);

namespace Kahu\SDK\Models\Reports;

use DateTimeInterface;
use Kahu\SDK\Contracts\ModelInterface;

final class InspectModel implements ModelInterface {
  public readonly string $id;
  public readonly string $status;
  public readonly int|null $packageCount;
  public readonly float $progress;
  public readonly DateTimeInterface $createdAt;
  public readonly DateTimeInterface|null $updatedAt;
  public readonly DateTimeInterface|null $finishedAt;

  public function __construct(
    string $id,
    string $status,
    int|null $packageCount,
    float $progress,
    DateTimeInterface $createdAt,
    DateTimeInterface|null $updatedAt,
    DateTimeInterface|null $finishedAt
  ) {
    $this->id = $id;
    $this->status = $status;
    $this->packageCount = $packageCount;
    $this->progress = $progress;
    $this->createdAt = $createdAt;
    $this->updatedAt = $updatedAt;
    $this->finishedAt = $finishedAt;
  }
}
