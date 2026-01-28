# Quotes Resource

The Quotes resource provides full CRUD operations for managing quotes in your Simpro environment. Quotes represent pricing proposals sent to customers before work begins.

## Listing Quotes

### Basic List

Returns all quotes with pagination support:

```php
$quotes = $connector->quotes(companyId: 0)->list();

foreach ($quotes->items() as $quote) {
    echo "{$quote->id}: {$quote->name} - {$quote->customer} (\${$quote->total})\n";
}
```

### Filtering Quotes

Use the fluent search API or array-based filters:

#### Fluent Search API (Recommended)

```php
use Simpro\PhpSdk\Simpro\Query\Search;

// Search by name
$quotes = $connector->quotes(companyId: 0)->list()
    ->search(Search::make()->column('Name')->find('Kitchen Renovation'))
    ->items();

// Search by customer
$quotes = $connector->quotes(companyId: 0)->list()
    ->where('CustomerID', '=', 456)
    ->items();

// Search by status
$quotes = $connector->quotes(companyId: 0)->list()
    ->where('Status', '=', 'Approved')
    ->items();

// Search by stage
$quotes = $connector->quotes(companyId: 0)->list()
    ->where('Stage', '=', 'Draft')
    ->items();

// Find quotes within a date range
$quotes = $connector->quotes(companyId: 0)->list()
    ->search(Search::make()->column('DateIssued')->between('2024-01-01', '2024-01-31'))
    ->orderByDesc('DateIssued')
    ->items();

// Multiple criteria with OR logic
$quotes = $connector->quotes(companyId: 0)->list()
    ->search([
        Search::make()->column('Status')->equals('Pending'),
        Search::make()->column('Status')->equals('Draft'),
    ])
    ->matchAny()
    ->items();
```

#### Array-Based Syntax

```php
// Search by name
$quotes = $connector->quotes(companyId: 0)->list(['Name' => '%25Kitchen%25']);

// Filter by status
$quotes = $connector->quotes(companyId: 0)->list(['Status' => 'Approved']);

// Order by date issued descending
$quotes = $connector->quotes(companyId: 0)->list(['orderby' => '-DateIssued']);
```

### Ordering Results

```php
// Order by date issued (newest first)
$quotes = $connector->quotes(companyId: 0)->list()
    ->orderByDesc('DateIssued')
    ->items();

// Order by total value (highest first)
$quotes = $connector->quotes(companyId: 0)->list()
    ->orderByDesc('Total')
    ->items();

// Order by name
$quotes = $connector->quotes(companyId: 0)->list()
    ->orderBy('Name')
    ->items();

// Order by expiry date
$quotes = $connector->quotes(companyId: 0)->list()
    ->orderBy('ExpiryDate')
    ->items();
```

## Getting a Single Quote

### Get by ID

Returns complete information for a specific quote:

```php
$quote = $connector->quotes(companyId: 0)->get(quoteId: 123);

return [
    'id' => $quote->id,
    'name' => $quote->name,
    'status' => $quote->status,
    'stage' => $quote->stage,
    'customer' => $quote->customer?->name,
    'site' => $quote->site?->name,
    'dateIssued' => $quote->dateIssued?->format('Y-m-d'),
    'dueDate' => $quote->dueDate?->format('Y-m-d'),
    'expiryDate' => $quote->expiryDate?->format('Y-m-d'),
    'totals' => [
        'subtotal' => $quote->totals?->subtotal,
        'tax' => $quote->totals?->tax,
        'total' => $quote->totals?->total,
    ],
];
```

### Get with Specific Columns

Optionally specify which columns to return:

```php
$quote = $connector->quotes(companyId: 0)->get(quoteId: 123, columns: ['ID', 'Name', 'Status', 'Total']);
```

## Creating a Quote

Create a new quote:

```php
$quoteId = $connector->quotes(companyId: 0)->create(data: [
    'Name' => 'Kitchen Renovation Project',
    'Customer' => ['ID' => 456],
    'Site' => ['ID' => 789],
    'DateIssued' => '2024-01-15',
    'DueDate' => '2024-02-15',
    'ExpiryDate' => '2024-03-15',
    'Description' => 'Complete kitchen renovation including cabinets and appliances',
]);

echo "Created quote with ID: {$quoteId}\n";
```

### Create Quote with Full Details

