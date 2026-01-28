<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\ServiceFees\ServiceFee;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ServiceFees\BulkReplaceServiceFeesRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ServiceFees\CreateServiceFeeRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ServiceFees\DeleteServiceFeeRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ServiceFees\GetServiceFeeRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ServiceFees\ListServiceFeesRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ServiceFees\UpdateServiceFeeRequest;

/**
 * Resource for managing service fees within a cost center.
 *
 * @property AbstractSimproConnector $connector
 */
final class ServiceFeeResource extends BaseResource
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
     * List all service fees for this cost center.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListServiceFeesRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific service fee.
     */
    public function get(int|string $serviceFeeId): ServiceFee
    {
        $request = new GetServiceFeeRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $serviceFeeId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new service fee.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created service fee
     */
    public function create(array $data): int
    {
        $request = new CreateServiceFeeRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Bulk replace all service fees.
     *
     * @param  array<array<string, mixed>>  $serviceFees
     */
    public function bulkReplace(array $serviceFees): Response
    {
        $request = new BulkReplaceServiceFeesRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $serviceFees);

        return $this->connector->send($request);
    }

    /**
     * Update an existing service fee.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $serviceFeeId, array $data): Response
    {
        $request = new UpdateServiceFeeRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $serviceFeeId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a service fee.
     */
    public function delete(int|string $serviceFeeId): Response
    {
        $request = new DeleteServiceFeeRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $serviceFeeId);

        return $this->connector->send($request);
    }
}
