<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\Tasks\JobTask;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Tasks\GetJobTaskRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Tasks\ListJobTasksRequest;

/**
 * Resource for managing job tasks.
 *
 * @property AbstractSimproConnector $connector
 */
final class JobTaskResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
        private readonly int|string $jobId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all tasks for this job.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListJobTasksRequest($this->companyId, $this->jobId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific task.
     */
    public function get(int|string $taskId): JobTask
    {
        $request = new GetJobTaskRequest($this->companyId, $this->jobId, $taskId);

        return $this->connector->send($request)->dto();
    }
}
