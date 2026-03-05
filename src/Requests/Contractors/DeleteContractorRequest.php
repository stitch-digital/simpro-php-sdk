<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Contractors;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteContractorRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $contractorId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/contractors/{$this->contractorId}";
    }
}
