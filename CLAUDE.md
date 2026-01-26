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

## API Reference

### Swagger Specification
The complete Simpro API specification is available in `swagger 2.json` in the root directory. This OpenAPI 2.0 spec contains:
- All available endpoints with request/response schemas
- Field types, constraints, and descriptions
- Example values for each field

When implementing new endpoints:
1. Search the swagger file for the endpoint path (e.g., `/api/v1.0/jobs/`)
2. Locate the response schema in the `definitions` section (e.g., `Jobs.GET_Entity`)
3. Map the PascalCase field names to camelCase for PHP DTOs
4. Use the field types and required/optional flags to create DTO properties

**Example**: For `/api/v1.0/info/`, the swagger shows `Version` (string, required) maps to `public string $version` in the DTO.

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
- Resource methods are added via traits (e.g., `SupportsJobsEndpoints`)

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

Resources provide fluent, chainable methods for API interactions. **Implemented resources**: InfoResource. When adding resources, follow the pattern in "Adding New Resources" below.

**Pattern**: Resources extend `BaseResource`, build query parameters, and delegate to request classes. All list methods should return `SimproPaginator` instances. Single-entity methods return DTOs directly.

### Request Layer

Request classes define HTTP endpoints and response DTOs. **Implemented requests**: InfoRequest.

**Pattern**: Each API endpoint has a corresponding request class that extends `Saloon\Http\Request`. See "Adding New Resources" for the complete structure.

### Data Layer

**DTOs** (`src/Data/`):
- Immutable data objects using readonly properties
- **Implemented DTOs**: Info

**Pattern**: DTOs are created automatically by Saloon from API responses. Each resource should have its own DTO classes matching the API response structure.

### Pagination

**SimproPaginator** (`src/Paginators/SimproPaginator.php`):
- Extends Saloon's `PagedPaginator`
- Uses limit/offset pagination (query params: `page`, `pageSize`)
- Reads pagination metadata from response headers: `Result-Total`, `Result-Pages`
- Automatically extracts items from list response DTOs
- Memory efficient: loads one page at a time
- Usage: `foreach ($paginator->items() as $item)` or `$paginator->collect()` for Laravel collections

**Pattern**: Pagination is automatic. Resources return paginators, not arrays. The paginator intelligently extracts arrays from response DTOs.

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

### Documentation Examples
When writing code examples in documentation (README, resource docs, etc.):
- **Prefer return statements over echo** - Return structured data (arrays, objects) rather than using echo statements
- **Use arrays for multiple values** - When displaying multiple pieces of information, return an array instead of multiple echo statements
- **Modern PHP style** - Avoid dated patterns like echo concatenation in favor of returning data that can be used programmatically

**Good Example:**
```php
$info = $connector->info()->get();

return [
    'version' => $info->version,
    'country' => $info->country,
    'features' => [
        'maintenancePlanner' => $info->maintenancePlanner,
        'multiCompany' => $info->multiCompany,
    ],
];
```

**Avoid:**
```php
echo "Version: {$info->version}\n";
echo "Country: {$info->country}\n";
```

## Adding New Resources

This section provides the complete structure for adding a new Simpro API resource (e.g., Jobs, Quotes, Customers, etc.). Follow this pattern exactly to maintain consistency.

### Single-Entity Endpoints (Non-Paginated)

Some endpoints like `/info/` return a single object rather than a paginated list. For these:
- Skip the ListResponse wrapper DTO
- Resource method returns the DTO directly, not a paginator
- No pagination headers needed in test fixtures
- Example: `$connector->info()->info()` returns `Info` DTO

### Step 1: Create Request Classes

Request classes define the HTTP method, endpoint, and response DTO.

**File**: `src/Requests/Jobs/ListJobsRequest.php`

