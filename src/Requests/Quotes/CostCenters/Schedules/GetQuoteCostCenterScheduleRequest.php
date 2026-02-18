<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\Schedules;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\Schedules\CostCenterSchedule;

final class GetQuoteCostCenterScheduleRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $quoteId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
        private readonly int|string $scheduleId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/quotes/{$this->quoteId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/schedules/{$this->scheduleId}";
    }

    public function createDtoFromResponse(Response $response): CostCenterSchedule
    {
        return CostCenterSchedule::fromResponse($response);
    }
}
