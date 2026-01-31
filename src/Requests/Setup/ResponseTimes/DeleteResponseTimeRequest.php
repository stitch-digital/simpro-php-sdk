<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\ResponseTimes;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteResponseTimeRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $responseTimeId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/responseTimes/{$this->responseTimeId}";
    }
}
