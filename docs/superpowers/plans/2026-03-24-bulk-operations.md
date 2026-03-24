# Bulk Operations Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add `bulkCreate()`, `bulkUpdate()`, and `bulkDelete()` methods to all SDK resources that have corresponding single-item CRUD operations.

**Architecture:** Three generic reusable request classes (`BulkCreateRequest`, `BulkUpdateRequest`, `BulkDeleteRequest`) handle the uniform Simpro `/multiple/` and `/delete/` API patterns. Two new DTOs (`BulkResponse`, `BulkResponseItem`) model the per-item response format. Each resource gets thin wrapper methods that construct the generic request with its base endpoint path.

**Tech Stack:** PHP 8.2+, Saloon v3, Pest testing framework

**Spec:** `docs/superpowers/specs/2026-03-24-bulk-operations-design.md`

---

### Task 1: BulkResponseItem DTO

**Files:**
- Create: `src/Data/Bulk/BulkResponseItem.php`
- Create: `tests/Data/Bulk/BulkResponseItemTest.php`

- [ ] **Step 1: Write the failing test**

Create `tests/Data/Bulk/BulkResponseItemTest.php`:

```php
<?php

declare(strict_types=1);

use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponseItem;

it('creates from array with all fields', function () {
    $item = BulkResponseItem::fromArray([
        'status' => 201,
        'headers' => [
            'Batch-ID' => 0,
            'Resource-ID' => 1882,
            'Location' => '/api/v1.0/companies/0/customers/individuals/1882',
        ],
        'body' => null,
    ]);

    expect($item)->toBeInstanceOf(BulkResponseItem::class)
        ->and($item->status)->toBe(201)
        ->and($item->batchId)->toBe(0)
        ->and($item->resourceId)->toBe(1882)
        ->and($item->location)->toBe('/api/v1.0/companies/0/customers/individuals/1882')
        ->and($item->body)->toBeNull();
});

it('creates from array with string resource id', function () {
    $item = BulkResponseItem::fromArray([
        'status' => 201,
        'headers' => [
            'Batch-ID' => 0,
            'Resource-ID' => 'pEwTUfJ9jXxtD6b1BZU2IZDwHaGoJX4ZuOBmw_DCKy8',
            'Location' => '/api/v1.0/companies/0/jobs/123/attachment/files/pEwTUfJ9jXxtD6b1BZU2IZDwHaGoJX4ZuOBmw_DCKy8',
        ],
        'body' => null,
    ]);

    expect($item->resourceId)->toBe('pEwTUfJ9jXxtD6b1BZU2IZDwHaGoJX4ZuOBmw_DCKy8');
});

it('creates from array without location header', function () {
    $item = BulkResponseItem::fromArray([
        'status' => 204,
        'headers' => [
            'Batch-ID' => 1,
            'Resource-ID' => 1885,
        ],
        'body' => null,
    ]);

    expect($item->location)->toBeNull();
});

it('identifies successful responses', function () {
    $item = BulkResponseItem::fromArray([
        'status' => 201,
        'headers' => ['Batch-ID' => 0, 'Resource-ID' => 1],
        'body' => null,
    ]);

    expect($item->isSuccessful())->toBeTrue();
});

it('identifies failed responses', function () {
    $item = BulkResponseItem::fromArray([
        'status' => 422,
        'headers' => ['Batch-ID' => 0, 'Resource-ID' => 0],
        'body' => ['Message' => 'Validation failed'],
    ]);

    expect($item->isSuccessful())->toBeFalse()
        ->and($item->body)->toBe(['Message' => 'Validation failed']);
});

it('handles 204 as successful', function () {
    $item = BulkResponseItem::fromArray([
        'status' => 204,
        'headers' => ['Batch-ID' => 0, 'Resource-ID' => 1884],
        'body' => null,
    ]);

    expect($item->isSuccessful())->toBeTrue();
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `./vendor/bin/pest tests/Data/Bulk/BulkResponseItemTest.php`
Expected: FAIL — class `BulkResponseItem` not found

- [ ] **Step 3: Write minimal implementation**

Create `src/Data/Bulk/BulkResponseItem.php`:

```php
<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Bulk;

