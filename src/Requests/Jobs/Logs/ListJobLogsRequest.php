<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Jobs\Logs;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Jobs\Logs\JobLog;

final class ListJobLogsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/logs/jobs/";
    }

    /**
     * @return array<JobLog>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            fn (array $item): JobLog => JobLog::fromArray($item),
            $response->json(),
        );
    }
}
