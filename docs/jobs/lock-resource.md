# Job Lock Resource

> [Jobs](../jobs-resource.md) > Lock

Lock and unlock jobs and cost centers to prevent concurrent modifications.

## Job Lock

### Navigation

```php
// Access lock for a specific job
$connector->jobs(companyId: 0)->job(jobId: 123)->lock()
```

### Lock a Job

```php
$response = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->lock()
    ->create(data: []);

if ($response->successful()) {
    echo "Job locked\n";
}
```

### Unlock a Job

```php
$response = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->lock()
    ->delete();

if ($response->successful()) {
    echo "Job unlocked\n";
}
```

## Cost Center Lock

### Navigation

```php
// Access lock for a specific cost center
$connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->lock()
```

### Lock a Cost Center

```php
$response = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->lock()
    ->create(data: []);
```

### Unlock a Cost Center

```php
$response = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->lock()
    ->delete();
```

## Usage Notes

- Locks prevent other users from making changes while locked
- Always unlock resources when finished to avoid blocking other users
- Consider using try/finally to ensure locks are released
