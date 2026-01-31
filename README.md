# Simpro PHP SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/stitch-digital/simpro-php-sdk.svg?style=flat-square)](https://packagist.org/packages/stitch-digital/simpro-php-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/stitch-digital/simpro-php-sdk.svg?style=flat-square)](https://packagist.org/packages/stitch-digital/simpro-php-sdk)

This package is an unofficial PHP SDK for the Simpro API, built with [Saloon](https://docs.saloon.dev/).

⚠️ **Active Development Notice**

This package is under active development. The API, features, and internal structure may change at any time.

A stable, production-ready release has **not** yet been tagged. Until a `v1.0.0` (or similar) release is published, this SDK should be considered **experimental** and used with caution in production environments.

<div align="center">
  <a href="https://www.simprogroup.com">
    <img src="simpro-logo.jpg" alt="Simpro Logo">
  </a>
</div>

## About Simpro

[Simpro](https://www.simprogroup.com/) is a cloud-based field service management platform designed for trade and service contractors, supporting industries such as fire protection, electrical, plumbing, HVAC, and security. It helps businesses manage jobs, quoting, scheduling, asset registers, compliance, and invoicing in one central system, replacing spreadsheets and disconnected tools with a mobile-friendly platform where technicians can capture job data, photos, and service results on site while office teams manage workflows, customer communication, and reporting, with integrations into accounting and other business software.

- **Website:** [https://www.simprogroup.com/](https://www.simprogroup.com/)
- **GitHub:** [https://github.com/simPRO-Software](https://github.com/simPRO-Software)

## Table of Contents

- [Installation](#installation)
- [Quick Start](#quick-start)
- [Authentication Methods](#authentication-methods)
  - [OAuth (Authorization Code Grant)](#oauth-authorization-code-grant)
  - [API Key](#api-key)
- [Usage](#usage)
  - [Setting a Timeout](#setting-a-timeout)
  - [Rate Limiting](#rate-limiting)
  - [Caching](#caching)
  - [Laravel Integration](#laravel-integration)
  - [Handling Errors](#handling-errors)
- [Resources](#resources)
- [Pagination and Querying](#pagination-and-querying)
  - [Fluent Search API](#fluent-search-api)
  - [Search Methods](#search-methods)
  - [Using Laravel Collections](#using-laravel-collections)
- [Security](#security)
- [Credits](#credits)
- [License](#license)

## Installation

```bash
composer require stitch-digital/simpro-php-sdk
```

## Quick Start

Choose the authentication method that fits your use case. See the [Authentication Methods](#authentication-methods) section below for details.

```php
use Simpro\PhpSdk\Simpro\Connectors\SimproApiKeyConnector;

// For server-to-server integrations
$connector = new SimproApiKeyConnector(
    baseUrl: 'https://example.simprosuite.com/api/v1.0',
    apiKey: 'your-api-key'
);

// Get information about your Simpro instance
$version = $connector->info()->version(); // Returns: '99.0.0.0.1.1'

// List all companies
$companies = $connector->companies()->list();
foreach ($companies->items() as $company) {
    echo "{$company->id}: {$company->name}\n";
}
```

Behind the scenes, the SDK uses [Saloon](https://docs.saloon.dev/) v3 to make HTTP requests.

**Note:** This SDK is in early development. API resources and endpoints are being actively developed.

## Authentication Methods

Simpro's API supports two authentication methods. Choose the one that best fits your use case:

### OAuth (Authorization Code Grant)

**Best for:** Web applications where users can be redirected to Simpro's authorization page.

This flow requires users to approve access to your application. After approval, they're redirected back with a code you exchange for an access token.

```php
use Simpro\PhpSdk\Simpro\Connectors\SimproOAuthConnector;

// Create the connector
$connector = new SimproOAuthConnector(
    baseUrl: 'https://example.simprosuite.com',
    clientId: 'your-client-id',
    clientSecret: 'your-client-secret',
    redirectUri: 'https://yourapp.com/oauth/callback'
);

// Step 1: Redirect user to authorization URL
$authUrl = $connector->getAuthorizationUrl();
// Redirect user to $authUrl

// Step 2: In your callback handler, exchange the code for a token
$code = $_GET['code']; // From the callback URL
$authenticator = $connector->getAccessToken($code);

// Step 3: Authenticate your connector and store the authenticator
$connector->authenticate($authenticator);

// Store $authenticator->serialize() securely (encrypted in database)
// so you can reuse it later

// Step 4: Make API requests
$version = $connector->info()->version();

// Step 5: Check for expired tokens and refresh when needed
if ($authenticator->hasExpired()) {
    $newAuthenticator = $connector->refreshAccessToken($authenticator);
    // Update stored authenticator
}
```

**Token Management:**

The `AccessTokenAuthenticator` returned by `getAccessToken()` contains:
- Access token
- Refresh token  
- Expiry timestamp

You should securely store this (encrypted) in your database and check for expiration before each request.

```php
// Serializing for storage
$serialized = $authenticator->serialize();

// Unserializing when retrieving
use Saloon\Http\Auth\AccessTokenAuthenticator;
$authenticator = AccessTokenAuthenticator::unserialize($serialized);
```

### API Key

**Best for:** Server-to-server integrations, background jobs, and command-line tools.

This is the simplest authentication method - just provide your API key and start making requests.

```php
use Simpro\PhpSdk\Simpro\Connectors\SimproApiKeyConnector;

// Create the connector
$connector = new SimproApiKeyConnector(
    baseUrl: 'https://example.simprosuite.com/api/v1.0',
    apiKey: 'your-api-key'
);

// Make API requests - authentication is handled automatically
$version = $connector->info()->version();
```

**That's it!** No token management, no refresh logic - just simple, straightforward authentication.

## Usage

### Authentication

The SDK supports two authentication methods. See the [Authentication Methods](#authentication-methods) section above for detailed information:

- **OAuth Authorization Code Grant**: For web applications with user redirects
- **API Key**: For server-to-server integrations

### Setting a timeout

By default, the SDK waits 10 seconds for a response. Override via the constructor:

```php
// OAuth
$connector = new SimproOAuthConnector(
    baseUrl: 'https://example.simprosuite.com',
    clientId: 'your-client-id',
    clientSecret: 'your-client-secret',
    redirectUri: 'https://yourapp.com/oauth/callback',
    scopes: [],
    requestTimeout: 30
);

// API Key
$connector = new SimproApiKeyConnector(
    baseUrl: 'https://example.simprosuite.com/api/v1.0',
    apiKey: 'your-api-key',
    requestTimeout: 30
);
```

### Rate Limiting

The SDK automatically handles Simpro's API rate limit of **10 requests per second** per base URL. By default, when the limit is reached, the SDK waits and retries automatically.

See [Simpro's API documentation](https://developer.simprogroup.com/apidoc/?page=ed8457e003ba0f6197756eca5a61fde9) for more details on rate limits.

#### Default Behavior

Rate limiting is enabled by default with sensible settings:

```php
// No configuration needed - rate limiting works out of the box
$connector = new SimproApiKeyConnector(
    baseUrl: 'https://example.simprosuite.com/api/v1.0',
    apiKey: 'your-api-key'
);
```

With default settings:
- **10 requests per second** limit
- **Sleep and retry** when limit is reached (no exceptions)
- **Memory store** for tracking request counts (per-process)

#### Custom Store

For applications running multiple processes or workers, use a persistent store to share rate limit state:

```php
use Saloon\RateLimitPlugin\Stores\FileStore;
use Simpro\PhpSdk\Simpro\RateLimit\RateLimitConfig;

// File-based store for persistence across processes
$connector = new SimproApiKeyConnector(
    baseUrl: 'https://example.simprosuite.com/api/v1.0',
    apiKey: 'your-api-key',
    rateLimitConfig: new RateLimitConfig(
        store: new FileStore('/var/cache/simpro'),
    ),
);
```

For Laravel applications, you can use a cache-based store:

```php
use Saloon\RateLimitPlugin\Stores\LaravelCacheStore;
use Simpro\PhpSdk\Simpro\RateLimit\RateLimitConfig;

// Use Laravel's Redis cache for distributed rate limiting
$connector = new SimproApiKeyConnector(
    baseUrl: 'https://example.simprosuite.com/api/v1.0',
    apiKey: 'your-api-key',
    rateLimitConfig: new RateLimitConfig(
        store: new LaravelCacheStore(Cache::store('redis')),
    ),
);
```

See [Saloon's Rate Limit Plugin documentation](https://docs.saloon.dev/installable-plugins/handling-rate-limits) for all available stores.

#### Throwing Exceptions

For queue jobs or situations where you want to handle rate limits yourself, configure the SDK to throw exceptions instead of sleeping:

```php
use Saloon\RateLimitPlugin\Exceptions\RateLimitReachedException;
use Simpro\PhpSdk\Simpro\RateLimit\RateLimitConfig;

$connector = new SimproApiKeyConnector(
    baseUrl: 'https://example.simprosuite.com/api/v1.0',
    apiKey: 'your-api-key',
    rateLimitConfig: RateLimitConfig::throwing(),
);

try {
    $response = $connector->send($request);
} catch (RateLimitReachedException $e) {
    // Release job back to queue with delay
    $secondsToWait = $e->getLimit()->getRemainingSeconds();
    // ... release job with $secondsToWait delay
}
```

#### Disabling Rate Limiting

If you need to disable rate limiting entirely:

```php
$connector = new SimproApiKeyConnector(
    baseUrl: 'https://example.simprosuite.com/api/v1.0',
    apiKey: 'your-api-key'
);

$connector->useRateLimitPlugin(false);
```

### Caching

The SDK supports response caching using Saloon's cache plugin. Caching is **opt-in** and disabled by default - you must provide a `CacheConfig` to enable it.

#### Enabling Caching

You can enable caching using any PSR-16 compatible cache, Laravel's cache system, or Flysystem:

```php
use Simpro\PhpSdk\Simpro\Cache\CacheConfig;

// Using PSR-16 cache (e.g., Symfony Cache)
$connector = new SimproApiKeyConnector(
    baseUrl: 'https://example.simprosuite.com/api/v1.0',
    apiKey: 'your-api-key',
    cacheConfig: CacheConfig::psr16($symfonyCache),
);

// Using Laravel cache
$connector = new SimproApiKeyConnector(
    baseUrl: 'https://example.simprosuite.com/api/v1.0',
    apiKey: 'your-api-key',
    cacheConfig: CacheConfig::laravel(Cache::store('redis')),
);

// Using Flysystem
$connector = new SimproApiKeyConnector(
    baseUrl: 'https://example.simprosuite.com/api/v1.0',
    apiKey: 'your-api-key',
    cacheConfig: CacheConfig::flysystem($filesystem),
);
```

#### Cache Options

Configure cache expiry and key prefix:

```php
use Simpro\PhpSdk\Simpro\Cache\CacheConfig;

$connector = new SimproApiKeyConnector(
    baseUrl: 'https://example.simprosuite.com/api/v1.0',
    apiKey: 'your-api-key',
    cacheConfig: CacheConfig::laravel(
        cache: Cache::store('redis'),
        expiryInSeconds: 600,  // 10 minutes (default: 300)
        keyPrefix: 'my-app',   // Optional additional prefix
    ),
);
```

#### Cache Behavior

| Aspect | Behavior |
|--------|----------|
| Default state | Disabled (opt-in) |
| Cached methods | GET and OPTIONS only |
| Cached responses | Successful responses only |
| Cache key prefix | `simpro:{hostname}[:userPrefix]:{hash}` |
| Default expiry | 300 seconds (5 minutes) |

Cache keys are automatically prefixed with the Simpro instance hostname to ensure multi-tenant isolation.

#### Enabling Caching After Construction

You can also enable or disable caching after creating the connector:

```php
use Simpro\PhpSdk\Simpro\Cache\CacheConfig;

$connector = new SimproApiKeyConnector(
    baseUrl: 'https://example.simprosuite.com/api/v1.0',
    apiKey: 'your-api-key',
);

// Enable caching later
$connector->setCacheConfig(CacheConfig::laravel(Cache::store('redis')));

// Check if caching is enabled
if ($connector->hasCaching()) {
    // ...
}

// Disable caching
$connector->setCacheConfig(null);
```

#### Checking Cache Status

After sending a request, you can check if the response came from cache:

```php
$response = $connector->send($request);

if ($response->isCached()) {
    // Response was served from cache
}
```

#### Disabling Cache for Specific Requests

You can disable caching for individual requests:

```php
$request->disableCaching();
$response = $connector->send($request);
```

#### Invalidating Cache

To invalidate the cache for a specific request:

```php
$request->invalidateCache();
$response = $connector->send($request); // Fresh response, also updates cache
```

See [Saloon's Cache Plugin documentation](https://docs.saloon.dev/installable-plugins/caching-responses) for more details.

### Laravel Integration

This section provides guidance for integrating the SDK into Laravel applications. The approach differs depending on whether you're building a single-tenant application (API Key) or a multi-tenant application (OAuth).

#### API Key Connector (Single-Tenant Applications)

For applications that connect to a single Simpro instance using an API key.

**Configuration** (`config/services.php`):

```php
'simpro' => [
    'base_url' => env('SIMPRO_BASE_URL'),
    'api_key' => env('SIMPRO_API_KEY'),
],
```

**Service Provider Binding** (`app/Providers/AppServiceProvider.php`):

```php
use Illuminate\Support\Facades\Cache;
use Saloon\RateLimitPlugin\Stores\LaravelCacheStore;
use Simpro\PhpSdk\Simpro\Cache\CacheConfig;
use Simpro\PhpSdk\Simpro\Connectors\SimproApiKeyConnector;
use Simpro\PhpSdk\Simpro\RateLimit\RateLimitConfig;

public function register(): void
{
    $this->app->singleton(SimproApiKeyConnector::class, function ($app) {
        return new SimproApiKeyConnector(
            baseUrl: config('services.simpro.base_url'),
            apiKey: config('services.simpro.api_key'),
            requestTimeout: 30,
            rateLimitConfig: new RateLimitConfig(
                store: new LaravelCacheStore($app['cache']->store()),
            ),
            cacheConfig: CacheConfig::laravel(
                cache: Cache::store('redis'),
                expiryInSeconds: 300,
            ),
        );
    });
}
```

**Usage via Dependency Injection**:

```php
use Simpro\PhpSdk\Simpro\Connectors\SimproApiKeyConnector;

class JobController extends Controller
{
    public function __construct(
        private SimproApiKeyConnector $simpro
    ) {}

    public function index()
    {
        $jobs = $this->simpro->jobs()->list()->all();

        return view('jobs.index', compact('jobs'));
    }
}
```

**Optional Facade**:

Create a facade for convenient static access:

```php
// app/Facades/Simpro.php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use Simpro\PhpSdk\Simpro\Connectors\SimproApiKeyConnector;

/**
 * @method static \Simpro\PhpSdk\Simpro\Resources\InfoResource info()
 * @method static \Simpro\PhpSdk\Simpro\Resources\CompanyResource companies()
 * @method static \Simpro\PhpSdk\Simpro\Resources\JobResource jobs()
 * @method static \Simpro\PhpSdk\Simpro\Resources\CustomerResource customers()
 *
 * @see SimproApiKeyConnector
 */
class Simpro extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SimproApiKeyConnector::class;
    }
}
```

Usage:

```php
use App\Facades\Simpro;

$version = Simpro::info()->version();
$companies = Simpro::companies()->list()->all();
```

#### OAuth Connector (Multi-Tenant Applications)

For applications where multiple users connect their own Simpro accounts. This approach requires the Saloon Laravel plugin for the Eloquent cast:

```bash
composer require saloonphp/laravel-plugin "^3.0"
```

**Configuration** (`config/services.php`):

```php
'simpro' => [
    'client_id' => env('SIMPRO_CLIENT_ID'),
    'client_secret' => env('SIMPRO_CLIENT_SECRET'),
    'redirect_uri' => env('SIMPRO_REDIRECT_URI'),
],
```

**Model** (`app/Models/SimproConnection.php`):

Store the OAuth authenticator and tenant-specific Simpro URL:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Saloon\Laravel\Casts\EncryptedOAuthAuthenticatorCast;

class SimproConnection extends Model
{
    protected $fillable = [
        'user_id',
        'build_url',
        'simpro_auth',
    ];

    protected function casts(): array
    {
        return [
            'simpro_auth' => EncryptedOAuthAuthenticatorCast::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```

Migration:

```php
Schema::create('simpro_connections', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('build_url'); // e.g., https://tenant.simprosuite.com
    $table->text('simpro_auth'); // Encrypted OAuth authenticator
    $table->timestamps();
});
```

**Factory** (`app/Services/SimproConnectorFactory.php`):

```php
namespace App\Services;

use App\Models\SimproConnection;
use Illuminate\Support\Facades\Cache;
use Saloon\RateLimitPlugin\Stores\LaravelCacheStore;
use Simpro\PhpSdk\Simpro\Cache\CacheConfig;
use Simpro\PhpSdk\Simpro\Connectors\SimproOAuthConnector;
use Simpro\PhpSdk\Simpro\RateLimit\RateLimitConfig;

class SimproConnectorFactory
{
    /**
     * Create an unauthenticated connector for the OAuth flow.
     */
    public function make(string $buildUrl): SimproOAuthConnector
    {
        return new SimproOAuthConnector(
            baseUrl: $buildUrl,
            clientId: config('services.simpro.client_id'),
            clientSecret: config('services.simpro.client_secret'),
            redirectUri: config('services.simpro.redirect_uri'),
            scopes: [],
            requestTimeout: 30,
            rateLimitConfig: new RateLimitConfig(
                store: new LaravelCacheStore(Cache::store()),
            ),
            cacheConfig: CacheConfig::laravel(
                cache: Cache::store('redis'),
                expiryInSeconds: 300,
            ),
        );
    }

    /**
     * Create an authenticated connector from a stored connection.
     */
    public function authenticated(SimproConnection $connection): SimproOAuthConnector
    {
        $connector = $this->make($connection->build_url);
        $authenticator = $connection->simpro_auth;

        // Refresh the token if expired
        if ($authenticator->hasExpired()) {
            $authenticator = $connector->refreshAccessToken($authenticator);
            $connection->update(['simpro_auth' => $authenticator]);
        }

        $connector->authenticate($authenticator);

        return $connector;
    }
}
```

**Controller** (`app/Http/Controllers/SimproOAuthController.php`):

```php
namespace App\Http\Controllers;

use App\Models\SimproConnection;
use App\Services\SimproConnectorFactory;
use Illuminate\Http\Request;

class SimproOAuthController extends Controller
{
    public function __construct(
        private SimproConnectorFactory $factory
    ) {}

    public function redirect(Request $request)
    {
        $request->validate(['build_url' => 'required|url']);

        // Store the build URL in session for the callback
        session(['simpro_build_url' => $request->build_url]);

        $connector = $this->factory->make($request->build_url);

        return redirect($connector->getAuthorizationUrl());
    }

    public function callback(Request $request)
    {
        $buildUrl = session('simpro_build_url');
        $connector = $this->factory->make($buildUrl);

        $authenticator = $connector->getAccessToken($request->code);

        SimproConnection::updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'build_url' => $buildUrl,
                'simpro_auth' => $authenticator,
            ]
        );

        return redirect()->route('dashboard')
            ->with('success', 'Simpro account connected successfully.');
    }
}
```

**Usage Example**:

```php
use App\Models\SimproConnection;
use App\Services\SimproConnectorFactory;

class SyncJobsCommand extends Command
{
    public function handle(SimproConnectorFactory $factory): void
    {
        $connections = SimproConnection::all();

        foreach ($connections as $connection) {
            $simpro = $factory->authenticated($connection);

            foreach ($simpro->jobs()->list()->items() as $job) {
                // Process each job...
            }
        }
    }
}
```

#### Further Reading

- [Saloon Laravel Integration](https://docs.saloon.dev/installable-plugins/laravel-integration)
- [Saloon OAuth2 Authentication](https://docs.saloon.dev/digging-deeper/oauth2-authentication/oauth2-authentication)
- [Understanding Simpro Grant Types](https://developer.simprogroup.com/apidoc/?page=b0d64c044a432c8e0f9f01e0641d5596)

### Handling Errors

The SDK uses Saloon's `AlwaysThrowOnErrors` trait on the connector, which means exceptions will automatically be thrown whenever a request fails (4xx or 5xx response status codes). You don't need to manually check if a request failed or call `throw()` on responses - exceptions are thrown automatically.

Saloon's built-in exceptions are used for most errors, with a custom exception for validation errors (422 status codes).

#### Exception Hierarchy

The SDK uses Saloon's exception hierarchy:

```
SaloonException
├── FatalRequestException (Connection Errors)
└── RequestException (Request Errors)
    ├── ServerException (5xx)
    │   ├── InternalServerErrorException (500)
    │   ├── ServiceUnavailableException (503)
    │   └── GatewayTimeoutException (504)
    └── ClientException (4xx)
        ├── UnauthorizedException (401)
        ├── PaymentRequiredException (402)
        ├── ForbiddenException (403)
        ├── NotFoundException (404)
        ├── MethodNotAllowedException (405)
        ├── RequestTimeOutException (408)
        ├── UnprocessableEntityException (422)
        │   └── ValidationException (422 - Custom)
        └── TooManyRequestsException (429)
```

#### Validation Errors

For validation errors (422 status code), the SDK throws a custom `ValidationException` which extends Saloon's `UnprocessableEntityException`. This exception provides additional methods to access validation error details:

```php
use Saloon\Exceptions\Request\ClientException;
use Saloon\Exceptions\Request\ServerException;
use Simpro\PhpSdk\Simpro\Exceptions\ValidationException;

try {
    // Make an API request
    $response = $connector->send($request);
} catch (ValidationException $exception) {
    // Handle validation errors (422)
    // Get a string describing all errors
    $message = $exception->getMessage();

    // Get all validation errors as an array
    $errors = $exception->getErrors();
    // ['field_name' => ['Error message 1', 'Error message 2']]

    // Get errors for a specific field
    $fieldErrors = $exception->getErrorsForField('field_name');

    // Check if a specific field has errors
    $hasErrors = $exception->hasErrorsForField('field_name');

    // Get all error messages as a flat array
    $allMessages = $exception->getAllErrorMessages();

    // Access the Saloon Response object for debugging
    $response = $exception->getResponse();
} catch (ClientException $exception) {
    // Handle 4xx errors (401, 403, 404, etc.)
    $message = $exception->getMessage();
    $response = $exception->getResponse();
} catch (ServerException $exception) {
    // Handle 5xx errors
    $message = $exception->getMessage();
    $response = $exception->getResponse();
}
```

#### Connection Errors

If Saloon cannot connect to the API, it will throw a `FatalRequestException`:

```php
use Saloon\Exceptions\Request\FatalRequestException;

try {
    $response = $connector->send($request);
} catch (FatalRequestException $exception) {
    // Handle connection errors (network issues, DNS failures, etc.)
    $message = $exception->getMessage();
}
```

## Resources

The SDK provides resource-based APIs for working with different Simpro entities. Each resource has its own documentation page with detailed examples and usage instructions.

### Available Resources

- **[Info](docs/info-resource.md)** - Get information about your Simpro instance, including version, country, and enabled features
- **[Companies](docs/companies-resource.md)** - Access company information and manage multi-company environments
- **[Jobs](docs/jobs-resource.md)** - Manage jobs with full CRUD operations (create, list, get, update, delete)
- **[Customers](docs/customers-resource.md)** - Customer company management with full CRUD operations
- **[Quotes](docs/quotes-resource.md)** - Quote management with full CRUD operations
- **[Invoices](docs/invoices-resource.md)** - Invoice management with full CRUD operations
- **[Schedules](docs/schedules-resource.md)** - View job and activity schedules (read-only)
- **[Employees](docs/employees-resource.md)** - Employee management with full CRUD operations
- **[CurrentUser](docs/current-user-resource.md)** - Get authenticated user information
- **[Reports](docs/reports-resource.md)** - Access job cost-to-complete reports (financial and operations)
- **[Setup](docs/setup-resource.md)** - Configure system settings: webhooks, tax codes, payment methods, custom fields, labor rates, and more

More resources will be added as development continues.

## Pagination and Querying

Resource methods that return lists use a `QueryBuilder` instance that provides fluent search, filtering, and ordering capabilities. The query builder wraps Saloon's pagination plugin and handles pagination automatically.

### Basic Usage

```php
// List all companies (returns QueryBuilder)
$companies = $connector->companies()->list();

// Iterate over all items across all pages
foreach ($companies->items() as $company) {
    echo "{$company->id}: {$company->name}\n";
}

// Or get the first result
$first = $connector->companies()->list()->first();

// Or get all results as an array
$all = $connector->companies()->list()->all();
```

### Fluent Search API

The SDK provides a fluent search API for building complex queries:

```php
use Simpro\PhpSdk\Simpro\Query\Search;

// Simple wildcard search
$result = $connector->companies()->listDetailed()
    ->search(Search::make()->column('Name')->find('Test'))
    ->first();

// Multiple search criteria with OR logic
$results = $connector->companies()->list()
    ->search([
        Search::make()->column('Name')->find('Corp'),
        Search::make()->column('ID')->greaterThan(5),
    ])
    ->matchAny()
    ->orderByDesc('Name')
    ->collect();

// Alternative where() syntax
$results = $connector->companies()->list()
    ->where('Name', 'like', 'Acme')
    ->where('ID', '>=', 10)
    ->first();
```

### Search Methods

The `Search` class provides these methods:

| Method | Description | Example Value |
|--------|-------------|---------------|
| `equals($value)` | Exact match | `Test` |
| `find($value)` | Wildcard search | `%25Test%25` |
| `startsWith($value)` | Starts with | `Test%25` |
| `endsWith($value)` | Ends with | `%25Test` |
| `lessThan($value)` | Less than | `<10` |
| `lessThanOrEqual($value)` | Less than or equal | `<=10` |
| `greaterThan($value)` | Greater than | `>10` |
| `greaterThanOrEqual($value)` | Greater than or equal | `>=10` |
| `notEqual($value)` | Not equal | `!=Cancelled` |
| `between($min, $max)` | Range | `1~100` |
| `in($array)` | In list | `Active,Pending` |
| `notIn($array)` | Not in list | `!=Cancelled,!=Deleted` |

### Using Laravel Collections

The `collect()` method returns a `LazyCollection` for powerful data transformations:

```php
$companies = $connector->companies()->list();

$filtered = $companies->collect()
    ->filter(fn($company) => $company->id > 0)
    ->map(fn($company) => ['id' => $company->id, 'name' => $company->name])
    ->sortBy('name');
```

### Array Filters (Backward Compatible)

You can also pass filters as an array to list methods:

```php
$companies = $connector->companies()->list(['Name' => 'Test Company']);
```

### Controlling Page Size

By default, 30 items are fetched per page:

```php
$builder = $connector->companies()->list();
$builder->getPaginator()->setPerPageLimit(100);
```

### Pagination Metadata

The Simpro API returns pagination information in response headers:
- `Result-Total`: Total number of results
- `Result-Pages`: Total number of pages

The paginator automatically reads these headers and handles pagination for you.

## Security

If you discover any security related issues, please email support@stitch-digital.com instead of using the issue tracker.

## Credits

- [Stitch Digital](https://www.stitch-digital.com)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
