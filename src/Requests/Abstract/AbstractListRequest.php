<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Abstract;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;

/**
 * Base class for all list (paginated) requests.
 *
 * Extend this class for any endpoint that returns a paginated list of items.
 * Child classes must implement resolveEndpoint() and createDtoFromResponse().
 */
abstract class AbstractListRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;
}
