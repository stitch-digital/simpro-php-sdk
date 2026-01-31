<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Commissions\Basic;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\BasicCommission;

final class GetBasicCommissionRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $commissionId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/commissions/basic/{$this->commissionId}";
    }

    public function createDtoFromResponse(Response $response): BasicCommission
    {
        return BasicCommission::fromResponse($response);
    }
}
