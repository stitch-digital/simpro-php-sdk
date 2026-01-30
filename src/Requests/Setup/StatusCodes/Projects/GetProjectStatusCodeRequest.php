<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\Projects;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\ProjectStatusCode;

final class GetProjectStatusCodeRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $statusCodeId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/statusCodes/projects/{$this->statusCodeId}";
    }

    public function createDtoFromResponse(Response $response): ProjectStatusCode
    {
        return ProjectStatusCode::fromResponse($response);
    }
}