```php
<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Jobs;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Jobs\JobListResponse;

final class ListJobsRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/v1.0/jobs/';
    }

    protected function defaultQuery(): array
    {
        return [
            // Default query params if needed
        ];
    }

    public function createDtoFromResponse(Response $response): JobListResponse
    {
        return JobListResponse::fromResponse($response);
    }
}
```

**Pattern Notes**:
- Class is `final`
- Use `declare(strict_types=1);`
- Method is defined using Saloon's `Method` enum
- Endpoint starts with `/v1.0/` (adjust based on actual Simpro API version)
- `createDtoFromResponse()` method creates the DTO from the response
- Import `Saloon\Http\Response` for the response parameter
- Query parameters are added by the resource layer, not hardcoded here

### Step 2: Create Data Transfer Objects (DTOs)

DTOs represent the API response structure. Create three files per resource:

**File**: `src/Data/Jobs/Job.php`

```php
<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

final readonly class Job
{
    public function __construct(
        public string $id,
        public string $type,
        public JobAttributes $attributes,
        public array $relationships = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            type: $data['type'],
            attributes: JobAttributes::fromArray($data['attributes'] ?? []),
            relationships: $data['relationships'] ?? [],
        );
    }
}
```

**File**: `src/Data/Jobs/JobAttributes.php`

```php
<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

use DateTimeImmutable;

final readonly class JobAttributes
{
    public function __construct(
        public ?string $name = null,
        public ?string $description = null,
        public ?string $status = null,
        public ?DateTimeImmutable $created = null,
        public ?DateTimeImmutable $updated = null,
        // Add all properties from the API response
        // Use nullable types for optional fields
        // Use DateTimeImmutable for datetime fields
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['Name'] ?? null,
            description: $data['Description'] ?? null,
            status: $data['Status'] ?? null,
            created: isset($data['Created']) ? new DateTimeImmutable($data['Created']) : null,
            updated: isset($data['Updated']) ? new DateTimeImmutable($data['Updated']) : null,
        );
    }
}
```

**File**: `src/Data/Jobs/JobListResponse.php`

```php
<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

use Saloon\Http\Response;

final readonly class JobListResponse
{
    /**
     * @param  array<Job>  $jobs
     */
    public function __construct(
        public array $jobs,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        $jobs = array_map(
            fn(array $item) => Job::fromArray($item),
            $data['jobs'] ?? []
        );

        return new self(jobs: $jobs);
    }
}
```

**Pattern Notes**:
- All DTOs are `final readonly`
- Use constructor property promotion
- Each DTO must have a static factory method: `fromResponse()` for top-level DTOs or `fromArray()` for nested DTOs
- The list response DTO has a single array property named after the resource (e.g., `$jobs`, `$quotes`, `$customers`)
- This property name MUST match the resource name for the paginator to work correctly
- Attributes class contains all the actual data fields
- Use `?Type` for nullable/optional fields
- Use `DateTimeImmutable` for datetime fields and manually construct from strings in fromArray
- Use enums for fields with fixed values (like status, sector, etc.)
- Map PascalCase API field names to camelCase PHP property names in the factory methods

### Step 3: Create Enums (if needed)

If the API returns fields with fixed values, create enums:

**File**: `src/Data/Jobs/JobStatus.php`

```php
<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

enum JobStatus: string
{
    case Pending = 'Pending';
    case InProgress = 'In Progress';
    case Completed = 'Completed';
    case Cancelled = 'Cancelled';
}
```

**Pattern Notes**:
- Use backed enums with string values
- Values should match exactly what the API returns
- Use enums in the attributes class: `public ?JobStatus $status = null`

### Step 4: Create Resource Class

Resources provide the public API for interacting with endpoints.

**File**: `src/Resources/JobResource.php`

