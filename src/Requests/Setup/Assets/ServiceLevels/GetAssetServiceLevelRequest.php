<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Assets\ServiceLevels;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\AssetServiceLevel;

final class GetAssetServiceLevelRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $serviceLevelId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/assets/serviceLevels/{$this->serviceLevelId}";
    }

    public function createDtoFromResponse(Response $response): AssetServiceLevel
    {
        return AssetServiceLevel::fromResponse($response);
    }
}