```php
$quoteId = $connector->quotes(companyId: 0)->create(data: [
    'Name' => 'Office Fit-out Q1 2024',
    'Customer' => ['ID' => 456],
    'Site' => ['ID' => 789],
    'DateIssued' => date('Y-m-d'),
    'DueDate' => date('Y-m-d', strtotime('+14 days')),
    'ExpiryDate' => date('Y-m-d', strtotime('+30 days')),
    'Description' => 'Office fit-out for level 2',
    'Notes' => 'Internal notes for the team',
    'OrderNo' => 'RFQ-2024-001',
    'Stage' => 'Draft',
]);
```

## Updating a Quote

Update an existing quote:

```php
$response = $connector->quotes(companyId: 0)->update(quoteId: 123, data: [
    'Name' => 'Kitchen Renovation - Updated',
    'DueDate' => '2024-03-01',
]);

if ($response->successful()) {
    echo "Quote updated successfully\n";
}
```

### Update Quote Status

```php
$response = $connector->quotes(companyId: 0)->update(quoteId: 123, data: [
    'Status' => 'Approved',
]);
```

### Update Quote Stage

```php
$response = $connector->quotes(companyId: 0)->update(quoteId: 123, data: [
    'Stage' => 'Sent',
]);
```

## Deleting a Quote

Delete a quote:

```php
$response = $connector->quotes(companyId: 0)->delete(quoteId: 123);

if ($response->successful()) {
    echo "Quote deleted successfully\n";
}
```

**Note:** Deleting quotes may be restricted based on quote status and whether it has been converted to a job.

## Response Structures

### QuoteListItem (List Response)

Lightweight object returned by `list()`:

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Quote ID |
| `name` | `?string` | Quote name |
| `site` | `?string` | Site name |
| `siteId` | `?string` | Site ID |
| `status` | `?string` | Quote status |
| `stage` | `?string` | Quote stage |
| `customer` | `?string` | Customer name |
| `customerId` | `?string` | Customer ID |
| `dateIssued` | `?string` | Date issued (YYYY-MM-DD) |
| `total` | `?float` | Quote total |

### Quote (Detailed Response)

Complete quote object returned by `get()`:

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Quote ID |
| `name` | `?string` | Quote name |
| `site` | `?QuoteSite` | Site details |
| `customer` | `?QuoteCustomer` | Customer details |
| `status` | `?string` | Quote status |
| `stage` | `?string` | Quote stage |
| `orderNo` | `?string` | Customer order/RFQ number |
| `description` | `?string` | Quote description |
| `notes` | `?string` | Internal notes |
| `dateIssued` | `?DateTimeImmutable` | Date issued |
| `dueDate` | `?DateTimeImmutable` | Response due date |
| `expiryDate` | `?DateTimeImmutable` | Quote expiry date |
| `totals` | `?QuoteTotals` | Quote totals breakdown |
| `dateModified` | `?DateTimeImmutable` | Last modification date |

### Nested Objects

**QuoteCustomer:**
| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Customer ID |
| `name` | `?string` | Customer name |

**QuoteSite:**
| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Site ID |
| `name` | `?string` | Site name |

**QuoteTotals:**
| Property | Type | Description |
|----------|------|-------------|
| `subtotal` | `?float` | Subtotal (ex tax) |
| `tax` | `?float` | Tax amount |
| `total` | `?float` | Total (inc tax) |

## Examples

### List Pending Quotes

```php
$quotes = $connector->quotes(companyId: 0)->list()
    ->where('Status', '=', 'Pending')
    ->orderByDesc('DateIssued')
    ->all();

foreach ($quotes as $quote) {
    echo "{$quote->name}: {$quote->customer} - \${$quote->total}\n";
}
```

### Find Expiring Quotes

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$today = date('Y-m-d');
$nextWeek = date('Y-m-d', strtotime('+7 days'));

$expiringQuotes = $connector->quotes(companyId: 0)->list()
    ->search([
        Search::make()->column('ExpiryDate')->between($today, $nextWeek),
        Search::make()->column('Status')->notEqual('Approved'),
    ])
    ->matchAll()
    ->orderBy('ExpiryDate')
    ->all();

foreach ($expiringQuotes as $quote) {
    echo "Expiring soon: {$quote->name} (expires: {$quote->expiryDate})\n";
}
```

### Get Quote Pipeline Summary

```php
$quotes = $connector->quotes(companyId: 0)->list()->all();

