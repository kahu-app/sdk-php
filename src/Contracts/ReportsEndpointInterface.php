<?php
declare(strict_types = 1);

namespace Kahu\SDK\Contracts;

use Ramsey\Collection\CollectionInterface;

interface ReportsEndpointInterface extends EndpointInterface {
  public function list(): CollectionInterface;
  public function create(string $contents): ModelInterface;
  public function view(string $id, array $include = []): ModelInterface;
}
