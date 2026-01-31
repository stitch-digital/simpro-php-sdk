<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\Sections\Section;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Sections\CreateSectionRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Sections\DeleteSectionRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Sections\GetSectionRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Sections\ListSectionsRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Sections\UpdateSectionRequest;

/**
 * Resource for managing job sections.
 *
 * @property AbstractSimproConnector $connector
 */
final class JobSectionResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $jobId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all sections for this job.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListSectionsRequest($this->companyId, $this->jobId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific section.
     */
    public function get(int|string $sectionId): Section
    {
        $request = new GetSectionRequest($this->companyId, $this->jobId, $sectionId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new section.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created section
     */
    public function create(array $data): int
    {
        $request = new CreateSectionRequest($this->companyId, $this->jobId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing section.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $sectionId, array $data): Response
    {
        $request = new UpdateSectionRequest($this->companyId, $this->jobId, $sectionId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a section.
     */
    public function delete(int|string $sectionId): Response
    {
        $request = new DeleteSectionRequest($this->companyId, $this->jobId, $sectionId);

        return $this->connector->send($request);
    }
}
