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
