<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\LaborRates;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Customers\Contracts\ContractLaborRate;

final class GetContractLaborRateRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $customerId,
        private readonly int|string $contractId,
        private readonly int|string $laborRateId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/customers/{$this->customerId}/contracts/{$this->contractId}/laborRates/{$this->laborRateId}";
    }

    public function createDtoFromResponse(Response $response): ContractLaborRate
    {
        return ContractLaborRate::fromResponse($response);
    }
}
