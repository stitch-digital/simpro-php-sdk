<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Abstract;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Base class for all POST (create) requests.
 *
 * Extend this class for any endpoint that creates a new resource.
 * Child classes must implement resolveEndpoint() and pass data to constructor.
 */
abstract class AbstractCreateRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(
        protected readonly array $data,
    ) {}

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }

    /**
     * Extract the created resource ID from the response.
     */
    public function createDtoFromResponse(Response $response): int
    {
        $data = $response->json();

        return (int) $data['ID'];
    }
}
