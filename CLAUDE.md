# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

This is an unofficial PHP SDK for the Simpro API, built with [Saloon v3](https://docs.saloon.dev/). It provides a developer-friendly interface for interacting with Simpro's field service management platform.

**Status**: Active development, pre-v1.0.0. No stable release yet.

## Development Commands

### Testing
```bash
# Run all tests in parallel
composer test

# Run a single test file
vendor/bin/pest tests/Connectors/SimproApiKeyConnectorTest.php

# Run a specific test
vendor/bin/pest --filter="test_name"
```

### Code Quality
```bash
# Format code (Laravel Pint)
composer format

# Static analysis (PHPStan level 5)
composer analyse

# Generate PHPStan baseline
composer baseline

# Run all checks (format + analyse + test)
composer review
```

## Architecture

### Authentication Layer

The SDK supports two authentication methods through a class hierarchy:

```
AbstractSimproConnector (shared functionality)
├── SimproOAuthConnector (OAuth Authorization Code Grant)
└── SimproApiKeyConnector (API Key / Token)
```

**AbstractSimproConnector** (`src/Connectors/AbstractSimproConnector.php`):
- Base class for all connectors
- Uses Saloon traits: `AcceptsJson`, `AlwaysThrowOnErrors`, `HasTimeout`
- Implements `HasPagination` for automatic pagination support
- Handles validation errors (422 status) via custom `ValidationException`
- Provides `clients()` method through `SupportsClientsEndpoints` trait

**SimproOAuthConnector** (`src/Connectors/SimproOAuthConnector.php`):
- Uses Saloon's `AuthorizationCodeGrant` trait
- Suitable for web applications with user authorization flows
- Manages access token, refresh token, and expiry
- Requires: baseUrl, clientId, clientSecret, redirectUri

**SimproApiKeyConnector** (`src/Connectors/SimproApiKeyConnector.php`):
- Simple token-based authentication
- Suitable for server-to-server integrations
- Requires: baseUrl, apiKey

### Resource Layer

Resources provide fluent, chainable methods for API interactions.

**ClientResource** (`src/Resources/ClientResource.php`):
- Extends Saloon's `BaseResource`
- All methods return `SimproPaginator` instances
- Core method: `list(array $filters = [])` - accepts any filter parameters
- Convenience methods: `listActive()`, `findById()`, `findByName()`, `search()`
- Filter methods: `whereAccountManager()`, `whereSector()`, `createdAfter()`, etc.
- Filter methods internally call `list()` with appropriate parameters

**Pattern**: Resource methods build query parameters and delegate to request classes.

### Request Layer

**ListClientsRequest** (`src/Requests/Clients/ListClientsRequest.php`):
- Defines the HTTP method (GET) and endpoint (`/v1.0/companies/clients/`)
- Specifies the response DTO (`ClientListResponse`)
- Query parameters are added by the resource layer

**Pattern**: Each API endpoint has a corresponding request class. New endpoints should follow this structure.

### Data Layer

**DTOs** (`src/Data/`):
- Immutable data objects using readonly properties
- `Client`: Represents a single client with `id`, `type`, `attributes`, `relationships`
- `ClientAttributes`: Contains all client properties (name, isActive, sector, etc.)
- `ClientListResponse`: Response wrapper containing array of `Client` DTOs
- `Sector`: Enum for client industry sectors
- `PaginationMeta` and `PaginationLinks`: Pagination metadata

**Pattern**: DTOs are created automatically by Saloon from API responses.

### Pagination

**SimproPaginator** (`src/Paginators/SimproPaginator.php`):
- Extends Saloon's `PagedPaginator`
- Uses limit/offset pagination (query params: `page`, `pageSize`)
- Reads pagination metadata from response headers: `Result-Total`, `Result-Pages`
- Memory efficient: loads one page at a time
- Usage: `foreach ($paginator->items() as $item)` or `$paginator->collect()` for Laravel collections

**Pattern**: Pagination is automatic. Resources return paginators, not arrays.

### Exception Handling

**ValidationException** (`src/Exceptions/ValidationException.php`):
- Custom exception for 422 validation errors
- Extends Saloon's `UnprocessableEntityException`
- Provides: `getErrors()`, `getErrorsForField()`, `hasErrorsForField()`, `getAllErrorMessages()`
- Instantiated in `AbstractSimproConnector::getRequestException()`

**Pattern**: All other HTTP errors use Saloon's built-in exceptions. The connector's `AlwaysThrowOnErrors` trait ensures exceptions are thrown automatically.

## Code Conventions

### Strict Types
All PHP files use `declare(strict_types=1);` at the top.

### Final Classes
Most classes are `final` (enforced by Pint). Exceptions: abstract classes and exception classes (see pint.json).

### Formatting
Uses Laravel Pint preset with `declare_strict_types` and `final_class` rules. Run `composer format` before committing.

### Testing
- Uses Pest (PHPUnit wrapper)
- Test fixtures in `tests/Fixtures/Saloon/`
- `TestCase` sets up `SimproApiKeyConnector` with mock client
- Mock fixtures follow Saloon's convention: named after request classes

### Namespacing
All classes under `Simpro\PhpSdk\Simpro\` namespace.

## Adding New Resources

To add support for a new Simpro API endpoint (e.g., Jobs):

1. **Create Request class**: `src/Requests/Jobs/ListJobsRequest.php`
   - Define method (GET/POST/etc.) and endpoint
   - Specify response DTO class

2. **Create DTOs**: `src/Data/Jobs/Job.php`, `JobAttributes.php`, `JobListResponse.php`
   - Use readonly properties
   - Match API response structure

3. **Create Resource class**: `src/Resources/JobResource.php`
   - Extend `BaseResource`
   - Implement `list()` method and any convenience methods
   - Return `SimproPaginator` instances

4. **Create Trait**: `src/Concerns/SupportsJobsEndpoints.php`
   - Add `jobs(): JobResource` method

5. **Add trait to AbstractSimproConnector**:
   - `use SupportsJobsEndpoints;`

6. **Write tests**: `tests/Resources/JobResourceTest.php`, `tests/Requests/Jobs/ListJobsRequestTest.php`
   - Create mock fixtures in `tests/Fixtures/Saloon/`

## Important Notes

- **No Simpro Instance**: This SDK does not have a `Simpro` main class anymore. Use `SimproOAuthConnector` or `SimproApiKeyConnector` directly.
- **Saloon v3**: The SDK is built on Saloon v3. Refer to [Saloon documentation](https://docs.saloon.dev/) for advanced features.
- **PHP 8.2+**: Minimum PHP version is 8.2 (uses readonly properties, typed properties).
- **Active Development**: API may change. Check `IMPLEMENTATION_SUMMARY.md` for recent architecture changes.
- **Pagination Headers**: Simpro API returns pagination data in custom headers (`Result-Total`, `Result-Pages`), not in response body.
