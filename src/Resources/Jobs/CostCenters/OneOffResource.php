<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\OneOffs\OneOffItem;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\OneOffs\BulkReplaceOneOffsRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\OneOffs\CreateOneOffRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\OneOffs\DeleteOneOffRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\OneOffs\GetOneOffRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\OneOffs\ListOneOffsRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\OneOffs\UpdateOneOffRequest;

/**
 * Resource for managing one-offs within a cost center.
 *
 * @property AbstractSimproConnector $connector
 */
final class OneOffResource extends BaseResource
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
     * List all one-off items for this cost center.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListOneOffsRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific one-off item.
     */
    public function get(int|string $oneOffId): OneOffItem
    {
        $request = new GetOneOffRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $oneOffId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new one-off item.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created one-off item
     */
    public function create(array $data): int
    {
        $request = new CreateOneOffRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Bulk replace all one-off items.
     *
     * @param  array<array<string, mixed>>  $oneOffs
     */
    public function bulkReplace(array $oneOffs): Response
    {
        $request = new BulkReplaceOneOffsRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $oneOffs);

        return $this->connector->send($request);
    }

    /**
     * Update an existing one-off item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $oneOffId, array $data): Response
    {
        $request = new UpdateOneOffRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $oneOffId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a one-off item.
     */
    public function delete(int|string $oneOffId): Response
    {
        $request = new DeleteOneOffRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $oneOffId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple one-off items in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/jobs/{$this->jobId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/oneOffs",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple one-off items in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/jobs/{$this->jobId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/oneOffs",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple one-off items in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/jobs/{$this->jobId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/oneOffs",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
