<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\Prebuilds\PrebuildItem;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Prebuilds\BulkReplacePrebuildsRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Prebuilds\CreatePrebuildRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Prebuilds\DeletePrebuildRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Prebuilds\GetPrebuildRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Prebuilds\ListPrebuildsRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Prebuilds\UpdatePrebuildRequest;

/**
 * Resource for managing prebuilds within a cost center.
 *
 * @property AbstractSimproConnector $connector
 */
final class PrebuildResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
        private readonly int|string $jobId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all prebuild items for this cost center.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListPrebuildsRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific prebuild item.
     */
    public function get(int|string $prebuildId): PrebuildItem
    {
        $request = new GetPrebuildRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $prebuildId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new prebuild item.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created prebuild item
     */
    public function create(array $data): int
    {
        $request = new CreatePrebuildRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Bulk replace all prebuild items.
     *
     * @param  array<array<string, mixed>>  $prebuilds
     */
    public function bulkReplace(array $prebuilds): Response
    {
        $request = new BulkReplacePrebuildsRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $prebuilds);

        return $this->connector->send($request);
    }

    /**
     * Update an existing prebuild item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $prebuildId, array $data): Response
    {
        $request = new UpdatePrebuildRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $prebuildId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a prebuild item.
     */
    public function delete(int|string $prebuildId): Response
    {
        $request = new DeletePrebuildRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $prebuildId);

        return $this->connector->send($request);
    }
}