```php
<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Paginators\SimproPaginator;
use Simpro\PhpSdk\Simpro\Requests\Jobs\ListJobsRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class JobResource extends BaseResource
{
    /**
     * List jobs with any supported filters.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): SimproPaginator
    {
        $request = new ListJobsRequest;

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return $this->connector->paginate($request);
    }

    // Convenience methods that call list() with specific filters

    public function listActive(): SimproPaginator
    {
        return $this->list(['is_active' => true]);
    }

    public function listByStatus(string $status): SimproPaginator
    {
        return $this->list(['status' => $status]);
    }

    public function findById(int|string $jobId): SimproPaginator
    {
        return $this->list(['id' => $jobId]);
    }

    public function search(string $query): SimproPaginator
    {
        return $this->list(['text_contains' => $query]);
    }

    // Filter methods - all call list() internally

    public function whereCustomerId(int|string $customerId): SimproPaginator
    {
        return $this->list(['customer_id' => $customerId]);
    }

    public function createdAfter(string $isoDateTime): SimproPaginator
    {
        return $this->list(['created_after' => $isoDateTime]);
    }

    public function updatedSince(string $isoDateTime): SimproPaginator
    {
        return $this->list(['updated_after' => $isoDateTime]);
    }
}
```

**Pattern Notes**:
- Class is `final` and extends `BaseResource`
- Add `@property AbstractSimproConnector $connector` for IDE support
- Core method is `list(array $filters = [])` which accepts any filter parameters
- Convenience methods call `list()` with specific filters
- Array values are converted to comma-separated strings (API convention)
- All values are cast to strings before being added to query
- All methods return `SimproPaginator` (never arrays)
- Method names should be descriptive: `whereX()`, `findByX()`, `listX()`

### Step 5: Create Concerns Trait

Traits add resource methods to the connector.

**File**: `src/Concerns/SupportsJobsEndpoints.php`

```php
<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\JobResource;

trait SupportsJobsEndpoints
{
    public function jobs(): JobResource
    {
        return new JobResource($this);
    }
}
```

**Pattern Notes**:
- Simple trait with a single method
- Method name is plural, lowercase, matches the resource name
- Returns new instance of the resource class
- Passes `$this` (the connector) to the resource constructor

### Step 6: Add Trait to AbstractSimproConnector

**File**: `src/Connectors/AbstractSimproConnector.php`

```php
use Simpro\PhpSdk\Simpro\Concerns\SupportsJobsEndpoints;

abstract class AbstractSimproConnector extends \Saloon\Http\Connector implements HasPagination
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;
    use HasTimeout;
    use SupportsJobsEndpoints;  // Add this line

    // ... rest of class
}
```

### Step 7: Create Tests

**File**: `tests/Requests/Jobs/ListJobsRequestTest.php`

```php
<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Requests\Jobs\ListJobsRequest;

it('sends list jobs request to correct endpoint', function () {
    MockClient::global([
        ListJobsRequest::class => MockResponse::fixture('list_jobs'),
    ]);

    $request = new ListJobsRequest();
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses job response correctly', function () {
    MockClient::global([
        ListJobsRequest::class => MockResponse::fixture('list_jobs'),
    ]);

    $request = new ListJobsRequest();
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(\Simpro\PhpSdk\Simpro\Data\Jobs\JobListResponse::class)
        ->and($dto->jobs)->toBeArray()
        ->and($dto->jobs[0])->toBeInstanceOf(\Simpro\PhpSdk\Simpro\Data\Jobs\Job::class);
});
```

**File**: `tests/Fixtures/Saloon/list_jobs.json`

```json
{
  "statusCode": 200,
  "headers": {
    "Result-Total": "100",
    "Result-Pages": "10"
  },
  "data": {
    "jobs": [
      {
        "id": "123",
        "type": "Job",
        "attributes": {
          "name": "Test Job",
          "status": "Pending",
          "created": "2024-01-01T00:00:00Z"
        },
        "relationships": {}
      }
    ]
  }
}
```

