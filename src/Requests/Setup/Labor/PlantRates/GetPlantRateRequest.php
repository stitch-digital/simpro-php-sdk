<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Labor\PlantRates;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\PlantRate;

/**
 * Get a specific PlantRate.
 */
final class GetPlantRateRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $plantRateId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/labor/plantRates/{$this->plantRateId}";
    }

    public function createDtoFromResponse(Response $response): PlantRate
    {
        return PlantRate::fromResponse($response);
    }
}