final readonly class BulkResponseItem
{
    public function __construct(
        public int $status,
        public int $batchId,
        public int|string $resourceId,
        public ?string $location,
        public mixed $body,
    ) {}

    /**
     * @param  array{status: int, headers: array<string, mixed>, body: mixed}  $data
     */
    public static function fromArray(array $data): self
    {
        $headers = $data['headers'];

        return new self(
            status: $data['status'],
            batchId: (int) $headers['Batch-ID'],
            resourceId: $headers['Resource-ID'],
            location: $headers['Location'] ?? null,
            body: $data['body'],
        );
    }

    public function isSuccessful(): bool
    {
        return $this->status >= 200 && $this->status < 300;
    }
}
```

- [ ] **Step 4: Run test to verify it passes**

Run: `./vendor/bin/pest tests/Data/Bulk/BulkResponseItemTest.php`
Expected: All 6 tests PASS

- [ ] **Step 5: Commit**

```bash
git add src/Data/Bulk/BulkResponseItem.php tests/Data/Bulk/BulkResponseItemTest.php
git commit -m "feat: add BulkResponseItem DTO for bulk operation responses"
```

---

### Task 2: BulkResponse DTO

**Files:**
- Create: `src/Data/Bulk/BulkResponse.php`
- Create: `tests/Data/Bulk/BulkResponseTest.php`

- [ ] **Step 1: Write the failing test**

Create `tests/Data/Bulk/BulkResponseTest.php`:

```php
<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponseItem;

it('creates from response', function () {
    $mockResponse = MockResponse::make([
        [
            'status' => 201,
            'headers' => ['Batch-ID' => 0, 'Resource-ID' => 1882, 'Location' => '/api/v1.0/companies/0/contractors/1882'],
            'body' => null,
        ],
        [
            'status' => 201,
            'headers' => ['Batch-ID' => 1, 'Resource-ID' => 1883, 'Location' => '/api/v1.0/companies/0/contractors/1883'],
            'body' => null,
        ],
    ]);

    $response = BulkResponse::fromResponse($mockResponse->toSaloonResponse());

    expect($response->items)->toHaveCount(2)
        ->and($response->items[0])->toBeInstanceOf(BulkResponseItem::class)
        ->and($response->items[0]->resourceId)->toBe(1882)
        ->and($response->items[1]->resourceId)->toBe(1883);
});

it('returns all resource ids', function () {
    $response = new BulkResponse([
        BulkResponseItem::fromArray(['status' => 201, 'headers' => ['Batch-ID' => 0, 'Resource-ID' => 100], 'body' => null]),
        BulkResponseItem::fromArray(['status' => 201, 'headers' => ['Batch-ID' => 1, 'Resource-ID' => 101], 'body' => null]),
        BulkResponseItem::fromArray(['status' => 201, 'headers' => ['Batch-ID' => 2, 'Resource-ID' => 102], 'body' => null]),
    ]);

    expect($response->resourceIds())->toBe([100, 101, 102]);
});

it('reports all successful when all items succeed', function () {
    $response = new BulkResponse([
        BulkResponseItem::fromArray(['status' => 201, 'headers' => ['Batch-ID' => 0, 'Resource-ID' => 100], 'body' => null]),
        BulkResponseItem::fromArray(['status' => 204, 'headers' => ['Batch-ID' => 1, 'Resource-ID' => 101], 'body' => null]),
    ]);

    expect($response->allSuccessful())->toBeTrue();
});

it('reports not all successful when any item fails', function () {
    $response = new BulkResponse([
        BulkResponseItem::fromArray(['status' => 201, 'headers' => ['Batch-ID' => 0, 'Resource-ID' => 100], 'body' => null]),
        BulkResponseItem::fromArray(['status' => 422, 'headers' => ['Batch-ID' => 1, 'Resource-ID' => 0], 'body' => ['Message' => 'Error']]),
    ]);

    expect($response->allSuccessful())->toBeFalse();
});

it('returns only failed items', function () {
    $response = new BulkResponse([
        BulkResponseItem::fromArray(['status' => 201, 'headers' => ['Batch-ID' => 0, 'Resource-ID' => 100], 'body' => null]),
        BulkResponseItem::fromArray(['status' => 422, 'headers' => ['Batch-ID' => 1, 'Resource-ID' => 0], 'body' => ['Message' => 'Error']]),
        BulkResponseItem::fromArray(['status' => 201, 'headers' => ['Batch-ID' => 2, 'Resource-ID' => 102], 'body' => null]),
    ]);

    $failures = $response->failures();

    expect($failures)->toHaveCount(1)
        ->and($failures[0]->batchId)->toBe(1)
        ->and($failures[0]->status)->toBe(422);
});

