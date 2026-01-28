# Job Cost Centers Resource

> [Jobs](../jobs-resource.md) > [Sections](./sections-resource.md) > Cost Centers

Cost centers group related work items within a job section. They can contain labor, materials, assets, work orders, and more.

## Navigation

```php
// Access cost centers for a specific section
$connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenters()
```

## Listing Cost Centers

```php
$costCenters = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenters()
    ->list()
    ->all();

foreach ($costCenters as $costCenter) {
    echo "{$costCenter->id}: {$costCenter->name}\n";
}
```

## Getting a Single Cost Center

```php
$costCenter = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenters()
    ->get(costCenterId: 5);

echo "Cost Center: {$costCenter->name}\n";
```

## Creating a Cost Center

```php
$costCenterId = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenters()
    ->create(data: [
        'Name' => 'Electrical Work',
        'Description' => 'All electrical installation work',
    ]);

echo "Created cost center with ID: {$costCenterId}\n";
```

## Updating a Cost Center

```php
$response = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenters()
    ->update(costCenterId: 5, data: [
        'Name' => 'Electrical Work - Updated',
    ]);
```

## Deleting a Cost Center

```php
$response = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenters()
    ->delete(costCenterId: 5);
```

## Response Structure

### CostCenter

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Cost center ID |
| `name` | `?string` | Cost center name |
| `description` | `?string` | Description |

## Nested Resources

Cost centers provide access to many sub-resources. Use the `costCenter()` scope to navigate:

```php
$connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->labor()  // or ->assets(), ->workOrders(), etc.
```

| Resource | Documentation | Description |
|----------|---------------|-------------|
| Labor | [Labor](./labor-resource.md) | Labor line items |
| Assets | [Assets](./assets-resource.md) | Customer assets |
| Catalogs | [Catalogs](./catalogs-resource.md) | Catalog/material items |
| One-Offs | [One-Offs](./one-offs-resource.md) | One-off line items |
| Prebuilds | [Prebuilds](./prebuilds-resource.md) | Prebuild assemblies |
| Service Fees | [Service Fees](./service-fees-resource.md) | Service fee items |
| Stock | [Stock](./stock-resource.md) | Stock items |
| Work Orders | [Work Orders](./work-orders-resource.md) | Work orders |
| Contractor Jobs | [Contractor Jobs](./contractor-jobs-resource.md) | Subcontractor work |
| Schedules | [Schedules](./schedules-resource.md) | Cost center schedules |
| Tasks | [Tasks](./tasks-resource.md) | Associated tasks |
| Lock | [Lock](./lock-resource.md) | Lock/unlock cost center |
