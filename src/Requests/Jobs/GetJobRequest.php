<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Jobs;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Jobs\Job;

final class GetJobRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $jobId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/jobs/{$this->jobId}";
    }

    public function createDtoFromResponse(Response $response): Job
    {
        return Job::fromResponse($response);
    }
}
