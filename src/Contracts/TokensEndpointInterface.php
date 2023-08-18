<?php
declare(strict_types = 1);

namespace Kahu\SDK\Contracts;

use Ramsey\Collection\CollectionInterface;
use Scale\Time\Hours;

interface TokensEndpointInterface extends EndpointInterface {
  public function list(): CollectionInterface;
  public function create(string $description = null, int $expiresIn = Hours::IN_SECONDS): ModelInterface;
  public function inspect(): ModelInterface;
}
