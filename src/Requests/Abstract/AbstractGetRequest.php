<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Abstract;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Base class for all single-item GET requests.
 *
 * Extend this class for any endpoint that retrieves a single resource by ID.
 * Child classes must implement resolveEndpoint() and createDtoFromResponse().
 */
abstract class AbstractGetRequest extends Request
{
    protected Method $method = Method::GET;
}
