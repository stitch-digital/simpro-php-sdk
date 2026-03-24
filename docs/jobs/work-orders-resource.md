# Job Work Orders Resource

> [Jobs](../jobs-resource.md) > [Sections](./sections-resource.md) > [Cost Centers](./cost-centers-resource.md) > Work Orders

Work orders represent specific pieces of work within a cost center, often used for field service operations.

## Navigation

```php
// Access work orders for a specific cost center
$connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->workOrders()
```

## Listing Work Orders

```php
$workOrders = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->workOrders()
    ->list()
    ->all();

foreach ($workOrders as $workOrder) {
    echo "{$workOrder->id}: {$workOrder->staff?->name} on {$workOrder->workOrderDate}\n";
}
```

## Listing Work Orders (Detailed)

Returns all fields including blocks, materials, scheduled times, custom fields, and assets.

```php
$workOrders = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->workOrders()
    ->listDetailed()
    ->all();

foreach ($workOrders as $workOrder) {
    echo "{$workOrder->id}: {$workOrder->staff?->name}\n";
    echo "  Approved: " . ($workOrder->approved ? 'Yes' : 'No') . "\n";
    echo "  Scheduled: {$workOrder->scheduledHrs}hrs\n";

    foreach ($workOrder->blocks as $block) {
        echo "  Block: {$block->startTime} - {$block->endTime} ({$block->hrs}hrs)\n";
    }

    foreach ($workOrder->workOrderAssets as $asset) {
        echo "  Asset #{$asset->assetId}: {$asset->assetType?->name} - {$asset->result}\n";
    }
}

// Exclude materials from the response for performance
$workOrders = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->workOrders()
    ->listDetailed(includeMaterials: false)
    ->all();
```

## Getting a Single Work Order

```php
$workOrder = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->workOrders()
    ->get(workOrderId: 99);
```

## Creating a Work Order

```php
$workOrderId = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->workOrders()
    ->create(data: [
        'Staff' => ['ID' => 1446],
        'WorkOrderDate' => '2025-11-17',
    ]);

echo "Created work order with ID: {$workOrderId}\n";
```

## Updating a Work Order

```php
$response = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->workOrders()
    ->update(workOrderId: 99, data: [
        'WorkOrderDate' => '2025-11-20',
    ]);
```

## Response Structure

### WorkOrderListItem

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Work order ID |
| `staff` | `?StaffReference` | Assigned staff member (ID, Name, Type, TypeId) |
| `workOrderDate` | `?string` | Date of the work order (YYYY-MM-DD) |

### WorkOrderDetailed

Extends the list item with additional fields:

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Work order ID |
| `staff` | `?StaffReference` | Assigned staff member |
| `workOrderDate` | `?string` | Date of the work order |
| `descriptionNotes` | `?string` | Description notes (may contain HTML) |
| `materialNotes` | `?string` | Material notes (may contain HTML) |
| `approved` | `?bool` | Whether the work order is approved |
| `materials` | `array` | Materials used |
| `blocks` | `array<JobWorkOrderBlock>` | Scheduled time blocks |
| `scheduledHrs` | `?float` | Total scheduled hours |
| `scheduledStartTime` | `?string` | Scheduled start time (HH:MM) |
| `iso8601ScheduledStartTime` | `?DateTimeImmutable` | ISO 8601 scheduled start time |
| `scheduledEndTime` | `?string` | Scheduled end time (HH:MM) |
| `iso8601ScheduledEndTime` | `?DateTimeImmutable` | ISO 8601 scheduled end time |
| `dateModified` | `?DateTimeImmutable` | Last modified date |
| `customFields` | `array<CustomField>` | Custom field values |
| `workOrderAssets` | `array<WorkOrderAssetReference>` | Assets on this work order |

### WorkOrderAssetReference (nested in detailed response)

| Property | Type | Description |
|----------|------|-------------|
| `assetId` | `?int` | Asset ID |
| `assetType` | `?Reference` | Asset type (ID, Name) |
| `result` | `?string` | Result (e.g., "Pass", "Fail") |

## Nested Resources

Work orders have their own nested resources. Use the `workOrder()` scope to navigate:

```php
$connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->workOrder(workOrderId: 99)
    ->assets()  // or ->attachmentFiles(), ->customFields(), etc.
```

### Work Order Assets

```php
// List assets on a work order
$assets = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->workOrder(workOrderId: 99)
    ->assets()
    ->list()
    ->all();

foreach ($assets as $asset) {
    echo "Asset #{$asset->assetId}: {$asset->assetType?->name} - {$asset->result}\n";
}

// List assets with detailed information
$assets = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->workOrder(workOrderId: 99)
    ->assets()
    ->listDetailed()
    ->all();

foreach ($assets as $asset) {
    echo "Asset #{$asset->assetId}: {$asset->result}\n";
    echo "  Service Level: {$asset->serviceLevel?->name}\n";
    echo "  Notes: {$asset->notes}\n";

    foreach ($asset->testReadings as $reading) {
        echo "  Reading: {$reading->testReading?->name} = {$reading->value}\n";
    }
}
```

### Work Order Asset Response Structure

#### WorkOrderAsset (list)

| Property | Type | Description |
|----------|------|-------------|
| `assetId` | `?int` | Asset ID |
| `assetType` | `?Reference` | Asset type (ID, Name) |
| `result` | `?string` | Result (e.g., "Pass", "Fail") |

#### WorkOrderAssetDetailed (listDetailed)

| Property | Type | Description |
|----------|------|-------------|
| `assetId` | `?int` | Asset ID |
| `assetType` | `?Reference` | Asset type (ID, Name) |
| `serviceLevel` | `?Reference` | Service level (ID, Name) |
| `result` | `?string` | Result |
| `notes` | `?string` | Notes |
| `failurePoints` | `array` | Failure points |
| `testReadings` | `array<TestReading>` | Test reading results |

#### TestReading

| Property | Type | Description |
|----------|------|-------------|
| `testReading` | `?Reference` | Test reading definition (ID, Name) |
| `value` | `mixed` | Test reading value |

### Work Order Attachments

```php
// List attachments on a work order
$attachments = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->workOrder(workOrderId: 99)
    ->attachmentFiles()
    ->list()
    ->all();
```

### Work Order Custom Fields

```php
// List custom fields on a work order
$customFields = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->workOrder(workOrderId: 99)
    ->customFields()
    ->list()
    ->all();
```

### Mobile Signatures

```php
// List mobile signatures
$signatures = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->workOrder(workOrderId: 99)
    ->mobileSignatures()
    ->list()
    ->all();
```

## Deep Nesting - Asset Test Results (Level 6)

Work order assets can have test results with attachments:

```php
// Get test results for an asset (6 levels deep)
$testResults = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->workOrder(workOrderId: 99)
    ->asset(assetId: 77)
    ->testResults()
    ->list()
    ->all();

// Get attachments on a test result
$attachments = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->workOrder(workOrderId: 99)
    ->asset(assetId: 77)
    ->testResult(testResultId: 55)
    ->attachmentFiles()
    ->list()
    ->all();
```
