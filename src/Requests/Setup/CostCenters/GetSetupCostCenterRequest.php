<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CostCenters;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\SetupCostCenter;

/**
 * Retrieve details for a specific cost center.
 */
final class GetSetupCostCenterRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $costCenterId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/accounts/costCenters/{$this->costCenterId}";
    }

    public function createDtoFromResponse(Response $response): SetupCostCenter
    {
        return SetupCostCenter::fromResponse($response);
    }
}
