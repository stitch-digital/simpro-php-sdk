# Job Notes Resource

> [Jobs](../jobs-resource.md) > Notes

Notes allow you to add comments, updates, and follow-up reminders to jobs.

## Navigation

```php
// Access notes for a specific job
$connector->jobs(companyId: 0)->job(jobId: 123)->notes()
```

## Listing Notes

```php
$notes = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->notes()
    ->list()
    ->all();

foreach ($notes as $note) {
    echo "{$note->subject}: {$note->note}\n";
}
```

### With Filtering

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$notes = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->notes()
    ->list()
    ->search(Search::make()->column('Subject')->find('site visit'))
    ->orderByDesc('DateCreated')
    ->all();
```

## Getting a Single Note

```php
$note = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->notes()
    ->get(noteId: 456);

echo "Subject: {$note->subject}\n";
echo "Note: {$note->note}\n";
```

## Creating a Note

```php
$noteId = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->notes()
    ->create(data: [
        'Subject' => 'Site visit scheduled',
        'Note' => 'Confirmed appointment for Tuesday at 9am with site manager.',
    ]);

echo "Created note with ID: {$noteId}\n";
```

### With Follow-up Date

```php
$noteId = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->notes()
    ->create(data: [
        'Subject' => 'Follow up required',
        'Note' => 'Customer requested callback after material delivery.',
        'FollowUpDate' => '2024-02-15',
    ]);
```

## Updating a Note

```php
$response = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->notes()
    ->update(noteId: 456, data: [
        'Note' => 'Updated note content - appointment rescheduled to Wednesday.',
    ]);

if ($response->successful()) {
    echo "Note updated\n";
}
```

## Response Structure

### Note

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Note ID |
| `subject` | `?string` | Note subject/title |
| `note` | `?string` | Note content |
| `dateCreated` | `?DateTimeImmutable` | Creation date |
| `followUpDate` | `?DateTimeImmutable` | Follow-up reminder date |
| `submittedBy` | `?StaffReference` | Note author |

## Examples

### List Recent Notes

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$notes = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->notes()
    ->list()
    ->orderByDesc('DateCreated')
    ->collect()
    ->take(5)
    ->all();
```

### Find Notes with Follow-ups

```php
$today = date('Y-m-d');

$notes = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->notes()
    ->list()
    ->search(Search::make()->column('FollowUpDate')->lessThanOrEqual($today))
    ->all();

foreach ($notes as $note) {
    echo "Follow-up needed: {$note->subject}\n";
}
```
