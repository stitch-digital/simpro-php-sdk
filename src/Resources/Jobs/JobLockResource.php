<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Lock\CreateJobLockRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Lock\DeleteJobLockRequest;

/**
 * Resource for managing job locks.
 *
 * @property AbstractSimproConnector $connector
 */
final class JobLockResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
        private readonly int|string $jobId,
    ) {
        parent::__construct($connector);
    }

    /**
     * Lock the job.
     *
     * @param  array<string, mixed>  $data  Optional lock data
     */
    public function create(array $data = []): Response
    {
        $request = new CreateJobLockRequest($this->companyId, $this->jobId, $data);

        return $this->connector->send($request);
    }

    /**
     * Unlock the job.
     */
    public function delete(): Response
    {
        $request = new DeleteJobLockRequest($this->companyId, $this->jobId);

        return $this->connector->send($request);
    }
}