**Pattern Notes**:
- Test fixtures are JSON files named after the request class (snake_case)
- Fixtures include `statusCode`, `headers`, `data`, and optional `context`
- Headers should include `Result-Total` and `Result-Pages` for pagination
- The `data` structure should match the expected API response
- Test that the request hits the correct endpoint
- Test that DTOs are parsed correctly
- Test pagination if applicable

### Step 8: Update Tests to Use Resource

**File**: `tests/Resources/JobResourceTest.php`

```php
<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Requests\Jobs\ListJobsRequest;

it('lists jobs via resource method', function () {
    MockClient::global([
        ListJobsRequest::class => MockResponse::fixture('list_jobs'),
    ]);

    $paginator = $this->sdk->jobs()->list();

    expect($paginator)->toBeInstanceOf(\Simpro\PhpSdk\Simpro\Paginators\SimproPaginator::class);
});

it('iterates over paginated jobs', function () {
    MockClient::global([
        ListJobsRequest::class => MockResponse::fixture('list_jobs'),
    ]);

    $jobs = $this->sdk->jobs()->list();
    $firstJob = $jobs->items()->current();

    expect($firstJob)->toBeInstanceOf(\Simpro\PhpSdk\Simpro\Data\Jobs\Job::class)
        ->and($firstJob->id)->toBe('123')
        ->and($firstJob->attributes->name)->toBe('Test Job');
});

it('filters jobs using convenience methods', function () {
    MockClient::global([
        ListJobsRequest::class => MockResponse::fixture('list_jobs'),
    ]);

    $paginator = $this->sdk->jobs()->listActive();

    expect($paginator)->toBeInstanceOf(\Simpro\PhpSdk\Simpro\Paginators\SimproPaginator::class);
});
```

### Complete File Structure

After following all steps, you should have:

```
src/
├── Concerns/
│   └── SupportsJobsEndpoints.php
├── Connectors/
│   └── AbstractSimproConnector.php (updated)
├── Data/
│   └── Jobs/
│       ├── Job.php
│       ├── JobAttributes.php
│       ├── JobListResponse.php
│       └── JobStatus.php (if needed)
├── Requests/
│   └── Jobs/
│       └── ListJobsRequest.php
└── Resources/
    └── JobResource.php

tests/
├── Fixtures/
│   └── Saloon/
│       └── list_jobs.json
├── Requests/
│   └── Jobs/
│       └── ListJobsRequestTest.php
└── Resources/
    └── JobResourceTest.php
```

### Usage After Implementation

```php
use Simpro\PhpSdk\Simpro\Connectors\SimproApiKeyConnector;

$connector = new SimproApiKeyConnector(
    baseUrl: 'https://example.simprosuite.com/api/v1.0',
    apiKey: 'your-api-key'
);

// List all jobs
$jobs = $connector->jobs()->list();

// Filter jobs
$activeJobs = $connector->jobs()->listActive();
$customerJobs = $connector->jobs()->whereCustomerId(123);
$recentJobs = $connector->jobs()->createdAfter('2024-01-01T00:00:00Z');

// Iterate over results and collect data
$jobData = [];
foreach ($jobs->items() as $job) {
    $jobData[] = [
        'id' => $job->id,
        'name' => $job->attributes->name,
    ];
}

// Or use Laravel collections
$jobNames = $jobs->collect()
    ->map(fn($job) => $job->attributes->name)
    ->toArray();
```

## Important Notes

- **No Simpro Instance**: This SDK does not have a `Simpro` main class anymore. Use `SimproOAuthConnector` or `SimproApiKeyConnector` directly.
- **Saloon v3**: The SDK is built on Saloon v3. Refer to [Saloon documentation](https://docs.saloon.dev/) for advanced features.
- **PHP 8.2+**: Minimum PHP version is 8.2 (uses readonly properties, typed properties).
- **Active Development**: API may change. Check `IMPLEMENTATION_SUMMARY.md` for recent architecture changes.
- **Pagination Headers**: Simpro API returns pagination data in custom headers (`Result-Total`, `Result-Pages`), not in response body.
