<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\ContractorJobs\ContractorJobDetail;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\ContractorJobs\GetContractorJobRequest;
use Simpro\PhpSdk\Simpro\Requests\ContractorJobs\ListContractorJobsRequest;
use Simpro\PhpSdk\Simpro\Requests\ContractorJobs\ListDetailedContractorJobsRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class ContractorJobResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all contractor jobs with basic information.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListContractorJobsRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all contractor jobs with full details.
     *
     * @param  array<string, mixed>  $filters
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListDetailedContractorJobsRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get detailed information for a specific contractor job.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $contractorJobId, ?array $columns = null): ContractorJobDetail
    {
        $request = new GetContractorJobRequest($this->companyId, $contractorJobId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }
}
