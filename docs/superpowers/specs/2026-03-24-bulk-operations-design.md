# Bulk Operations Design

## Problem

The Simpro API supports bulk POST, PATCH, and DELETE operations via `/multiple/` and `/delete/` URL suffixes. The SDK currently has no clean way to leverage these endpoints — consumers must construct raw Saloon requests manually.

## Design Decisions

- **Method names:** `bulkCreate()`, `bulkUpdate()`, `bulkDelete()` — consistent with existing `bulkReplace()` methods
- **Approach:** Generic reusable request classes (not per-resource classes) — the bulk API pattern is completely uniform across all endpoints
- **Return types:** Typed `BulkResponse` DTO for POST/PATCH, `array<string>` for DELETE
- **Scope:** All resources that currently have `create()`/`update()`/`delete()` get corresponding bulk methods

## New Files

### DTOs

#### `src/Data/Bulk/BulkResponseItem.php`

Represents one item from a bulk POST/PATCH response.

```php
final readonly class BulkResponseItem
{
    public function __construct(
        public int $status,
        public int $batchId,
        public int|string $resourceId,
        public ?string $location,
        public mixed $body,
    ) {}

    public static function fromArray(array $data): self;
    public function isSuccessful(): bool; // status >= 200 && < 300
}
```

#### `src/Data/Bulk/BulkResponse.php`

Wraps the collection of response items.

```php
final readonly class BulkResponse
{
    /** @param array<int, BulkResponseItem> $items */
    public function __construct(public array $items) {}

    public static function fromResponse(Response $response): self;

    /** @return array<int, int|string> */
    public function resourceIds(): array;

    public function allSuccessful(): bool;

    /** @return array<int, BulkResponseItem> */
    public function failures(): array;
}
```

### Request Classes

All in `src/Requests/Bulk/`.

#### `BulkCreateRequest`

- **Method:** POST
- **Endpoint:** `{baseEndpoint}/multiple/`
- **Constructor:** `string $endpoint`, `@param array<int, array<string, mixed>> $data`
- **Body:** `$data` passed directly (returns `array<int, array<string, mixed>>` from `defaultBody()`)
- **DTO:** `BulkResponse`

#### `BulkUpdateRequest`

- **Method:** PATCH
- **Endpoint:** `{baseEndpoint}/multiple/`
- **Constructor:** `string $endpoint`, `@param array<int, array<string, mixed>> $data`
- **Body:** `$data` passed directly (each item must include `ID`)
- **DTO:** `BulkResponse`

#### `BulkDeleteRequest`

- **Method:** POST
- **Endpoint:** `{baseEndpoint}/delete/`
- **Constructor:** `string $endpoint`, `@param array<int, int|string> $ids`
- **Body:** `['IDs' => $ids]` (returns `array{IDs: array<int, int|string>}` from `defaultBody()`)
- **DTO:** `array<string>` (message strings from API)

Each class accepts `string $endpoint` and the data/IDs in its constructor, appends the appropriate suffix in `resolveEndpoint()`.

## Modified Files

### Resource Integration

Every resource that has `create()`, `update()`, or `delete()` gets the corresponding `bulkCreate()`, `bulkUpdate()`, or `bulkDelete()` method. The resource constructs the generic request class with its base endpoint path.

**Pattern (example: ContractorResource):**

```php
public function bulkCreate(array $data): BulkResponse
{
    $request = new BulkCreateRequest(
        "/api/v1.0/companies/{$this->companyId}/contractors",
        $data,
    );
    return $this->connector->send($request)->dto();
}

public function bulkUpdate(array $data): BulkResponse
{
    $request = new BulkUpdateRequest(
        "/api/v1.0/companies/{$this->companyId}/contractors",
        $data,
    );
    return $this->connector->send($request)->dto();
}

/** @return array<string> */
public function bulkDelete(array $ids): array
{
    $request = new BulkDeleteRequest(
        "/api/v1.0/companies/{$this->companyId}/contractors",
        $ids,
    );
    return $this->connector->send($request)->dto();
}
```

The endpoint string is constructed from the same IDs the resource already holds. Resources that only have some CRUD operations get only the matching bulk methods (e.g., a resource with `create()` and `update()` but no `delete()` gets `bulkCreate()` and `bulkUpdate()` only).

### Resources to Modify

Resources with `create()` get `bulkCreate()`. Resources with `update()` get `bulkUpdate()`. Resources with `delete()` get `bulkDelete()`.

The full list of affected resources (60+) spans all directories under `src/Resources/` including Employees, Contractors, Customers, Jobs, Quotes, Invoices, and Setup.

**Exclusions:** Resources where bulk operations don't make semantic sense:
- Lock resources (`JobLockResource`, `QuoteLockResource`, `CostCenterLockResource`) — these toggle a lock state, not create/delete resources
- `CurrencyResource` — currencies are system-managed reference data, not user-created entities; bulk operations are not meaningful here
- Custom field value resources (e.g., `CustomerCustomFieldResource`, `JobCustomFieldResource`) — these only have `update()` for setting field values on an entity, not CRUD on the fields themselves

**Inclusions (note):** Setup custom field *definition* resources (the 14+ resources extending `AbstractCustomFieldResource` in `src/Resources/Setup/`) DO get bulk operations — these manage field definitions and have full CRUD.

**Error handling:** The `BulkResponse` DTO only applies when the API returns a 200 response with per-item results. If the entire request fails (non-2xx), the existing `AbstractSimproConnector` error handling throws before DTO mapping occurs.

## Usage Examples

```php
// Bulk create
$response = $connector->contractors(companyId: 0)->bulkCreate([
    ['GivenName' => 'Peter', 'FamilyName' => 'Smith'],
    ['GivenName' => 'Michael', 'FamilyName' => 'Dickson'],
]);
$ids = $response->resourceIds(); // [1882, 1883]

// With custom batch IDs
$response = $connector->contractors(companyId: 0)->bulkCreate([
    ['BatchID' => 53, 'GivenName' => 'Peter', 'FamilyName' => 'Smith'],
    ['BatchID' => 655, 'GivenName' => 'Michael', 'FamilyName' => 'Dickson'],
]);

// Bulk update (include ID in each item)
$response = $connector->contractors(companyId: 0)->bulkUpdate([
    ['ID' => 1884, 'GivenName' => 'Pete'],
    ['ID' => 1885, 'GivenName' => 'Mike'],
]);

// Bulk delete
$messages = $connector->jobs(companyId: 0)->bulkDelete([210787, 210788]);
// ['2 section(s) deleted.']

// Nested resources
$messages = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->attachmentFiles()
    ->bulkDelete(['pEwTUfJ9jX...', 'MEPQuk-TKv...']);

// Check for failures
if (!$response->allSuccessful()) {
    foreach ($response->failures() as $failure) {
        echo "Batch {$failure->batchId} failed with status {$failure->status}\n";
    }
}
```

## Testing

- **DTO unit tests:** `BulkResponseItem::fromArray()`, `BulkResponse::fromResponse()`, `isSuccessful()`, `resourceIds()`, `allSuccessful()`, `failures()`
- **Request tests:** Verify endpoint resolution appends `/multiple/` or `/delete/`, correct HTTP methods, body structure
- **Resource integration test:** One representative resource (e.g., ContractorResource) tested for `bulkCreate`, `bulkUpdate`, `bulkDelete`
- **Fixtures:** `bulk_create_request.json`, `bulk_update_request.json`, `bulk_delete_request.json`

Since the generic request classes are shared across all resources, testing every resource's bulk methods is unnecessary — the pattern is verified once.
