<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\ActivitySchedules;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\ActivitySchedules\ActivitySchedule;

final class GetActivityScheduleRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $scheduleId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/activitySchedules/{$this->scheduleId}";
    }

    public function createDtoFromResponse(Response $response): ActivitySchedule
    {
        return ActivitySchedule::fromResponse($response);
    }
}
