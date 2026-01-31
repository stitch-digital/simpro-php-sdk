<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\TestReadings;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a AssetTypeTestReading.
 */
final class DeleteAssetTypeTestReadingRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $assetTypeId,
        private readonly int|string $testReadingId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/assetTypes/{$this->assetTypeId}/testReadings/{$this->testReadingId}";
    }
}
