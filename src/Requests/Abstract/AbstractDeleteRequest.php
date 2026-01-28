<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Abstract;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Base class for all DELETE requests.
 *
 * Extend this class for any endpoint that deletes a resource.
 * Child classes must implement resolveEndpoint().
 */
abstract class AbstractDeleteRequest extends Request
{
    protected Method $method = Method::DELETE;
}
