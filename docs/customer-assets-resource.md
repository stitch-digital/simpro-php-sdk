# Customer Assets Resource

The Customer Assets resource provides read-only access to customer assets with their associated sites across your Simpro environment.

## Basic Usage

```php
use Simpro\PhpSdk\Simpro\Connectors\SimproApiKeyConnector;

$connector = new SimproApiKeyConnector(
    baseUrl: 'https://your-instance.simprocloud.com',
    apiKey: 'your-api-key'
);

// Access customer assets for a company
$assets = $connector->customerAssets(companyId: 0);
```

## Available Methods

### List Customer Assets

```php
// List all customer assets
$assets = $connector->customerAssets(0)->list()->all();

foreach ($assets as $asset) {
    echo "{$asset->id}: {$asset->assetType?->name}\n";
    echo "  Site: {$asset->site?->name}\n";

    foreach ($asset->serviceLevels ?? [] as $level) {
        echo "  Service: {$level->name} (due: {$level->serviceDate})\n";
    }
}

// With search
use Simpro\PhpSdk\Simpro\Query\Search;

$assets = $connector->customerAssets(0)->list()
    ->search(Search::make()->column('Site.ID')->is('6128'))
    ->orderByDesc('ID')
    ->all();
```

### List Customer Assets (Detailed)

The detailed list includes additional fields: DisplayOrder, CustomerContract, StartDate, LastTest, Archived, CustomFields, ParentID, and DateModified.

```php
// Detailed list with all columns
$assets = $connector->customerAssets(0)->listDetailed()->all();

foreach ($assets as $asset) {
    echo "{$asset->id}: {$asset->assetType?->name}\n";
    echo "  Start Date: {$asset->startDate}\n";
    echo "  Archived: " . ($asset->archived ? 'Yes' : 'No') . "\n";
    echo "  Last Test: {$asset->lastTest?->result} on {$asset->lastTest?->date}\n";

    if ($asset->customerContract) {
        echo "  Contract: {$asset->customerContract->name}\n";
    }
}
```

## DTOs

### CustomerAssetListItem

| Field | Type | Description |
|-------|------|-------------|
| `id` | `int` | Asset ID |
| `assetType` | `?Reference` | Asset type (ID and Name) |
| `site` | `?Reference` | Site (ID and Name) |
| `serviceLevels` | `?array<CustomerAssetServiceLevel>` | Service level schedules |

### CustomerAssetListDetailedItem

| Field | Type | Description |
|-------|------|-------------|
| `id` | `int` | Asset ID |
| `assetType` | `?Reference` | Asset type (ID and Name) |
| `displayOrder` | `?int` | Display order |
| `customerContract` | `?CustomerAssetContract` | Associated contract |
| `startDate` | `?string` | Asset start date |
| `lastTest` | `?CustomerAssetLastTest` | Last test result |
| `archived` | `?bool` | Whether the asset is archived |
| `customFields` | `?array<CustomField>` | Custom field values |
| `parentId` | `?int` | Parent asset ID |
| `dateModified` | `?DateTimeImmutable` | Last modification timestamp |
| `site` | `?Reference` | Site (ID and Name) |

### CustomerAssetServiceLevel

| Field | Type | Description |
|-------|------|-------------|
| `id` | `int` | Service level ID |
| `name` | `?string` | Service level name |
| `serviceDate` | `?string` | Next service date |

### CustomerAssetLastTest

| Field | Type | Description |
|-------|------|-------------|
| `result` | `?string` | Test result (e.g. "Pass", "No Test") |
| `date` | `?string` | Test date |
| `serviceLevel` | `?Reference` | Service level (ID and Name) |

### CustomerAssetContract

| Field | Type | Description |
|-------|------|-------------|
| `id` | `int` | Contract ID |
| `name` | `?string` | Contract name |
| `startDate` | `?string` | Contract start date |
| `endDate` | `?string` | Contract end date |
| `contractNo` | `?string` | Contract number |
| `expired` | `?bool` | Whether the contract has expired |

## Pagination

Customer assets support pagination through the QueryBuilder:

```php
// Iterate through all pages
foreach ($connector->customerAssets(0)->list()->items() as $asset) {
    echo "{$asset->id}: {$asset->assetType?->name}\n";
}

// Get first result only
$first = $connector->customerAssets(0)->list()->first();
```

## Limitations

The Customer Assets resource is **read-only**. Only `list()` and `listDetailed()` are currently supported.
