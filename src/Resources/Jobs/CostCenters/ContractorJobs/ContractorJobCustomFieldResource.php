<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\ContractorJobs;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\CustomFields\JobCustomFieldValue;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ContractorJobs\CustomFields\GetContractorJobCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ContractorJobs\CustomFields\ListContractorJobCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ContractorJobs\CustomFields\UpdateContractorJobCustomFieldRequest;

/**
 * Resource for managing contractor job custom fields.
 *
 * @property AbstractSimproConnector $connector
 */
final class ContractorJobCustomFieldResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
        private readonly int|string $jobId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
        private readonly int|string $contractorJobId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all custom fields for this contractor job.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListContractorJobCustomFieldsRequest(
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->contractorJobId
        );

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific custom field.
     */
    public function get(int|string $customFieldId): JobCustomFieldValue
    {
        $request = new GetContractorJobCustomFieldRequest(
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->contractorJobId,
            $customFieldId
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update a custom field value.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $customFieldId, array $data): Response
    {
        $request = new UpdateContractorJobCustomFieldRequest(
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->contractorJobId,
            $customFieldId,
            $data
        );

        return $this->connector->send($request);
    }
}
