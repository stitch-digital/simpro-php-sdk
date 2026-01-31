<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Setup\AssetTypes;

use Simpro\PhpSdk\Simpro\Resources\Setup\AssetTypes\AssetTypeCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\AssetTypes\AssetTypeFileResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\AssetTypes\AssetTypeFolderResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\AssetTypes\AssetTypeServiceLevelResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\AssetTypes\AssetTypeTestReadingResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for navigating a specific asset type's nested resources.
 */
final class AssetTypeScope extends AbstractScope
{
    public function __construct(
        \Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector $connector,
        int $companyId,
        private readonly int|string $assetTypeId,
    ) {
        parent::__construct($connector, $companyId);
    }

    /**
     * Access file attachments for this asset type.
     */
    public function files(): AssetTypeFileResource
    {
        return new AssetTypeFileResource($this->connector, $this->companyId, $this->assetTypeId);
    }

    /**
     * Access folder attachments for this asset type.
     */
    public function folders(): AssetTypeFolderResource
    {
        return new AssetTypeFolderResource($this->connector, $this->companyId, $this->assetTypeId);
    }

    /**
     * Access custom fields for this asset type.
     */
    public function customFields(): AssetTypeCustomFieldResource
    {
        return new AssetTypeCustomFieldResource($this->connector, $this->companyId, $this->assetTypeId);
    }

    /**
     * Access service levels for this asset type.
     */
    public function serviceLevels(): AssetTypeServiceLevelResource
    {
        return new AssetTypeServiceLevelResource($this->connector, $this->companyId, $this->assetTypeId);
    }

    /**
     * Navigate to a specific service level.
     */
    public function serviceLevel(int|string $serviceLevelId): AssetTypeServiceLevelScope
    {
        return new AssetTypeServiceLevelScope($this->connector, $this->companyId, $this->assetTypeId, $serviceLevelId);
    }

    /**
     * Access test readings for this asset type.
     */
    public function testReadings(): AssetTypeTestReadingResource
    {
        return new AssetTypeTestReadingResource($this->connector, $this->companyId, $this->assetTypeId);
    }
}
