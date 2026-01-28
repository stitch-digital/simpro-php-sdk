# Job Timelines Resource

> [Jobs](../jobs-resource.md) > Timelines

Read-only access to the activity timeline for a job.

## Navigation

```php
// Access timelines for a specific job
$connector->jobs(companyId: 0)->job(jobId: 123)->timelines()
```

## Listing Timeline Events

```php
$events = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->timelines()
    ->list()
    ->all();

foreach ($events as $event) {
    echo "{$event->date}: {$event->description}\n";
}
```

## Response Structure

### TimelineEvent

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Event ID |
| `date` | `?DateTimeImmutable` | Event date/time |
| `description` | `?string` | Event description |
| `type` | `?string` | Event type |
| `user` | `?StaffReference` | User who performed the action |

## Usage Notes

- Timelines are read-only and cannot be modified via the API
- Events include status changes, notes added, schedules created, etc.
- Useful for auditing and tracking job history