it('returns empty failures when all succeed', function () {
    $response = new BulkResponse([
        BulkResponseItem::fromArray(['status' => 201, 'headers' => ['Batch-ID' => 0, 'Resource-ID' => 100], 'body' => null]),
    ]);

    expect($response->failures())->toBeEmpty();
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `./vendor/bin/pest tests/Data/Bulk/BulkResponseTest.php`
Expected: FAIL — class `BulkResponse` not found

- [ ] **Step 3: Write minimal implementation**

Create `src/Data/Bulk/BulkResponse.php`:

```php
<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Bulk;

use Saloon\Http\Response;

final readonly class BulkResponse
{
    /**
     * @param  array<int, BulkResponseItem>  $items
     */
    public function __construct(
        public array $items,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return new self(
            items: array_map(
                fn (array $item) => BulkResponseItem::fromArray($item),
                $data,
            ),
        );
    }

    /**
     * @return array<int, int|string>
     */
    public function resourceIds(): array
    {
        return array_map(fn (BulkResponseItem $item) => $item->resourceId, $this->items);
    }

    public function allSuccessful(): bool
    {
        return $this->failures() === [];
    }

    /**
     * @return array<int, BulkResponseItem>
     */
    public function failures(): array
    {
        return array_values(
            array_filter(
                $this->items,
                fn (BulkResponseItem $item) => ! $item->isSuccessful(),
            ),
        );
    }
}
```

- [ ] **Step 4: Run test to verify it passes**

Run: `./vendor/bin/pest tests/Data/Bulk/BulkResponseTest.php`
Expected: All 6 tests PASS

- [ ] **Step 5: Commit**

```bash
git add src/Data/Bulk/BulkResponse.php tests/Data/Bulk/BulkResponseTest.php
git commit -m "feat: add BulkResponse DTO wrapping bulk operation item results"
```

---

### Task 3: BulkCreateRequest

**Files:**
- Create: `src/Requests/Bulk/BulkCreateRequest.php`
- Create: `tests/Requests/Bulk/BulkCreateRequestTest.php`
- Create: `tests/Fixtures/Saloon/bulk_create_request.json`

- [ ] **Step 1: Create the test fixture**

Create `tests/Fixtures/Saloon/bulk_create_request.json`:

```json
{
  "statusCode": 200,
  "headers": {
    "Content-Type": "application/json"
  },
  "data": [
    {
      "status": 201,
      "headers": {
        "Batch-ID": 0,
        "Resource-ID": 1882,
        "Location": "/api/v1.0/companies/0/contractors/1882"
      },
      "body": null
    },
    {
      "status": 201,
      "headers": {
        "Batch-ID": 1,
        "Resource-ID": 1883,
        "Location": "/api/v1.0/companies/0/contractors/1883"
      },
      "body": null
    }
  ]
}
```

- [ ] **Step 2: Write the failing test**

Create `tests/Requests/Bulk/BulkCreateRequestTest.php`:

```php
<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;

it('resolves endpoint with /multiple/ suffix', function () {
    $request = new BulkCreateRequest('/api/v1.0/companies/0/contractors', []);

    expect($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/contractors/multiple/');
});

it('resolves endpoint with trailing slash', function () {
    $request = new BulkCreateRequest('/api/v1.0/companies/0/contractors/', []);

    expect($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/contractors/multiple/');
});

it('sends bulk create request and returns BulkResponse', function () {
    MockClient::global([
        BulkCreateRequest::class => MockResponse::fixture('bulk_create_request'),
    ]);

    $request = new BulkCreateRequest('/api/v1.0/companies/0/contractors', [
        ['GivenName' => 'Peter', 'FamilyName' => 'Smith'],
        ['GivenName' => 'Michael', 'FamilyName' => 'Dickson'],
    ]);

    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(BulkResponse::class)
        ->and($dto->items)->toHaveCount(2)
        ->and($dto->resourceIds())->toBe([1882, 1883])
        ->and($dto->allSuccessful())->toBeTrue();
});

it('uses POST method', function () {
    $request = new BulkCreateRequest('/api/v1.0/companies/0/contractors', []);

    expect($request->getMethod()->value)->toBe('POST');
});
```

- [ ] **Step 3: Run test to verify it fails**

Run: `./vendor/bin/pest tests/Requests/Bulk/BulkCreateRequestTest.php`
Expected: FAIL — class `BulkCreateRequest` not found

- [ ] **Step 4: Write minimal implementation**

Create `src/Requests/Bulk/BulkCreateRequest.php`:

```php
<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Bulk;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;

final class BulkCreateRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<int, array<string, mixed>>  $data
     */
    public function __construct(
        private readonly string $endpoint,
        private readonly array $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return rtrim($this->endpoint, '/').'/multiple/';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }

    public function createDtoFromResponse(Response $response): BulkResponse
    {
        return BulkResponse::fromResponse($response);
    }
}
```

- [ ] **Step 5: Run test to verify it passes**

Run: `./vendor/bin/pest tests/Requests/Bulk/BulkCreateRequestTest.php`
Expected: All 4 tests PASS

- [ ] **Step 6: Commit**

```bash
git add src/Requests/Bulk/BulkCreateRequest.php tests/Requests/Bulk/BulkCreateRequestTest.php tests/Fixtures/Saloon/bulk_create_request.json
git commit -m "feat: add BulkCreateRequest for POST /multiple/ endpoints"
```

---

### Task 4: BulkUpdateRequest

**Files:**
- Create: `src/Requests/Bulk/BulkUpdateRequest.php`
- Create: `tests/Requests/Bulk/BulkUpdateRequestTest.php`
- Create: `tests/Fixtures/Saloon/bulk_update_request.json`

- [ ] **Step 1: Create the test fixture**

Create `tests/Fixtures/Saloon/bulk_update_request.json`:

```json
{
  "statusCode": 200,
  "headers": {
    "Content-Type": "application/json"
  },
  "data": [
    {
      "status": 204,
      "headers": {
        "Batch-ID": 0,
        "Resource-ID": 1884
      },
      "body": null
    },
    {
      "status": 204,
      "headers": {
        "Batch-ID": 1,
        "Resource-ID": 1885
      },
      "body": null
    }
  ]
}
```

- [ ] **Step 2: Write the failing test**

Create `tests/Requests/Bulk/BulkUpdateRequestTest.php`:

```php
<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;

it('resolves endpoint with /multiple/ suffix', function () {
    $request = new BulkUpdateRequest('/api/v1.0/companies/0/contractors', []);

    expect($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/contractors/multiple/');
});

it('resolves endpoint with trailing slash', function () {
    $request = new BulkUpdateRequest('/api/v1.0/companies/0/contractors/', []);

    expect($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/contractors/multiple/');
});

it('sends bulk update request and returns BulkResponse', function () {
    MockClient::global([
        BulkUpdateRequest::class => MockResponse::fixture('bulk_update_request'),
    ]);

    $request = new BulkUpdateRequest('/api/v1.0/companies/0/contractors', [
        ['ID' => 1884, 'GivenName' => 'Pete'],
        ['ID' => 1885, 'GivenName' => 'Mike'],
    ]);

    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(BulkResponse::class)
        ->and($dto->items)->toHaveCount(2)
        ->and($dto->resourceIds())->toBe([1884, 1885])
        ->and($dto->allSuccessful())->toBeTrue();
});

it('uses PATCH method', function () {
    $request = new BulkUpdateRequest('/api/v1.0/companies/0/contractors', []);

    expect($request->getMethod()->value)->toBe('PATCH');
});
```

- [ ] **Step 3: Run test to verify it fails**

Run: `./vendor/bin/pest tests/Requests/Bulk/BulkUpdateRequestTest.php`
Expected: FAIL — class `BulkUpdateRequest` not found

- [ ] **Step 4: Write minimal implementation**

Create `src/Requests/Bulk/BulkUpdateRequest.php`:

```php
<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Bulk;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;

final class BulkUpdateRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    /**
     * @param  array<int, array<string, mixed>>  $data
     */
    public function __construct(
        private readonly string $endpoint,
        private readonly array $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return rtrim($this->endpoint, '/').'/multiple/';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }

    public function createDtoFromResponse(Response $response): BulkResponse
    {
        return BulkResponse::fromResponse($response);
    }
}
```

- [ ] **Step 5: Run test to verify it passes**

Run: `./vendor/bin/pest tests/Requests/Bulk/BulkUpdateRequestTest.php`
Expected: All 4 tests PASS

- [ ] **Step 6: Commit**

```bash
git add src/Requests/Bulk/BulkUpdateRequest.php tests/Requests/Bulk/BulkUpdateRequestTest.php tests/Fixtures/Saloon/bulk_update_request.json
git commit -m "feat: add BulkUpdateRequest for PATCH /multiple/ endpoints"
```

---

### Task 5: BulkDeleteRequest

**Files:**
- Create: `src/Requests/Bulk/BulkDeleteRequest.php`
- Create: `tests/Requests/Bulk/BulkDeleteRequestTest.php`
- Create: `tests/Fixtures/Saloon/bulk_delete_request.json`

- [ ] **Step 1: Create the test fixture**

Create `tests/Fixtures/Saloon/bulk_delete_request.json`:

```json
{
  "statusCode": 200,
  "headers": {
    "Content-Type": "application/json"
  },
  "data": [
    "2 section(s) deleted.",
    "1 section(s) not found in project."
  ]
}
```

- [ ] **Step 2: Write the failing test**

Create `tests/Requests/Bulk/BulkDeleteRequestTest.php`:

```php
<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;

it('resolves endpoint with /delete/ suffix', function () {
    $request = new BulkDeleteRequest('/api/v1.0/companies/0/jobs', []);

    expect($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/jobs/delete/');
});

it('resolves endpoint with trailing slash', function () {
    $request = new BulkDeleteRequest('/api/v1.0/companies/0/jobs/', []);

    expect($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/jobs/delete/');
});

it('sends bulk delete request and returns array of strings', function () {
    MockClient::global([
        BulkDeleteRequest::class => MockResponse::fixture('bulk_delete_request'),
    ]);

    $request = new BulkDeleteRequest('/api/v1.0/companies/0/jobs', [210787, 210788, 210789]);

    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBe('2 section(s) deleted.')
        ->and($dto[1])->toBe('1 section(s) not found in project.');
});

it('wraps ids in IDs key in request body', function () {
    $request = new BulkDeleteRequest('/api/v1.0/companies/0/jobs', [210787, 210788]);

    $body = $request->body()->all();

    expect($body)->toBe(['IDs' => [210787, 210788]]);
});

it('uses POST method', function () {
    $request = new BulkDeleteRequest('/api/v1.0/companies/0/jobs', []);

    expect($request->getMethod()->value)->toBe('POST');
});

it('accepts string ids for attachments', function () {
    $request = new BulkDeleteRequest('/api/v1.0/companies/0/jobs/123/attachment/files', [
        'pEwTUfJ9jXxtD6b1BZU2IZDwHaGoJX4ZuOBmw_DCKy8',
        'MEPQuk-TKvn-IB9UWKI07tEGIH7lC2r91f7a5jSi5Ik',
    ]);

    $body = $request->body()->all();

    expect($body['IDs'])->toHaveCount(2)
        ->and($body['IDs'][0])->toBe('pEwTUfJ9jXxtD6b1BZU2IZDwHaGoJX4ZuOBmw_DCKy8');
});
```

- [ ] **Step 3: Run test to verify it fails**

Run: `./vendor/bin/pest tests/Requests/Bulk/BulkDeleteRequestTest.php`
Expected: FAIL — class `BulkDeleteRequest` not found

- [ ] **Step 4: Write minimal implementation**

Create `src/Requests/Bulk/BulkDeleteRequest.php`:

```php
<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Bulk;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class BulkDeleteRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<int, int|string>  $ids
     */
    public function __construct(
        private readonly string $endpoint,
        private readonly array $ids,
    ) {}

    public function resolveEndpoint(): string
    {
        return rtrim($this->endpoint, '/').'/delete/';
    }

    /**
     * @return array{IDs: array<int, int|string>}
     */
    protected function defaultBody(): array
    {
        return ['IDs' => $this->ids];
    }

    /**
     * @return array<int, string>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return $response->json();
    }
}
```

- [ ] **Step 5: Run test to verify it passes**

Run: `./vendor/bin/pest tests/Requests/Bulk/BulkDeleteRequestTest.php`
Expected: All 6 tests PASS

- [ ] **Step 6: Commit**

```bash
git add src/Requests/Bulk/BulkDeleteRequest.php tests/Requests/Bulk/BulkDeleteRequestTest.php tests/Fixtures/Saloon/bulk_delete_request.json
git commit -m "feat: add BulkDeleteRequest for POST /delete/ endpoints"
```

---

### Task 6: Resource integration — ContractorResource (representative test)

**Files:**
- Modify: `src/Resources/ContractorResource.php`
- Create: `tests/Requests/Contractors/BulkContractorRequestTest.php`

This task adds bulk methods to ContractorResource as the representative example and tests the full integration. The same pattern will be applied to all other resources in Task 7.

- [ ] **Step 1: Write the failing test**

Create `tests/Requests/Contractors/BulkContractorRequestTest.php`:

```php
<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;

it('bulk creates contractors', function () {
    MockClient::global([
        BulkCreateRequest::class => MockResponse::fixture('bulk_create_request'),
    ]);

    $response = $this->sdk->contractors(companyId: 0)->bulkCreate([
        ['GivenName' => 'Peter', 'FamilyName' => 'Smith'],
        ['GivenName' => 'Michael', 'FamilyName' => 'Dickson'],
    ]);

    expect($response)->toBeInstanceOf(BulkResponse::class)
        ->and($response->items)->toHaveCount(2)
        ->and($response->resourceIds())->toBe([1882, 1883]);
});

it('bulk updates contractors', function () {
    MockClient::global([
        BulkUpdateRequest::class => MockResponse::fixture('bulk_update_request'),
    ]);

    $response = $this->sdk->contractors(companyId: 0)->bulkUpdate([
        ['ID' => 1884, 'GivenName' => 'Pete'],
        ['ID' => 1885, 'GivenName' => 'Mike'],
    ]);

    expect($response)->toBeInstanceOf(BulkResponse::class)
        ->and($response->items)->toHaveCount(2)
        ->and($response->allSuccessful())->toBeTrue();
});

it('bulk deletes contractors', function () {
    MockClient::global([
        BulkDeleteRequest::class => MockResponse::fixture('bulk_delete_request'),
    ]);

    $messages = $this->sdk->contractors(companyId: 0)->bulkDelete([210787, 210788, 210789]);

    expect($messages)->toBeArray()
        ->and($messages[0])->toBe('2 section(s) deleted.');
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `./vendor/bin/pest tests/Requests/Contractors/BulkContractorRequestTest.php`
Expected: FAIL — method `bulkCreate` does not exist

- [ ] **Step 3: Add bulk methods to ContractorResource**

Add the following methods to `src/Resources/ContractorResource.php` after the existing `delete()` method (before the `contractor()` scope method):

```php
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;

/**
 * Create multiple contractors in a single request.
 *
 * @param  array<int, array<string, mixed>>  $data
 */
public function bulkCreate(array $data): BulkResponse
{
    $request = new BulkCreateRequest(
        "/api/v1.0/companies/{$this->companyId}/contractors",
        $data,
    );

    return $this->connector->send($request)->dto();
}

/**
 * Update multiple contractors in a single request.
 *
 * Each item in the data array must include an 'ID' key.
 *
 * @param  array<int, array<string, mixed>>  $data
 */
public function bulkUpdate(array $data): BulkResponse
{
    $request = new BulkUpdateRequest(
        "/api/v1.0/companies/{$this->companyId}/contractors",
        $data,
    );

    return $this->connector->send($request)->dto();
}

/**
 * Delete multiple contractors in a single request.
 *
 * @param  array<int, int|string>  $ids
 * @return array<int, string>
 */
public function bulkDelete(array $ids): array
{
    $request = new BulkDeleteRequest(
        "/api/v1.0/companies/{$this->companyId}/contractors",
        $ids,
    );

    return $this->connector->send($request)->dto();
}
```

- [ ] **Step 4: Run test to verify it passes**

Run: `./vendor/bin/pest tests/Requests/Contractors/BulkContractorRequestTest.php`
Expected: All 3 tests PASS

- [ ] **Step 5: Run full test suite**

Run: `composer test`
Expected: All tests PASS

- [ ] **Step 6: Commit**

```bash
git add src/Resources/ContractorResource.php tests/Requests/Contractors/BulkContractorRequestTest.php
git commit -m "feat: add bulk operations to ContractorResource"
```

---

### Task 7: Add bulk methods to all remaining resources

**Files to modify:** All resource files that have `create()`, `update()`, or `delete()` methods, except the excluded resources (locks, CurrencyResource, entity-level custom field value resources).

This is a mechanical task — apply the same pattern from Task 6 to each resource. The endpoint path comes from the resource's existing create/update/delete request classes.

**Rules:**
- If a resource has `create()` → add `bulkCreate()`
- If a resource has `update()` → add `bulkUpdate()`
- If a resource has `delete()` → add `bulkDelete()`
- Copy the endpoint path from the resource's existing single-item request, removing the trailing `/{id}` part
- Add the three `use` imports for `BulkResponse`, `BulkCreateRequest`, `BulkUpdateRequest`, `BulkDeleteRequest` as needed

**Do NOT add bulk methods to:**
- `Jobs/JobLockResource.php` — lock toggle, not CRUD
- `Quotes/QuoteLockResource.php` — lock toggle, not CRUD
- `Jobs/CostCenters/CostCenterLockResource.php` — lock toggle, not CRUD
- `Setup/CurrencyResource.php` — system-managed reference data
- `Customers/CustomerCustomFieldResource.php` — entity-level field values, update only
- `Contractors/ContractorCustomFieldResource.php` — entity-level field values, update only
- `Customers/ContactCustomFieldResource.php` — entity-level field values, update only
- `Customers/ContractCustomFieldResource.php` — entity-level field values, update only
- `Jobs/JobCustomFieldResource.php` — entity-level field values, update only
- `Invoices/InvoiceCustomFieldResource.php` — entity-level field values, update only
- `Invoices/CreditNotes/InvoiceCreditNoteCustomFieldResource.php` — entity-level field values, update only
- `Jobs/CostCenters/WorkOrders/WorkOrderCustomFieldResource.php` — entity-level field values, update only
- `Jobs/CostCenters/ContractorJobs/ContractorJobCustomFieldResource.php` — entity-level field values, update only
- `Employees/EmployeeCustomFieldResource.php` — entity-level field values, update only
- `Customers/ServiceLevelAssetTypeResource.php` — entity-level update only

For deeply nested resources, the endpoint path includes all parent IDs from the resource constructor. For example, `Jobs/CostCenters/LaborResource` has `$companyId`, `$jobId`, `$sectionId`, `$costCenterId` — its endpoint becomes `/api/v1.0/companies/{$this->companyId}/jobs/{$this->jobId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/labor`.

- [ ] **Step 1: Add bulk methods to all Employee resources**

Resources: `EmployeeResource`, `EmployeeLicenceResource`, `EmployeeAttachmentFileResource`, `EmployeeAttachmentFolderResource`, `LicenceAttachmentFileResource` (employees)

- [ ] **Step 2: Add bulk methods to all Contractor resources**

Resources: `ContractorLicenceResource`, `ContractorAttachmentFileResource`, `ContractorAttachmentFolderResource`, `LicenceAttachmentFileResource` (contractors)

- [ ] **Step 3: Add bulk methods to all Customer resources**

Resources: `CustomerIndividualResource`, `ContactResource`, `ContractResource`, `CustomerNoteResource`, `CustomerLaborRateResource`, `ContractLaborRateResource`, `ContractInflationResource`

- [ ] **Step 4: Add bulk methods to all Job resources**

Resources: `JobResource`, `JobSectionResource`, `JobNoteResource`, `CostCenterResource` (jobs), `AttachmentFileResource` (jobs), `AttachmentFolderResource` (jobs), `LaborResource`, `CatalogResource`, `AssetResource`, `OneOffResource`, `PrebuildResource`, `ServiceFeeResource`, `StockResource` (create+update only), `ContractorJobResource`, `CostCenterScheduleResource`, `WorkOrderResource` (create+update), `ContractorJobAttachmentFileResource`, `ContractorJobAttachmentFolderResource`, `WorkOrderAttachmentFileResource`, `TestResultAttachmentFileResource`, `WorkOrderAssetResource` (delete only)

- [ ] **Step 5: Add bulk methods to all Quote resources**

Resources: `QuoteResource`, `QuoteSectionResource`, `QuoteCostCenterResource`, `QuoteNoteResource`, `QuoteAttachmentFolderResource` (create only), `QuoteAttachmentFileResource` (create only), `QuoteCostCenterLaborResource`, `QuoteCostCenterCatalogResource`, `QuoteCostCenterAssetResource`, `QuoteCostCenterOneOffResource`, `QuoteCostCenterPrebuildResource`, `QuoteCostCenterServiceFeeResource`, `QuoteCostCenterScheduleResource`, `QuoteCostCenterContractorJobResource`, `QuoteCostCenterWorkOrderResource`

- [ ] **Step 6: Add bulk methods to all Invoice resources**

Resources: `InvoiceResource`, `InvoiceNoteResource`, `InvoiceCreditNoteResource` (create+update only)

- [ ] **Step 7: Add bulk methods to all Setup resources**

Resources: `AccountingCategoryResource`, `ActivityResource`, `AssetServiceLevelResource`, `BasicCommissionResource`, `AdvancedCommissionResource`, `BusinessGroupResource`, `ChartOfAccountResource`, `CostCenterResource` (setup), `CustomerGroupResource`, `CustomerProfileResource`, `CustomerTagResource`, `CustomerInvoiceStatusCodeResource`, `PaymentMethodResource`, `PaymentTermResource`, `ProjectStatusCodeResource`, `ProjectTagResource`, `QuoteArchiveReasonResource`, `ResponseTimeResource`, `TaskCategoryResource`, `VendorOrderStatusCodeResource`, `WebhookResource`, `ZoneResource`, `LaborRateResource`, `ScheduleRateResource`, `FitTimeResource`, `PricingTierResource`, `PurchasingStageResource`, `StockTakeReasonResource`, `StockTransferReasonResource`, `UomResource`, `AssetTypeResource`, `AssetTypeCustomFieldResource`, `AssetTypeFailurePointResource`, `AssetTypeFileResource`, `AssetTypeFolderResource`, `AssetTypeRecommendationResource`, `AssetTypeServiceLevelResource`, `AssetTypeTestReadingResource`

**`AbstractCustomFieldResource` (Setup custom field definitions):** Add bulk methods to the abstract class itself (not each subclass). Add an `abstract protected function getResourcePath(): string` method — each subclass already implements this on their request classes. The existing request classes use `getResourcePath()` to build the endpoint (`/api/v1.0/companies/{id}/setup/customFields/{resourcePath}/`). For bulk operations, add a concrete `protected function bulkEndpoint(): string` method to `AbstractCustomFieldResource` that delegates to a new `abstract protected function getCustomFieldResourcePath(): string` method. Each subclass implements it returning the same value as their request's `getResourcePath()` (e.g., `'catalogs'`, `'projects'`, etc.). The bulk methods then use: `"/api/v1.0/companies/{$this->companyId}/setup/customFields/{$this->getCustomFieldResourcePath()}"` as the endpoint.

- [ ] **Step 8: Add bulk methods to ActivityScheduleResource**

Resources: `ActivityScheduleResource`

- [ ] **Step 9: Add bulk methods to Invoices/CreditNotes note resource**

Resources: `InvoiceCreditNoteNoteResource`

- [ ] **Step 10: Run full test suite and static analysis**

Run: `composer review`
Expected: All formatting, analysis, and tests PASS

- [ ] **Step 11: Commit**

```bash
git add src/Resources/
git commit -m "feat: add bulk operations to all eligible resources"
```

---

### Task 8: Final verification

- [ ] **Step 1: Run full test suite**

Run: `composer review`
Expected: All checks PASS (format + analyse + test)

- [ ] **Step 2: Verify no excluded resources got bulk methods**

Manually check that lock resources, CurrencyResource, and entity-level custom field resources do NOT have bulk methods.

- [ ] **Step 3: Final commit if any fixes needed**

Only commit if Task 8 step 1 or 2 revealed issues that needed fixing.