$pipeline = [
    'Draft' => ['count' => 0, 'value' => 0],
    'Sent' => ['count' => 0, 'value' => 0],
    'Pending' => ['count' => 0, 'value' => 0],
    'Approved' => ['count' => 0, 'value' => 0],
    'Declined' => ['count' => 0, 'value' => 0],
];

foreach ($quotes as $quote) {
    $stage = $quote->stage ?? 'Unknown';
    if (isset($pipeline[$stage])) {
        $pipeline[$stage]['count']++;
        $pipeline[$stage]['value'] += $quote->total ?? 0;
    }
}

echo "Quote Pipeline:\n";
foreach ($pipeline as $stage => $data) {
    echo "  {$stage}: {$data['count']} quotes, \$" . number_format($data['value'], 2) . "\n";
}
```

### Get Quote with Full Details

```php
$quoteId = 123;
$quote = $connector->quotes(companyId: 0)->get(quoteId: $quoteId);

$details = [
    'quote' => [
        'id' => $quote->id,
        'name' => $quote->name,
        'status' => $quote->status,
        'stage' => $quote->stage,
        'orderNo' => $quote->orderNo,
        'description' => $quote->description,
        'notes' => $quote->notes,
    ],
    'dates' => [
        'issued' => $quote->dateIssued?->format('F j, Y'),
        'due' => $quote->dueDate?->format('F j, Y'),
        'expires' => $quote->expiryDate?->format('F j, Y'),
        'modified' => $quote->dateModified?->format('Y-m-d H:i'),
    ],
    'totals' => [
        'subtotal' => number_format($quote->totals?->subtotal ?? 0, 2),
        'tax' => number_format($quote->totals?->tax ?? 0, 2),
        'total' => number_format($quote->totals?->total ?? 0, 2),
    ],
];

if ($quote->customer !== null) {
    $details['customer'] = [
        'id' => $quote->customer->id,
        'name' => $quote->customer->name,
    ];
}

if ($quote->site !== null) {
    $details['site'] = [
        'id' => $quote->site->id,
        'name' => $quote->site->name,
    ];
}

return $details;
```

### Calculate Conversion Rate

```php
$quotes = $connector->quotes(companyId: 0)->list()
    ->search(Search::make()->column('DateIssued')->between('2024-01-01', '2024-12-31'))
    ->all();

$total = count($quotes);
$approved = 0;
$declined = 0;
$approvedValue = 0;

foreach ($quotes as $quote) {
    if ($quote->status === 'Approved') {
        $approved++;
        $approvedValue += $quote->total ?? 0;
    } elseif ($quote->status === 'Declined') {
        $declined++;
    }
}

$conversionRate = $total > 0 ? ($approved / $total) * 100 : 0;

echo "Quote Conversion Analysis:\n";
echo "  Total Quotes: {$total}\n";
echo "  Approved: {$approved}\n";
echo "  Declined: {$declined}\n";
echo "  Conversion Rate: " . number_format($conversionRate, 1) . "%\n";
echo "  Total Value Won: \$" . number_format($approvedValue, 2) . "\n";
```

### Quotes for Customer

```php
$customerId = 456;

$quotes = $connector->quotes(companyId: 0)->list()
    ->where('CustomerID', '=', $customerId)
    ->orderByDesc('DateIssued')
    ->all();

$summary = [
    'totalQuotes' => count($quotes),
    'totalValue' => 0,
    'approvedValue' => 0,
    'quotes' => [],
];

foreach ($quotes as $quote) {
    $summary['totalValue'] += $quote->total ?? 0;
    if ($quote->status === 'Approved') {
        $summary['approvedValue'] += $quote->total ?? 0;
    }

    $summary['quotes'][] = [
        'id' => $quote->id,
        'name' => $quote->name,
        'dateIssued' => $quote->dateIssued,
        'total' => $quote->total,
        'status' => $quote->status,
    ];
}

