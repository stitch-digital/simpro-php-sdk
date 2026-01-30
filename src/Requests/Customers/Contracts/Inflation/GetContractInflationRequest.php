<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\Inflation;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Customers\Contracts\ContractInflation;

final class GetContractInflationRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $customerId,
        private readonly int|string $contractId,
        private readonly int|string $inflationId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/customers/{$this->customerId}/contracts/{$this->contractId}/inflation/{$this->inflationId}";
    }

    public function createDtoFromResponse(Response $response): ContractInflation
    {
        return ContractInflation::fromResponse($response);
    }
}
