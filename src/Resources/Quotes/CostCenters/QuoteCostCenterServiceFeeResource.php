<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Quotes\CostCenters;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\ServiceFees\ServiceFee;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\ServiceFees\CreateQuoteCostCenterServiceFeeRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\ServiceFees\DeleteQuoteCostCenterServiceFeeRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\ServiceFees\GetQuoteCostCenterServiceFeeRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\ServiceFees\ListQuoteCostCenterServiceFeesRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\ServiceFees\UpdateQuoteCostCenterServiceFeeRequest;

/**
 * Resource for managing service fees within a quote cost center.
 *
 * @property AbstractSimproConnector $connector
 */
final class QuoteCostCenterServiceFeeResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $quoteId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all service fees for this quote cost center.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListQuoteCostCenterServiceFeesRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId);

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
        $request = new GetQuoteCostCenterServiceFeeRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $serviceFeeId);

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
        $request = new CreateQuoteCostCenterServiceFeeRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing service fee.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $serviceFeeId, array $data): Response
    {
        $request = new UpdateQuoteCostCenterServiceFeeRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $serviceFeeId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a service fee.
     */
    public function delete(int|string $serviceFeeId): Response
    {
        $request = new DeleteQuoteCostCenterServiceFeeRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $serviceFeeId);

        return $this->connector->send($request);
    }
}
