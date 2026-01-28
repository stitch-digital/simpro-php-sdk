# Jobs Nested Resources

> [Jobs](../jobs-resource.md)

This directory contains documentation for all nested resources under Jobs. The SDK supports navigation up to 6 levels deep using a scope-based fluent API.

## Resource Hierarchy

```
Jobs
├── Attachment Files
├── Attachment Folders
├── Custom Fields
├── Lock
├── Notes
├── Sections
│   └── Cost Centers
│       ├── Assets
│       ├── Catalogs
│       ├── Contractor Jobs
│       │   ├── Attachment Files
│       │   ├── Attachment Folders
│       │   └── Custom Fields
│       ├── Labor
│       ├── Lock
│       ├── One-Offs
│       ├── Prebuilds
│       ├── Schedules
│       ├── Service Fees
│       ├── Stock
│       ├── Tasks
│       └── Work Orders
│           ├── Assets
│           │   └── Test Results
│           │       └── Attachment Files
│           ├── Attachment Files
│           ├── Custom Fields
│           └── Mobile Signatures
├── Tasks
└── Timelines
```

## Documentation Pages

### Job-Level Resources (Level 2)

| Resource | Documentation | Description |
|----------|---------------|-------------|
| Attachments | [attachments-resource.md](./attachments-resource.md) | Files and folders |
| Custom Fields | [custom-fields-resource.md](./custom-fields-resource.md) | Custom field values |
| Lock | [lock-resource.md](./lock-resource.md) | Job locking |
| Notes | [notes-resource.md](./notes-resource.md) | Job notes |
| Sections | [sections-resource.md](./sections-resource.md) | Job sections |
| Tasks | [tasks-resource.md](./tasks-resource.md) | Associated tasks |
| Timelines | [timelines-resource.md](./timelines-resource.md) | Activity timelines |

### Section-Level Resources (Level 3)

| Resource | Documentation | Description |
|----------|---------------|-------------|
| Cost Centers | [cost-centers-resource.md](./cost-centers-resource.md) | Cost center management |

### Cost Center Resources (Level 4)

| Resource | Documentation | Description |
|----------|---------------|-------------|
| Assets | [assets-resource.md](./assets-resource.md) | Customer assets |
| Catalogs | [catalogs-resource.md](./catalogs-resource.md) | Catalog/material items |
| Contractor Jobs | [contractor-jobs-resource.md](./contractor-jobs-resource.md) | Subcontractor work |
| Labor | [labor-resource.md](./labor-resource.md) | Labor line items |
| Lock | [lock-resource.md](./lock-resource.md) | Cost center locking |
| One-Offs | [one-offs-resource.md](./one-offs-resource.md) | One-off items |
| Prebuilds | [prebuilds-resource.md](./prebuilds-resource.md) | Prebuild assemblies |
| Schedules | [schedules-resource.md](./schedules-resource.md) | Cost center schedules |
| Service Fees | [service-fees-resource.md](./service-fees-resource.md) | Service fees |
| Stock | [stock-resource.md](./stock-resource.md) | Stock items |
| Tasks | [tasks-resource.md](./tasks-resource.md) | Cost center tasks |
| Work Orders | [work-orders-resource.md](./work-orders-resource.md) | Work orders |

### Deep Nested Resources (Levels 5-6)

| Resource | Documentation | Description |
|----------|---------------|-------------|
| Work Order Assets | [work-orders-resource.md](./work-orders-resource.md#work-order-assets) | Assets on work orders |
| Asset Test Results | [work-orders-resource.md](./work-orders-resource.md#deep-nesting---asset-test-results-level-6) | Test results and attachments |

## Navigation Pattern

All nested resources follow a consistent scope-based pattern:

```php
// Level 2: Job resources
$connector->jobs(companyId: 0)->job(jobId: 123)->notes()

// Level 3: Section resources
$connector->jobs(companyId: 0)->job(jobId: 123)->section(sectionId: 1)->costCenters()

// Level 4: Cost center resources
$connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->labor()

// Level 5: Work order resources
$connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->workOrder(workOrderId: 99)
    ->assets()

// Level 6: Asset test result resources
$connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->workOrder(workOrderId: 99)
    ->asset(assetId: 77)
    ->testResults()
```

## Common Operations

All resources support standard operations where applicable:

| Operation | Method | Description |
|-----------|--------|-------------|
| List | `->list()` | Returns QueryBuilder for filtering/pagination |
| Get | `->get(id)` | Returns single DTO |
| Create | `->create(data)` | Returns created ID |
| Update | `->update(id, data)` | Returns Response |
| Delete | `->delete(id)` | Returns Response |
| Bulk Replace | `->bulkReplace(data)` | Replaces all items (where supported) |
