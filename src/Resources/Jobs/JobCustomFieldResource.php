<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\CustomFields\JobCustomFieldValue;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CustomFields\GetJobCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CustomFields\ListJobCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CustomFields\UpdateJobCustomFieldRequest;

/**
 * Resource for managing job custom fields.
 *
 * @property AbstractSimproConnector $connector
 */
final class JobCustomFieldResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
        private readonly int|string $jobId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all custom fields for this job.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListJobCustomFieldsRequest($this->companyId, $this->jobId);

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
        $request = new GetJobCustomFieldRequest($this->companyId, $this->jobId, $customFieldId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update a custom field value.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $customFieldId, array $data): Response
    {
        $request = new UpdateJobCustomFieldRequest($this->companyId, $this->jobId, $customFieldId, $data);

        return $this->connector->send($request);
    }
}
