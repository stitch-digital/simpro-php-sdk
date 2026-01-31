<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\Job;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CreateJobRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\DeleteJobRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\GetJobRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\ListJobsDetailedRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\ListJobsRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\UpdateJobRequest;
use Simpro\PhpSdk\Simpro\Scopes\Jobs\JobScope;

/**
 * @property AbstractSimproConnector $connector
 */
final class JobResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all jobs.
     *
     * Returns a QueryBuilder that supports fluent search, ordering, and filtering.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     *
     * @example
     * // Simple list
     * $jobs = $connector->jobs(companyId: 0)->list()->all();
     *
     * // With fluent search
     * $result = $connector->jobs(companyId: 0)->list()
     *     ->search(Search::make()->column('Name')->find('Project'))
     *     ->orderByDesc('DateIssued')
     *     ->first();
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListJobsRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all jobs with detailed information.
     *
     * Returns full Job DTOs with all available columns.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     *
     * @example
     * // Get all jobs with full details
     * $jobs = $connector->jobs(companyId: 0)->listDetailed()->all();
     *
     * // With fluent search
     * $result = $connector->jobs(companyId: 0)->listDetailed()
     *     ->search(Search::make()->column('Name')->find('Project'))
     *     ->orderByDesc('DateIssued')
     *     ->collect();
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListJobsDetailedRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a scope for a specific job to access nested resources.
     *
     * @example
     * // Access job sections
     * $connector->jobs(companyId: 0)->job(jobId: 123)->sections()->list();
     *
     * // Access job notes
     * $connector->jobs(companyId: 0)->job(jobId: 123)->notes()->list();
     *
     * // Navigate to a specific section
     * $connector->jobs(companyId: 0)->job(jobId: 123)->section(sectionId: 1)->costCenters()->list();
     */
    public function job(int|string $jobId): JobScope
    {
        return new JobScope($this->connector, $this->companyId, $jobId);
    }

    /**
     * Get detailed information for a specific job.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $jobId, ?array $columns = null): Job
    {
        $request = new GetJobRequest($this->companyId, $jobId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new job.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created job
     */
    public function create(array $data): int
    {
        $request = new CreateJobRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing job.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $jobId, array $data): Response
    {
        $request = new UpdateJobRequest($this->companyId, $jobId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a job.
     */
    public function delete(int|string $jobId): Response
    {
        $request = new DeleteJobRequest($this->companyId, $jobId);

        return $this->connector->send($request);
    }
}
