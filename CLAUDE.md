# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Run all tests
composer test

# Run a single test file
./vendor/bin/pest tests/Requests/Companies/ListCompaniesRequestTest.php

# Run a single test by name
./vendor/bin/pest --filter "sends list companies request"

# Code formatting (Laravel Pint)
composer format

# Static analysis (PHPStan level 5)
composer analyse

# Generate PHPStan baseline
composer baseline

# Run all checks (format + analyse + test)
composer review
```

## Architecture

This is a PHP SDK for the Simpro API built on [Saloon v3](https://docs.saloon.dev/). The namespace is `Simpro\PhpSdk\Simpro`.

### Layer Structure

1. **Connectors** (`src/Connectors/`) - Entry points for API interaction
   - `AbstractSimproConnector` - Base class with shared functionality, pagination support, and error handling
   - `SimproApiKeyConnector` - For server-to-server integrations
   - `SimproOAuthConnector` - For web applications with user authorization

2. **Resources** (`src/Resources/`) - API resource wrappers accessed via connector methods
   - Accessed via traits in `src/Concerns/` (e.g., `$connector->companies()` returns `CompanyResource`)
   - Return `QueryBuilder` for list operations or DTOs for single-item operations

3. **Requests** (`src/Requests/`) - Individual API endpoint definitions
   - Implement Saloon's `Request` class
   - Define endpoint, HTTP method, and DTO transformation via `createDtoFromResponse()`
   - Paginatable requests implement `Saloon\PaginationPlugin\Contracts\Paginatable`

4. **Data** (`src/Data/`) - Immutable DTOs using `readonly class`
   - Use `fromArray()` and `fromResponse()` factory methods
   - Nested objects are hydrated recursively

5. **Query** (`src/Query/`) - Fluent query building
   - `QueryBuilder` - Wraps pagination with search, filtering, and ordering
   - `Search` - Builds Simpro search syntax (wildcards, comparisons, ranges)

### Adding a New API Resource

1. Create Request class(es) in `src/Requests/{ResourceName}/`
2. Create DTO(s) in `src/Data/{ResourceName}/`
3. Create Resource class in `src/Resources/{ResourceName}Resource.php`
4. Create trait in `src/Concerns/Supports{ResourceName}Endpoints.php`
5. Add trait to `AbstractSimproConnector`

### Testing

Tests use Pest with Saloon's MockClient. Test fixtures are JSON files in `tests/Fixtures/Saloon/`.

```php
MockClient::global([
    ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
]);
```

Fixtures follow Saloon's format with `statusCode`, `headers`, and `data` keys.

## Code Style

- All classes use `declare(strict_types=1)`
- Classes should be `final` (enforced by Pint, except `src/Exceptions/`)
- Laravel preset for Pint
- PHPStan level 5
