<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Connectors;

use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\HasPagination;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Saloon\Traits\Plugins\HasTimeout;
use Simpro\PhpSdk\Simpro\Concerns\SupportsCompaniesEndpoints;
use Simpro\PhpSdk\Simpro\Concerns\SupportsInfoEndpoints;
use Simpro\PhpSdk\Simpro\Exceptions\ValidationException;
use Simpro\PhpSdk\Simpro\Paginators\SimproPaginator;
use Throwable;

/**
 * Abstract base connector for Simpro API.
 *
 * Provides shared functionality for both OAuth and API Key authentication methods.
 */
abstract class AbstractSimproConnector extends \Saloon\Http\Connector implements HasPagination
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;
    use HasTimeout;
    use SupportsCompaniesEndpoints;
    use SupportsInfoEndpoints;

    /**
     * Request timeout in seconds.
     */
    private int $requestTimeout;

    /**
     * Constructor
     */
    public function __construct(
        private string $baseUrl,
        int $requestTimeout = 10
    ) {
        $this->requestTimeout = $requestTimeout;
    }

    /**
     * Get the request timeout.
     */
    public function getRequestTimeout(): float
    {
        return (float) $this->requestTimeout;
    }

    /**
     * The Base URL of the API.
     */
    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Paginate a request.
     */
    public function paginate(Request $request): SimproPaginator
    {
        return new SimproPaginator($this, $request);
    }

    /**
     * Get request exception for error handling.
     */
    public function getRequestException(Response $response, ?Throwable $senderException): ?Throwable
    {
        if ($response->status() === 422) {
            return new ValidationException($response);
        }

        // Let Saloon handle other exceptions
        return null;
    }
}
