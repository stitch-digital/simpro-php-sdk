<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\CustomFields;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

final class UpdateContractCustomFieldRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $customerId,
        private readonly int|string $contractId,
        private readonly int|string $customFieldId,
        private readonly array $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/customers/{$this->customerId}/contracts/{$this->contractId}/customFields/{$this->customFieldId}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
