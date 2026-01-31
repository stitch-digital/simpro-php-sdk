<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Labor\ScheduleRates;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\ScheduleRate;

/**
 * Get a specific ScheduleRate.
 */
final class GetScheduleRateRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $scheduleRateId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/labor/scheduleRates/{$this->scheduleRateId}";
    }

    public function createDtoFromResponse(Response $response): ScheduleRate
    {
        return ScheduleRate::fromResponse($response);
    }
}
