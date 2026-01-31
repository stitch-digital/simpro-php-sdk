<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Customers;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Customers\Contracts\ServiceLevelAssetType;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\ServiceLevels\GetServiceLevelAssetTypeRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\ServiceLevels\UpdateServiceLevelAssetTypeRequest;

/**
 * Resource for managing service level asset types.
 *
 * @property AbstractSimproConnector $connector
 */
final class ServiceLevelAssetTypeResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $customerId,
        private readonly int|string $contractId,
        private readonly int|string $serviceLevelId,
    ) {
        parent::__construct($connector);
    }

    /**
     * Get a specific asset type.
     */
    public function get(int|string $assetTypeId): ServiceLevelAssetType
    {
        $request = new GetServiceLevelAssetTypeRequest(
            $this->companyId,
            $this->customerId,
            $this->contractId,
            $this->serviceLevelId,
            $assetTypeId
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an asset type.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $assetTypeId, array $data): Response
    {
        $request = new UpdateServiceLevelAssetTypeRequest(
            $this->companyId,
            $this->customerId,
            $this->contractId,
            $this->serviceLevelId,
            $assetTypeId,
            $data
        );

        return $this->connector->send($request);
    }
}
