<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\ContractorJobs\ContractorJob;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ContractorJobs\CreateContractorJobRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ContractorJobs\DeleteContractorJobRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ContractorJobs\GetContractorJobRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ContractorJobs\ListContractorJobsRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ContractorJobs\UpdateContractorJobRequest;

/**
 * Resource for managing contractor jobs within a cost center.
 *
 * @property AbstractSimproConnector $connector
 */
final class ContractorJobResource extends BaseResource
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
     * List all contractor jobs for this cost center.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListContractorJobsRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific contractor job.
     */
    public function get(int|string $contractorJobId): ContractorJob
    {
        $request = new GetContractorJobRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $contractorJobId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new contractor job.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created contractor job
     */
    public function create(array $data): int
    {
        $request = new CreateContractorJobRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing contractor job.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $contractorJobId, array $data): Response
    {
        $request = new UpdateContractorJobRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $contractorJobId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a contractor job.
     */
    public function delete(int|string $contractorJobId): Response
    {
        $request = new DeleteContractorJobRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $contractorJobId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple contractor jobs in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/jobs/{$this->jobId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/contractorJobs",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple contractor jobs in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/jobs/{$this->jobId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/contractorJobs",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple contractor jobs in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/jobs/{$this->jobId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/contractorJobs",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
