<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Labor\FitTimes;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a FitTime.
 */
final class DeleteFitTimeRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $fitTimeId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/labor/fitTimes/{$this->fitTimeId}";
    }
}
