<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Customers;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Customers\Customer;

final class GetCompanyCustomerRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $customerId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/customers/companies/{$this->customerId}";
    }

    public function createDtoFromResponse(Response $response): Customer
    {
        return Customer::fromResponse($response);
    }
}
