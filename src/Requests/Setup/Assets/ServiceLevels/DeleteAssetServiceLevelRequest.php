<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Assets\ServiceLevels;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteAssetServiceLevelRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $serviceLevelId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/assets/serviceLevels/{$this->serviceLevelId}";
    }
}
