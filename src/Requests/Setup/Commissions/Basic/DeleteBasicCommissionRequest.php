<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Commissions\Basic;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteBasicCommissionRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $commissionId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/commissions/basic/{$this->commissionId}";
    }
}
