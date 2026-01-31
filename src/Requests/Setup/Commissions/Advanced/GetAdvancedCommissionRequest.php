<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Commissions\Advanced;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\AdvancedCommission;

final class GetAdvancedCommissionRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $commissionId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/commissions/advanced/{$this->commissionId}";
    }

    public function createDtoFromResponse(Response $response): AdvancedCommission
    {
        return AdvancedCommission::fromResponse($response);
    }
}
