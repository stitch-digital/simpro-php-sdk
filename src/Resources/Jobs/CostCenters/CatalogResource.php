<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\Catalogs\CatalogItem;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Catalogs\BulkReplaceCatalogsRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Catalogs\CreateCatalogRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Catalogs\DeleteCatalogRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Catalogs\GetCatalogRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Catalogs\ListCatalogsRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Catalogs\UpdateCatalogRequest;

/**
 * Resource for managing catalogs within a cost center.
 *
 * @property AbstractSimproConnector $connector
 */
final class CatalogResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $jobId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all catalog items for this cost center.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListCatalogsRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific catalog item.
     */
    public function get(int|string $catalogId): CatalogItem
    {
        $request = new GetCatalogRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $catalogId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new catalog item.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created catalog item
     */
    public function create(array $data): int
    {
        $request = new CreateCatalogRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Bulk replace all catalog items.
     *
     * @param  array<array<string, mixed>>  $catalogs
     */
    public function bulkReplace(array $catalogs): Response
    {
        $request = new BulkReplaceCatalogsRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $catalogs);

        return $this->connector->send($request);
    }

    /**
     * Update an existing catalog item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $catalogId, array $data): Response
    {
        $request = new UpdateCatalogRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $catalogId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a catalog item.
     */
    public function delete(int|string $catalogId): Response
    {
        $request = new DeleteCatalogRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $catalogId);

        return $this->connector->send($request);
    }
}
