<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Reports;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Reports\JobCostToCompleteFinancial;

/**
 * Report: cost to complete - financial view.
 */
final class GetJobCostToCompleteFinancialRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/reports/jobs/costToComplete/financial/";
    }

    /**
     * @return array<JobCostToCompleteFinancial>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => JobCostToCompleteFinancial::fromArray($item),
            $data
        );
    }
}
