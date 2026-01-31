<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Schedules;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Schedules\Schedule;

final class GetScheduleRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $scheduleId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/schedules/{$this->scheduleId}";
    }

    public function createDtoFromResponse(Response $response): Schedule
    {
        return Schedule::fromResponse($response);
    }
}
