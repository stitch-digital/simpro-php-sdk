<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Customers\LaborRates;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteCustomerLaborRateRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $customerId,
        private readonly int|string $laborRateId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/customers/{$this->customerId}/laborRates/{$this->laborRateId}";
    }
}
