<?php
declare(strict_types = 1);

namespace Kahu\SDK\Endpoints;

use Kahu\SDK\Contracts\ModelInterface;
use Kahu\SDK\Contracts\SelfEndpointInterface;
use Kahu\SDK\Models\Self\InspectModel;

class SelfEndpoint extends AbstractEndpoint implements SelfEndpointInterface {
  public function inspect(): ModelInterface {
    return $this->authenticatedRequest(
      self::HTTP_GET,
      '/v0/self',
      InspectModel::class
    );
  }
}
