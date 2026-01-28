<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Abstract;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Base class for all PATCH (update) requests.
 *
 * Extend this class for any endpoint that updates an existing resource.
 * Child classes must implement resolveEndpoint() and pass data to constructor.
 */
abstract class AbstractUpdateRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

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
}