return $summary;
```

### Export Quotes to CSV

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$startDate = '2024-01-01';
$endDate = '2024-12-31';

$quotes = $connector->quotes(companyId: 0)->list()
    ->search(Search::make()->column('DateIssued')->between($startDate, $endDate))
    ->orderBy('DateIssued')
    ->all();

$csv = "ID,Name,Customer,Site,Date Issued,Due Date,Expiry Date,Total,Status,Stage\n";
foreach ($quotes as $quote) {
    $csv .= implode(',', [
        $quote->id,
        '"' . str_replace('"', '""', $quote->name ?? '') . '"',
        '"' . str_replace('"', '""', $quote->customer ?? '') . '"',
        '"' . str_replace('"', '""', $quote->site ?? '') . '"',
        $quote->dateIssued ?? '',
        '', // Due date not in list response
        '', // Expiry date not in list response
        $quote->total ?? 0,
        '"' . ($quote->status ?? '') . '"',
        '"' . ($quote->stage ?? '') . '"',
    ]) . "\n";
}

file_put_contents('quotes_export.csv', $csv);
echo "Exported " . count($quotes) . " quotes\n";
```

### Follow-up on Pending Quotes

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$followUpDays = 7;
$followUpDate = date('Y-m-d', strtotime("-{$followUpDays} days"));

$quotes = $connector->quotes(companyId: 0)->list()
    ->search([
        Search::make()->column('Status')->equals('Pending'),
        Search::make()->column('Stage')->equals('Sent'),
        Search::make()->column('DateIssued')->lessThanOrEqual($followUpDate),
    ])
    ->matchAll()
    ->all();

foreach ($quotes as $quote) {
    $full = $connector->quotes(companyId: 0)->get(quoteId: $quote->id);

    $followUpData = [
        'quoteId' => $full->id,
        'quoteName' => $full->name,
        'customerName' => $full->customer?->name,
        'total' => $full->totals?->total,
        'dateIssued' => $full->dateIssued?->format('Y-m-d'),
        'daysSinceIssued' => (time() - $full->dateIssued?->getTimestamp()) / 86400,
    ];

    // Queue follow-up (implement your notification logic)
    // queueQuoteFollowUp($followUpData);

    echo "Follow-up needed: {$full->name} ({$full->customer?->name})\n";
}
```

## Performance Considerations

### Pagination

Quote lists are paginated automatically. The default page size is 30:

```php
$builder = $connector->quotes(companyId: 0)->list();

// Change page size if needed
$builder->getPaginator()->setPerPageLimit(100);

// Iterate automatically handles pagination
foreach ($builder->items() as $quote) {
    // Processes all quotes across all pages
}
```

### Date Range Filtering

Always filter by date range when possible to reduce the result set:

```php
// Good - filtered query
$quotes = $connector->quotes(companyId: 0)->list()
    ->search(Search::make()->column('DateIssued')->between('2024-01-01', '2024-01-31'))
    ->items();

// Less efficient - no date filter
$quotes = $connector->quotes(companyId: 0)->list()->items();
```

### Caching Strategy

Consider caching quote pipeline data:

```php
use Illuminate\Support\Facades\Cache;

// Cache quote pipeline summary for 15 minutes
$pipeline = Cache::remember('simpro.quotes.pipeline', 900, function () use ($connector) {
    $quotes = $connector->quotes(companyId: 0)->list()
        ->where('Status', 'in', ['Draft', 'Sent', 'Pending'])
        ->all();

    $summary = ['count' => 0, 'value' => 0];
    foreach ($quotes as $quote) {
        $summary['count']++;
        $summary['value'] += $quote->total ?? 0;
    }

    return $summary;
});
```

## Error Handling

### Validation Errors

Handle validation errors when creating or updating quotes:

```php
use Simpro\PhpSdk\Simpro\Exceptions\ValidationException;

try {
    $quoteId = $connector->quotes(companyId: 0)->create(data: [
        'Name' => '', // Empty name
        'Customer' => ['ID' => 99999], // Invalid customer
    ]);
} catch (ValidationException $e) {
    $errors = $e->getErrors();

    foreach ($errors as $field => $messages) {
        echo "{$field}: " . implode(', ', $messages) . "\n";
    }
}
```

### Not Found Errors

Handle cases where a quote doesn't exist:

```php
use Saloon\Exceptions\Request\ClientException;

try {
    $quote = $connector->quotes(companyId: 0)->get(quoteId: 99999);
} catch (ClientException $e) {
    if ($e->getResponse()->status() === 404) {
        echo "Quote not found\n";
    } else {
        throw $e;
    }
}
```
