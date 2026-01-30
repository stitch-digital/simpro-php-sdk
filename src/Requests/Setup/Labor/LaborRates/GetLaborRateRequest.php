<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Labor\LaborRates;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\LaborRate;

/**
 * Get a specific LaborRate.
 */
final class GetLaborRateRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $laborRateId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/labor/laborRates/{$this->laborRateId}";
    }

    public function createDtoFromResponse(Response $response): LaborRate
    {
        return LaborRate::fromResponse($response);
    }
}
