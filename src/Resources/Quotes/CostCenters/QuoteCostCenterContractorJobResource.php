<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Quotes\CostCenters;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\ContractorJobs\ContractorJob;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\ContractorJobs\CreateQuoteCostCenterContractorJobRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\ContractorJobs\DeleteQuoteCostCenterContractorJobRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\ContractorJobs\GetQuoteCostCenterContractorJobRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\ContractorJobs\ListQuoteCostCenterContractorJobsRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\ContractorJobs\UpdateQuoteCostCenterContractorJobRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class QuoteCostCenterContractorJobResource extends BaseResource
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
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListQuoteCostCenterContractorJobsRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    public function get(int|string $contractorJobId): ContractorJob
    {
        $request = new GetQuoteCostCenterContractorJobRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $contractorJobId);

        return $this->connector->send($request)->dto();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = new CreateQuoteCostCenterContractorJobRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $contractorJobId, array $data): Response
    {
        $request = new UpdateQuoteCostCenterContractorJobRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $contractorJobId, $data);

        return $this->connector->send($request);
    }

    public function delete(int|string $contractorJobId): Response
    {
        $request = new DeleteQuoteCostCenterContractorJobRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $contractorJobId);

        return $this->connector->send($request);
    }
}
