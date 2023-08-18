<?php
declare(strict_types = 1);

namespace Kahu\SDK\Contracts;

interface SelfEndpointInterface extends EndpointInterface {
  public function inspect(): ModelInterface;
}
