<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Labor\LaborRates;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a LaborRate.
 */
final class DeleteLaborRateRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $laborRateId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/labor/laborRates/{$this->laborRateId}";
    }
}
