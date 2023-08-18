<?php
declare(strict_types = 1);

namespace Kahu\SDK;

final class Version {
  public const MAJOR = 0;
  public const MINOR = 1;
  public const PATCH = 0;

  public static function toString(): string {
    return sprintf('%d.%d.%d', self::MAJOR, self::MINOR, self::PATCH);
  }
}
