<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\LaborRates;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteContractLaborRateRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $customerId,
        private readonly int|string $contractId,
        private readonly int|string $laborRateId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/customers/{$this->customerId}/contracts/{$this->contractId}/laborRates/{$this->laborRateId}";
    }
}
