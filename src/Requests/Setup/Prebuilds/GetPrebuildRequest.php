<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Prebuilds;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\Prebuilds\Prebuild;

final class GetPrebuildRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int $prebuildId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/prebuilds/{$this->prebuildId}/";
    }

    public function createDtoFromResponse(Response $response): Prebuild
    {
        /** @var array<string, mixed> $data */
        $data = $response->json();

        return Prebuild::fromArray($data);
    }
}
