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
    echo "{$workOrder->id}: {$workOrder->name}\n";
}
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
        'Name' => 'Install HVAC unit',
        'Description' => 'Main unit installation in plant room',
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
        'Name' => 'Install HVAC unit - Complete',
    ]);
```

## Response Structure

### WorkOrder

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Work order ID |
| `name` | `?string` | Work order name |
| `description` | `?string` | Description |
| `status` | `?string` | Status |

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
```

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
