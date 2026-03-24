<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Bulk;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class BulkDeleteRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<int, int|string>  $ids
     */
    public function __construct(
        private readonly string $endpoint,
        private readonly array $ids,
    ) {}

    public function resolveEndpoint(): string
    {
        return rtrim($this->endpoint, '/').'/delete/';
    }

    /**
     * @return array{IDs: array<int, int|string>}
     */
    protected function defaultBody(): array
    {
        return ['IDs' => $this->ids];
    }

    /**
     * @return array<int, string>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return $response->json();
    }
}
