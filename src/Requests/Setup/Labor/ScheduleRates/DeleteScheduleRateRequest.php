<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Labor\ScheduleRates;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a ScheduleRate.
 */
final class DeleteScheduleRateRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $scheduleRateId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/labor/scheduleRates/{$this->scheduleRateId}";
    }
}
