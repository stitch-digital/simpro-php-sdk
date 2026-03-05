<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Contractors;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Contractors\Contractor;

final class GetContractorRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $contractorId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/contractors/{$this->contractorId}";
    }

    public function createDtoFromResponse(Response $response): Contractor
    {
        return Contractor::fromResponse($response);
    }
}
