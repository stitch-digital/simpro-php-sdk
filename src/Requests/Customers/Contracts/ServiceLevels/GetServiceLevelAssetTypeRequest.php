<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\ServiceLevels;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Customers\Contracts\ServiceLevelAssetType;

final class GetServiceLevelAssetTypeRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $customerId,
        private readonly int|string $contractId,
        private readonly int|string $serviceLevelId,
        private readonly int|string $assetTypeId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/customers/{$this->customerId}/contracts/{$this->contractId}/serviceLevels/{$this->serviceLevelId}/assetTypes/{$this->assetTypeId}";
    }

    public function createDtoFromResponse(Response $response): ServiceLevelAssetType
    {
        return ServiceLevelAssetType::fromResponse($response);
    }
}
