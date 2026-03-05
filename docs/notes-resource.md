# Notes Resource

The Notes resource provides read-only access to notes across your Simpro environment. Supports customer notes and job notes at the company level, listing all notes across all customers or jobs.

## Basic Usage

```php
use Simpro\PhpSdk\Simpro\Connectors\SimproApiKeyConnector;

$connector = new SimproApiKeyConnector(
    baseUrl: 'https://your-instance.simprocloud.com',
    apiKey: 'your-api-key'
);

// Access notes for a company
$notes = $connector->notes(companyId: 0);
```

## Available Methods

### List Customer Notes

```php
// List all customer notes across all customers
$notes = $connector->notes(0)->customers()->all();

foreach ($notes as $note) {
    echo "{$note->id}: {$note->subject}\n";
    echo "  Customer: {$note->customer?->companyName}\n";
    echo "  Visible to customer: " . ($note->visibility?->customer ? 'Yes' : 'No') . "\n";
}

// With search
use Simpro\PhpSdk\Simpro\Query\Search;

$notes = $connector->notes(0)->customers()
    ->search(Search::make()->column('Subject')->contains('Quote'))
    ->orderByDesc('ID')
    ->all();
```

### List Customer Notes (Detailed)

The detailed list includes additional fields: note content (HTML), dates, attachments, and staff references.

```php
// Detailed list with all columns
$notes = $connector->notes(0)->customersDetailed()->all();

foreach ($notes as $note) {
    echo "{$note->id}: {$note->subject}\n";
    echo "  Created: {$note->dateCreated?->format('Y-m-d')}\n";
    echo "  Follow-up: {$note->followUpDate?->format('Y-m-d')}\n";
    echo "  Submitted by: {$note->submittedBy?->name}\n";
    echo "  Assigned to: {$note->assignTo?->name}\n";
    echo "  Attachments: " . count($note->attachments) . "\n";
}
```

### List Job Notes

```php
// List all job notes across all jobs
$notes = $connector->notes(0)->jobs()->all();

foreach ($notes as $note) {
    echo "{$note->id}: {$note->subject}\n";
    echo "  Job: {$note->job?->name}\n";
    echo "  Visible to admin: " . ($note->visibility?->admin ? 'Yes' : 'No') . "\n";
}

// With search
use Simpro\PhpSdk\Simpro\Query\Search;

$notes = $connector->notes(0)->jobs()
    ->search(Search::make()->column('Subject')->contains('scheduled'))
    ->orderByDesc('ID')
    ->all();
```

### List Job Notes (Detailed)

The detailed list includes additional fields: note content (HTML), dates, attachments, and staff references.

```php
// Detailed list with all columns
$notes = $connector->notes(0)->jobsDetailed()->all();

foreach ($notes as $note) {
    echo "{$note->id}: {$note->subject}\n";
    echo "  Job: {$note->job?->name}\n";
    echo "  Created: {$note->dateCreated?->format('Y-m-d')}\n";
    echo "  Follow-up: {$note->followUpDate?->format('Y-m-d')}\n";
    echo "  Submitted by: {$note->submittedBy?->name}\n";
    echo "  Assigned to: {$note->assignTo?->name}\n";
    echo "  Attachments: " . count($note->attachments) . "\n";
}
```

## DTOs

### CustomerNoteListItem

| Field | Type | Description |
|-------|------|-------------|
| `id` | `int` | Note ID |
| `subject` | `?string` | Note subject |
| `visibility` | `?NoteVisibility` | Visibility settings |
| `customer` | `?CustomerNoteCustomer` | Customer reference |
| `href` | `?string` | API link to this note |

### CustomerNoteDetailedListItem

Includes all fields from `CustomerNoteListItem` plus:

| Field | Type | Description |
|-------|------|-------------|
| `note` | `?string` | Note content (HTML) |
| `dateCreated` | `?DateTimeImmutable` | Date the note was created |
| `followUpDate` | `?DateTimeImmutable` | Follow-up date |
| `attachments` | `array<NoteAttachment>` | Attached files |
| `assignTo` | `?StaffReference` | Staff member assigned to the note |
| `submittedBy` | `?StaffReference` | Staff member who submitted the note |

### NoteVisibility

| Field | Type | Description |
|-------|------|-------------|
| `customer` | `?bool` | Visible to customer |
| `admin` | `?bool` | Visible to admin |

### CustomerNoteCustomer

| Field | Type | Description |
|-------|------|-------------|
| `id` | `int` | Customer ID |
| `companyName` | `?string` | Company name |
| `givenName` | `?string` | Given name |
| `familyName` | `?string` | Family name |

### JobNoteListItem

| Field | Type | Description |
|-------|------|-------------|
| `id` | `int` | Note ID |
| `subject` | `?string` | Note subject |
| `visibility` | `?NoteVisibility` | Visibility settings |
| `job` | `?JobNoteJob` | Job reference |
| `href` | `?string` | API link to this note |

### JobNoteDetailedListItem

Includes all fields from `JobNoteListItem` plus:

| Field | Type | Description |
|-------|------|-------------|
| `note` | `?string` | Note content (HTML) |
| `dateCreated` | `?DateTimeImmutable` | Date the note was created |
| `followUpDate` | `?DateTimeImmutable` | Follow-up date |
| `attachments` | `array<NoteAttachment>` | Attached files |
| `assignTo` | `?StaffReference` | Staff member assigned to the note |
| `submittedBy` | `?StaffReference` | Staff member who submitted the note |

### JobNoteJob

| Field | Type | Description |
|-------|------|-------------|
| `id` | `int` | Job ID |
| `name` | `?string` | Job name |

## Examples

### Find Notes for a Specific Customer

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$notes = $connector->notes(0)->customers()
    ->search(Search::make()->column('Customer.ID')->is('201'))
    ->all();
```

### Get Recent Notes with Details

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$recent = $connector->notes(0)->customersDetailed()
    ->search(Search::make()->column('DateCreated')->greaterThanOrEqual('2026-01-01'))
    ->orderByDesc('DateCreated')
    ->all();

foreach ($recent as $note) {
    echo "{$note->subject} - {$note->dateCreated?->format('Y-m-d')}\n";
    echo "  By: {$note->submittedBy?->name}\n";
}
```

## Pagination

Customer notes support pagination through the QueryBuilder:

```php
// Iterate through all pages
foreach ($connector->notes(0)->customers()->items() as $note) {
    echo "{$note->id}: {$note->subject}\n";
}

// Get first page only
$firstPage = $connector->notes(0)->customers()->first();
```

### Get Recent Job Notes with Details

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$recent = $connector->notes(0)->jobsDetailed()
    ->search(Search::make()->column('DateCreated')->greaterThanOrEqual('2026-01-01'))
    ->orderByDesc('DateCreated')
    ->all();

foreach ($recent as $note) {
    echo "{$note->subject} - {$note->dateCreated?->format('Y-m-d')}\n";
    echo "  Job: {$note->job?->name}\n";
    echo "  By: {$note->submittedBy?->name}\n";
}
```

## Limitations

The Notes resource is **read-only** at the company level. To manage notes for a specific job, use the Jobs resource's nested notes endpoints. To manage notes for a specific customer, use the Customers resource's nested notes endpoints.
