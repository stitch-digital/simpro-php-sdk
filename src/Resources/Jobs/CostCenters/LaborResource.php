<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\Labor\LaborItem;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Labor\BulkReplaceLaborRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Labor\CreateLaborRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Labor\DeleteLaborRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Labor\GetLaborRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Labor\ListLaborRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Labor\UpdateLaborRequest;

/**
 * Resource for managing labor within a cost center.
 *
 * @property AbstractSimproConnector $connector
 */
final class LaborResource extends BaseResource
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
     * List all labor items for this cost center.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListLaborRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific labor item.
     */
    public function get(int|string $laborId): LaborItem
    {
        $request = new GetLaborRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $laborId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new labor item.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created labor item
     */
    public function create(array $data): int
    {
        $request = new CreateLaborRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Bulk replace all labor items.
     *
     * @param  array<array<string, mixed>>  $labor
     */
    public function bulkReplace(array $labor): Response
    {
        $request = new BulkReplaceLaborRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $labor);

        return $this->connector->send($request);
    }

    /**
     * Update an existing labor item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $laborId, array $data): Response
    {
        $request = new UpdateLaborRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $laborId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a labor item.
     */
    public function delete(int|string $laborId): Response
    {
        $request = new DeleteLaborRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $laborId);

        return $this->connector->send($request);
    }
}
