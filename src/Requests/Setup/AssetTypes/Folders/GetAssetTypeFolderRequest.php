<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\Folders;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\AssetTypeFolder;

/**
 * Get a specific AssetTypeFolder.
 */
final class GetAssetTypeFolderRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $assetTypeId,
        private readonly int|string $folderId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/assetTypes/{$this->assetTypeId}/attachments/folders/{$this->folderId}";
    }

    public function createDtoFromResponse(Response $response): AssetTypeFolder
    {
        return AssetTypeFolder::fromResponse($response);
    }
}
