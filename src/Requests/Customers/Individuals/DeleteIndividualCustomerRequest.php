<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Customers\Individuals;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteIndividualCustomerRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $customerId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/customers/individuals/{$this->customerId}";
    }
}
