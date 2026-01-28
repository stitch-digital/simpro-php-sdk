<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\Notes\JobNote;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Notes\CreateJobNoteRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Notes\GetJobNoteRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Notes\ListJobNotesRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Notes\UpdateJobNoteRequest;

/**
 * Resource for managing job notes.
 *
 * @property AbstractSimproConnector $connector
 */
final class JobNoteResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
        private readonly int|string $jobId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all notes for this job.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListJobNotesRequest($this->companyId, $this->jobId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific note.
     */
    public function get(int|string $noteId): JobNote
    {
        $request = new GetJobNoteRequest($this->companyId, $this->jobId, $noteId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new note.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created note
     */
    public function create(array $data): int
    {
        $request = new CreateJobNoteRequest($this->companyId, $this->jobId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing note.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $noteId, array $data): Response
    {
        $request = new UpdateJobNoteRequest($this->companyId, $this->jobId, $noteId, $data);

        return $this->connector->send($request);
    }
}
