<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\Files;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\AssetTypeFileListItem;

/**
 * List all AssetTypeFiles.
 */
final class ListAssetTypeFilesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $assetTypeId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/assetTypes/{$this->assetTypeId}/attachments/files/";
    }

    /**
     * @return array<AssetTypeFileListItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => AssetTypeFileListItem::fromArray($item),
            $data
        );
    }
}
