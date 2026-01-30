<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CostCenters;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a cost center.
 */
final class DeleteSetupCostCenterRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $costCenterId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/accounts/costCenters/{$this->costCenterId}";
    }
}
