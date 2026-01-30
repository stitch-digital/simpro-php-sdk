<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Reports;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Reports\JobCostToCompleteOperations;

/**
 * Report: cost to complete - operations view.
 */
final class GetJobCostToCompleteOperationsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/reports/jobs/costToComplete/operations/";
    }

    /**
     * @return array<JobCostToCompleteOperations>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => JobCostToCompleteOperations::fromArray($item),
            $data
        );
    }
}
