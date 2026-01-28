<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\ContractorJobs\ContractorJob;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
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
        private readonly int|string $companyId,
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
}
