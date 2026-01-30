<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\ActivitySchedules;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteActivityScheduleRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $scheduleId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/activitySchedules/{$this->scheduleId}";
    }
}
