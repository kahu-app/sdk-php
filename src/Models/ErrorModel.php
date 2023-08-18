<?php
declare(strict_types = 1);

namespace Kahu\SDK\Models;

use Kahu\SDK\Contracts\ModelInterface;

final class ErrorModel implements ModelInterface {
  public readonly string $message;

  public function __construct(string $message) {
    $this->message = $message;
  }
}
