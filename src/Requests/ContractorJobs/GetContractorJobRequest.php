<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\ContractorJobs;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\ContractorJobs\ContractorJobDetail;

final class GetContractorJobRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $contractorJobId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/contractorJobs/{$this->contractorJobId}";
    }

    public function createDtoFromResponse(Response $response): ContractorJobDetail
    {
        return ContractorJobDetail::fromResponse($response);
    }
}
