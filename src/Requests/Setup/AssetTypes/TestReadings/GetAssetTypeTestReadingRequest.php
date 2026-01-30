<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\TestReadings;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\AssetTypeTestReading;

/**
 * Get a specific AssetTypeTestReading.
 */
final class GetAssetTypeTestReadingRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $assetTypeId,
        private readonly int|string $testReadingId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/assetTypes/{$this->assetTypeId}/testReadings/{$this->testReadingId}";
    }

    public function createDtoFromResponse(Response $response): AssetTypeTestReading
    {
        return AssetTypeTestReading::fromResponse($response);
    }
}
