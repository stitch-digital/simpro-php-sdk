<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Bulk;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;

final class BulkCreateRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<int, array<string, mixed>>  $data
     */
    public function __construct(
        private readonly string $endpoint,
        private readonly array $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return rtrim($this->endpoint, '/').'/multiple/';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }

    public function createDtoFromResponse(Response $response): BulkResponse
    {
        return BulkResponse::fromResponse($response);
    }
}
