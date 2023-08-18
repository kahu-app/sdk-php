<?php
declare(strict_types = 1);

namespace Kahu\SDK\Endpoints;

use Kahu\SDK\Contracts\ModelInterface;
use Kahu\SDK\Contracts\ReportsEndpointInterface;
use Kahu\SDK\Models\Reports\InspectModel;
use Ramsey\Collection\CollectionInterface;

class ReportsEndpoint extends AbstractEndpoint implements ReportsEndpointInterface {
  public function list(): CollectionInterface {
    return $this->authenticatedRequest(
      self::HTTP_GET,
      '/v0/reports',
      InspectModel::class
    );
  }

  public function create(string $contents): ModelInterface {
    return $this->authenticatedRequest(
      self::HTTP_POST,
      '/v0/reports',
      ReportModel::class,
      [
        'body' => $contents
      ]
    );
  }

  public function view(string $id, array $include = []): ModelInterface {
    return $this->authenticatedRequest(
      self::HTTP_GET,
      "/v0/reports/{$id}",
      ReportModel::class,
      [
        'queryParams' => [
          'include' => implode(',', $include)
        ]
      ]
    );
  }
}
