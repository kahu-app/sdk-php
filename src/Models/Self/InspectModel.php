<?php
declare(strict_types = 1);

namespace Kahu\SDK\Models\Self;

use Kahu\SDK\Contracts\ModelInterface;

final class InspectModel implements ModelInterface {
  public readonly string $id;
  public readonly string $avatar;
  public readonly string $name;

  public function __construct(
  string $id,
  string $avatar,
  string $name
  ) {
    $this->id = $id;
    $this->avatar = $avatar;
    $this->name = $name;
  }
}
