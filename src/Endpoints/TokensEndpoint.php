<?php
declare(strict_types = 1);

namespace Kahu\SDK\Endpoints;

use Kahu\SDK\Contracts\ModelInterface;
use Kahu\SDK\Contracts\TokensEndpointInterface;
use Kahu\SDK\Models\Tokens\InspectModel;
use Kahu\SDK\Models\Tokens\TokenModel;
use Ramsey\Collection\CollectionInterface;
use Scale\Time\Hours;

class TokensEndpoint extends AbstractEndpoint implements TokensEndpointInterface {
  public function list(): CollectionInterface {
    return $this->authenticatedRequest(
      self::HTTP_GET,
      '/v0/tokens',
      InspectModel::class
    );
  }

  public function create(string $description = null, int $expiresIn = Hours::IN_SECONDS): ModelInterface {
    return $this->authenticatedRequest(
      self::HTTP_POST,
      '/v0/tokens',
      TokenModel::class,
      [
        'body' => [
          'description' => $description,
          'expiresIn' => $expiresIn
        ]
      ]
    );
  }

  public function inspect(): ModelInterface {
    return $this->authenticatedRequest(
      self::HTTP_GET,
      '/v0/tokens/current',
      InspectModel::class
    );
  }
}
