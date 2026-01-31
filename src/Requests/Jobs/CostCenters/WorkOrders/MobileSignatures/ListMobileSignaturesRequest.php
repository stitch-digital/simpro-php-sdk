<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\WorkOrders\MobileSignatures;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

final class ListMobileSignaturesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $jobId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
        private readonly int|string $workOrderId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/jobs/{$this->jobId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/workOrders/{$this->workOrderId}/mobileSignatures/";
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return $response->json();
    }
}
