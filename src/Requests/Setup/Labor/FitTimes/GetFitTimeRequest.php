<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Labor\FitTimes;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\FitTime;

/**
 * Get a specific FitTime.
 */
final class GetFitTimeRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $fitTimeId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/labor/fitTimes/{$this->fitTimeId}";
    }

    public function createDtoFromResponse(Response $response): FitTime
    {
        return FitTime::fromResponse($response);
    }
}
