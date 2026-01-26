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

// Make API requests
$clients = $connector->clients()->list();

// Iterate over clients
foreach ($clients->items() as $client) {
    $clientId = $client->id;
    $clientName = $client->attributes->name;
}
```

Behind the scenes, the SDK uses [Saloon](https://docs.saloon.dev) to make the HTTP requests.

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
$clients = $connector->clients()->list();

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
$clients = $connector->clients()->list();
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

### Handling errors

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
    $paginator = $simpro->clients()->list();
} catch (ValidationException $exception) {
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
    $paginator = $simpro->clients()->list();
} catch (FatalRequestException $exception) {
    // Handle connection errors (network issues, DNS failures, etc.)
    $message = $exception->getMessage();
}
```

## Resources

The SDK provides resource-based APIs for working with different entities. Each resource is accessed through a method on the main SDK instance.

### Client Resource

The client resource provides methods for working with clients. Access it through the `clients()` method:

```php
$simpro = Simpro::make(...);
$clients = $simpro->clients();
```

Methods that return lists of clients (like `list()`, `findById()`, etc.) return a `SimproPaginator` instance. See the [Pagination](#pagination) section for details on working with paginated results. Future methods like `create()`, `update()`, and `delete()` will return different response types.

#### Listing Clients

The following methods return a `SimproPaginator` instance:

**List all clients:**

```php
$paginator = $simpro->clients()->list();
```

**List active clients:**

```php
$paginator = $simpro->clients()->listActive();
```

**List inactive clients:**

```php
$paginator = $simpro->clients()->listInactive();
```

#### Finding Clients

**Find by ID:**

```php
$paginator = $simpro->clients()->findById(123);
```

**Find by name:**

```php
// Exact match
$paginator = $simpro->clients()->findByName('Acme Corp', strict: true);

// Contains match (default)
$paginator = $simpro->clients()->findByName('Acme');
```

**Search clients:**

```php
// Search across multiple fields
$paginator = $simpro->clients()->search('construction');
```

#### Filtering Clients

The client resource provides many filtering methods. All filtering methods return a `SimproPaginator` instance. You can chain multiple filters together, or pass filters directly to the `list()` method for better performance.

**Filtering by Account Manager:**

```php
// Single or multiple account manager IDs
$paginator = $simpro->clients()->whereAccountManager(5);
$paginator = $simpro->clients()->whereAccountManager([5, 10, 15]);

// Exclude account managers
$paginator = $simpro->clients()->whereNotAccountManager(5);
```

**Filtering by Billing Card:**

```php
$paginator = $simpro->clients()->whereBillingCard(3);
$paginator = $simpro->clients()->whereBillingCard([3, 7]);
$paginator = $simpro->clients()->whereNotBillingCard(3);
```

**Filtering by Date:**

```php
// Created dates
$paginator = $simpro->clients()->createdBefore('2024-01-01T00:00:00Z');
$paginator = $simpro->clients()->createdAfter('2024-01-01T00:00:00Z');

// Updated dates
$paginator = $simpro->clients()->updatedBefore('2024-01-01T00:00:00Z');
$paginator = $simpro->clients()->updatedAfter('2024-01-01T00:00:00Z');
$paginator = $simpro->clients()->updatedSince('2024-01-01T00:00:00Z'); // alias
```

**Filtering by Sector:**

```php
use Simpro\PhpSdk\Simpro\Data\Clients\Sector;

// Using Sector enum
$paginator = $simpro->clients()->whereSector(Sector::Construction);
$paginator = $simpro->clients()->whereSector([Sector::Construction, Sector::RetailTrade]);

// Using string (backward compatibility)
$paginator = $simpro->clients()->whereSector('Construction');

// Exclude sectors
$paginator = $simpro->clients()->whereNotSector(Sector::Construction);
```

**Filtering by Price Tier:**

```php
$paginator = $simpro->clients()->wherePriceTier(2);
$paginator = $simpro->clients()->wherePriceTier([2, 4, 6]);
$paginator = $simpro->clients()->whereNotPriceTier(2);
```

**Filtering by Active Status:**

```php
$paginator = $simpro->clients()->whereIsActive(true);
$paginator = $simpro->clients()->whereIsActive(false);
```

**Filtering by Report Settings:**

```php
$paginator = $simpro->clients()->whereReportWhitelabel(true);
$paginator = $simpro->clients()->whereReportManual(true);
```

**Filtering by Billing Settings:**

```php
$paginator = $simpro->clients()->whereBillingManual(true);
$paginator = $simpro->clients()->whereBillingFixedPrice(true);
```

**Filtering by Quoting Settings:**

```php
$paginator = $simpro->clients()->whereQuotingAutoRemindersEnabled(true);
```

**Filtering by Properties:**

```php
$paginator = $simpro->clients()->whereHasProperties(true);
$paginator = $simpro->clients()->whereHasActiveProperty(true);
```

**Filtering by Duplicate Status:**

```php
$paginator = $simpro->clients()->whereIsDuplicated(true);
```

**Filtering by Client Group:**

```php
$paginator = $simpro->clients()->whereParentClientGroup(10);
$paginator = $simpro->clients()->whereClientGroup(10);
$paginator = $simpro->clients()->whereNotClientGroup(10);
```

**Filtering by Branch:**

```php
$paginator = $simpro->clients()->whereBranch(5);
$paginator = $simpro->clients()->whereBranch([5, 10]);
$paginator = $simpro->clients()->whereNotBranch(5);
```

**Filtering by Account:**

```php
$paginator = $simpro->clients()->whereHasAccount(true);
```

**Filtering by Tags:**

```php
$paginator = $simpro->clients()->whereTags(1);
$paginator = $simpro->clients()->whereTags([1, 2, 3]);
$paginator = $simpro->clients()->whereNotTags(1);
```

**Filtering by Business Hours:**

```php
$paginator = $simpro->clients()->whereHasBusinessHours(true);
```

**Filtering by Phone Number:**

```php
$paginator = $simpro->clients()->wherePhoneNumberContains('555');
```

**Filtering by Extra Fields:**

```php
$paginator = $simpro->clients()->whereExtraFields([
    'custom_field_1' => 'value1',
    'custom_field_2' => 'value2',
]);
```

**Custom Filters:**

You can pass custom filters directly to the `list()` method for better performance when using multiple filters:

```php
$paginator = $simpro->clients()->list([
    'is_active' => true,
    'sector' => Sector::Construction->value,
    'account_manager' => [5, 10],
    'created_after' => '2024-01-01T00:00:00Z',
]);
```

**Chaining Filters:**

You can also chain filter methods together, though using `list()` with an array is more efficient:

```php
$paginator = $simpro->clients()
    ->whereIsActive(true)
    ->whereSector(Sector::Construction)
    ->whereAccountManager(5)
    ->createdAfter('2024-01-01T00:00:00Z');
```

#### Client Object Structure

Each client returned from listing methods is a `Client` DTO with the following structure:

```php
$client->id;                    // string - Client ID
$client->type;                  // string - Always "Client"
$client->attributes->name;       // string|null - Client name
$client->attributes->isActive;   // bool|null - Active status
$client->attributes->sector;     // Sector|null - Sector enum
$client->attributes->created;    // DateTimeImmutable|null - Creation date
$client->attributes->updated;    // DateTimeImmutable|null - Last update date
$client->attributes->ref;        // string|null - Reference number
$client->attributes->contactName; // string|null - Contact name
$client->attributes->contactEmail; // string|null - Contact email
// ... and many more properties (see ClientAttributes class)
$client->relationships;         // array - Related resources
```

## Pagination

Many resource methods return a `SimproPaginator` instance for working with paginated API responses. The SDK uses Saloon's pagination plugin to handle pagination automatically. The Simpro API uses limit/offset pagination, where results are divided into pages. The SDK's paginator handles all of this for you, allowing you to iterate through every result across every page in one loop.

The paginator is a custom PHP iterator, meaning it can be used in foreach loops. It's also memory efficient - it only keeps one page in memory at a time, so you can iterate through thousands of pages and millions of results without running out of memory.

### Iterating Over Items

The simplest way to use the paginator is to iterate over items using the `items()` method. This will give you each item across multiple pages:

```php
$paginator = $simpro->clients()->list();

foreach ($paginator->items() as $client) {
    $clientId = $client->id;
    $clientName = $client->attributes->name;
    $isActive = $client->attributes->isActive;
    $sector = $client->attributes->sector?->value;
    $createdAt = $client->attributes->created?->format('Y-m-d H:i:s');
}
```

### Using Laravel Collections

If you're using Laravel (or have `illuminate/collections` installed), you can use the `collect()` method to get a `LazyCollection`. This allows you to use powerful collection methods like `filter()`, `map()`, `sort()`, and more while keeping memory consumption low:

```php
use Simpro\PhpSdk\Simpro\Data\Clients\Sector;

$paginator = $simpro->clients()->list();
$collection = $paginator->collect();

$activeConstructionClients = $collection
    ->filter(function ($client) {
        return $client->attributes->isActive === true 
            && $client->attributes->sector === Sector::Construction;
    })
    ->map(function ($client) {
        return [
            'id' => $client->id,
            'name' => $client->attributes->name,
            'sector' => $client->attributes->sector?->value,
        ];
    })
    ->sortBy('name');

foreach ($activeConstructionClients as $client) {
    $clientName = $client['name'];
}
```

### Collecting All Items

You can collect all items into an array if you need to work with the complete dataset:

```php
$paginator = $simpro->clients()->list();
$allClients = $paginator->collect()->all();

// Now you have an array of all clients
$clientCount = count($allClients);
$firstClient = $allClients[0];
```

### Accessing Pagination Metadata

The paginator provides methods to access pagination information:

```php
$paginator = $simpro->clients()->list();

// Get the total number of pages
$totalPages = $paginator->getTotalPages();

// Get the first item without iterating
$firstClient = $paginator->items()->current();
```

### Controlling Page Size

By default, 50 items are fetched per page. You can control pagination by setting the per-page limit:

```php
$paginator = $simpro->clients()->list();
$paginator->setPerPageLimit(100); // Fetch 100 items per page

foreach ($paginator->items() as $client) {
    $clientName = $client->attributes->name;
}
```

The paginator will automatically handle fetching subsequent pages as you iterate through the results. You don't need to worry about managing page numbers or offsets - just iterate and the SDK handles the rest.

## Using Saloon requests directly

You can use the request classes directly for full control:

```php
use Simpro\PhpSdk\Simpro\Simpro;
use Simpro\PhpSdk\Simpro\Requests\Clients\ListClientsRequest;

$simpro = Simpro::make(...);
$request = new ListClientsRequest();

$response = $simpro->send($request)->dto();
```

## Security

If you discover any security related issues, please email support@stitch-digital.com instead of using the issue tracker.

## Credits

- [Stitch Digital](https://www.stitch-digital.com)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
