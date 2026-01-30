<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomerProfiles;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\CustomerProfile;

final class GetCustomerProfileRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $customerProfileId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/customerProfiles/{$this->customerProfileId}";
    }

    public function createDtoFromResponse(Response $response): CustomerProfile
    {
        return CustomerProfile::fromResponse($response);
    }
}
