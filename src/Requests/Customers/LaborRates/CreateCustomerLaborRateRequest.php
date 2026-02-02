<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Customers\LaborRates;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;
use Simpro\PhpSdk\Simpro\Data\Customers\LaborRates\CustomerLaborRate;

final class CreateCustomerLaborRateRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(
        private readonly int $companyId,
        private readonly int|string $customerId,
        private readonly array $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/customers/{$this->customerId}/laborRates/";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }

    public function createDtoFromResponse(Response $response): CustomerLaborRate
    {
        return CustomerLaborRate::fromArray($response->json());
    }
}
