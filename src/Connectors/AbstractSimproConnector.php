<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Connectors;

use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\HasPagination;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Saloon\Traits\Plugins\HasTimeout;
use Simpro\PhpSdk\Simpro\Cache\CacheConfig;
use Simpro\PhpSdk\Simpro\Concerns\HasSimproCaching;
use Simpro\PhpSdk\Simpro\Concerns\HasSimproRateLimits;
use Simpro\PhpSdk\Simpro\Concerns\SupportsCompaniesEndpoints;
use Simpro\PhpSdk\Simpro\Concerns\SupportsCurrentUserEndpoints;
use Simpro\PhpSdk\Simpro\Concerns\SupportsCustomersEndpoints;
use Simpro\PhpSdk\Simpro\Concerns\SupportsEmployeesEndpoints;
use Simpro\PhpSdk\Simpro\Concerns\SupportsInfoEndpoints;
use Simpro\PhpSdk\Simpro\Concerns\SupportsInvoicesEndpoints;
use Simpro\PhpSdk\Simpro\Concerns\SupportsJobsEndpoints;
use Simpro\PhpSdk\Simpro\Concerns\SupportsQuotesEndpoints;
use Simpro\PhpSdk\Simpro\Concerns\SupportsSchedulesEndpoints;
use Simpro\PhpSdk\Simpro\Exceptions\ValidationException;
use Simpro\PhpSdk\Simpro\Paginators\SimproPaginator;
use Simpro\PhpSdk\Simpro\RateLimit\RateLimitConfig;
use Throwable;

/**
 * Abstract base connector for Simpro API.
 *
 * Provides shared functionality for both OAuth and API Key authentication methods.
 */
abstract class AbstractSimproConnector extends \Saloon\Http\Connector implements Cacheable, HasPagination
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;
    use HasSimproCaching;
    use HasSimproRateLimits;
    use HasTimeout;
    use SupportsCompaniesEndpoints;
    use SupportsCurrentUserEndpoints;
    use SupportsCustomersEndpoints;
    use SupportsEmployeesEndpoints;
    use SupportsInfoEndpoints;
    use SupportsInvoicesEndpoints;
    use SupportsJobsEndpoints;
    use SupportsQuotesEndpoints;
    use SupportsSchedulesEndpoints;

    /**
     * Request timeout in seconds.
     */
    private int $requestTimeout;

    /**
     * Constructor
     */
    public function __construct(
        private string $baseUrl,
        int $requestTimeout = 10,
        ?RateLimitConfig $rateLimitConfig = null,
        ?CacheConfig $cacheConfig = null,
    ) {
        $this->requestTimeout = $requestTimeout;
        $this->setRateLimitConfig($rateLimitConfig);
        $this->setCacheConfig($cacheConfig);
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
