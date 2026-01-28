# Invoices Resource

The Invoices resource provides full CRUD operations for managing customer invoices in your Simpro environment. Invoices represent billing documents sent to customers for completed work or services.

## Listing Invoices

### Basic List

Returns all invoices with pagination support:

```php
$invoices = $connector->invoices(0)->list();

foreach ($invoices->items() as $invoice) {
    echo "{$invoice->invoiceNo}: {$invoice->customer} - \${$invoice->total}\n";
}
```

### Filtering Invoices

Use the fluent search API or array-based filters:

#### Fluent Search API (Recommended)

```php
use Simpro\PhpSdk\Simpro\Query\Search;

// Search by invoice number
$invoices = $connector->invoices(0)->list()
    ->search(Search::make()->column('InvoiceNo')->equals('INV-001234'))
    ->first();

// Search by customer
$invoices = $connector->invoices(0)->list()
    ->where('CustomerID', '=', 456)
    ->items();

// Search by status
$invoices = $connector->invoices(0)->list()
    ->where('Status', '=', 'Pending')
    ->items();

// Find invoices within a date range
$invoices = $connector->invoices(0)->list()
    ->search(Search::make()->column('DateIssued')->between('2024-01-01', '2024-01-31'))
    ->orderByDesc('DateIssued')
    ->items();

// Find overdue invoices
$today = date('Y-m-d');
$invoices = $connector->invoices(0)->list()
    ->search([
        Search::make()->column('DateDue')->lessThan($today),
        Search::make()->column('AmountDue')->greaterThan(0),
    ])
    ->matchAll()
    ->items();
```

#### Array-Based Syntax

```php
// Search by invoice number
$invoices = $connector->invoices(0)->list(['InvoiceNo' => 'INV-001234']);

// Filter by status
$invoices = $connector->invoices(0)->list(['Status' => 'Pending']);

// Order by date issued descending
$invoices = $connector->invoices(0)->list(['orderby' => '-DateIssued']);
```

### Ordering Results

```php
// Order by date issued (newest first)
$invoices = $connector->invoices(0)->list()
    ->orderByDesc('DateIssued')
    ->items();

// Order by amount due (highest first)
$invoices = $connector->invoices(0)->list()
    ->orderByDesc('AmountDue')
    ->items();

// Order by due date
$invoices = $connector->invoices(0)->list()
    ->orderBy('DateDue')
    ->items();
```

## Getting a Single Invoice

### Get by ID

Returns complete information for a specific invoice:

```php
$invoice = $connector->invoices(0)->get(123);

return [
    'id' => $invoice->id,
    'invoiceNo' => $invoice->invoiceNo,
    'status' => $invoice->status,
    'customer' => $invoice->customer?->name,
    'site' => $invoice->site?->name,
    'dateIssued' => $invoice->dateIssued?->format('Y-m-d'),
    'dateDue' => $invoice->dateDue?->format('Y-m-d'),
    'totals' => [
        'subtotal' => $invoice->totals?->subtotal,
        'tax' => $invoice->totals?->tax,
        'total' => $invoice->totals?->total,
    ],
];
```

### Get with Specific Columns

Optionally specify which columns to return:

```php
$invoice = $connector->invoices(0)->get(123, ['ID', 'InvoiceNo', 'Status', 'Total']);
```

## Creating an Invoice

Create a new invoice:

```php
$invoiceId = $connector->invoices(0)->create([
    'Customer' => ['ID' => 456],
    'DateIssued' => '2024-01-15',
    'DateDue' => '2024-02-15',
    'Description' => 'Services rendered for January 2024',
    'OrderNo' => 'PO-12345',
]);

echo "Created invoice with ID: {$invoiceId}\n";
```

### Create Invoice from Job

```php
$invoiceId = $connector->invoices(0)->create([
    'Job' => ['ID' => 789],
    'Customer' => ['ID' => 456],
    'DateIssued' => date('Y-m-d'),
    'DateDue' => date('Y-m-d', strtotime('+30 days')),
]);
```

## Updating an Invoice

Update an existing invoice:

```php
$response = $connector->invoices(0)->update(123, [
    'DateDue' => '2024-03-15',
    'Description' => 'Updated description',
]);

if ($response->successful()) {
    echo "Invoice updated successfully\n";
}
```

### Update Invoice Status

```php
$response = $connector->invoices(0)->update(123, [
    'Status' => 'Sent',
]);
```

## Deleting an Invoice

Delete an invoice:

```php
$response = $connector->invoices(0)->delete(123);

if ($response->successful()) {
    echo "Invoice deleted successfully\n";
}
```

**Note:** Deleting invoices may be restricted based on invoice status and business rules in Simpro.

## Response Structures

### InvoiceListItem (List Response)

Lightweight object returned by `list()`:

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Invoice ID |
| `invoiceNo` | `?string` | Invoice number |
| `status` | `?string` | Invoice status |
| `customer` | `?string` | Customer name |
| `customerId` | `?string` | Customer ID |
| `dateIssued` | `?string` | Date issued (YYYY-MM-DD) |
| `dateDue` | `?string` | Due date (YYYY-MM-DD) |
| `total` | `?float` | Invoice total amount |
| `amountDue` | `?float` | Remaining amount due |

### Invoice (Detailed Response)

Complete invoice object returned by `get()`:

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Invoice ID |
| `invoiceNo` | `?string` | Invoice number |
| `status` | `?string` | Invoice status |
| `customer` | `?InvoiceCustomer` | Customer details |
| `site` | `?InvoiceSite` | Site details |
| `orderNo` | `?string` | Customer order/PO number |
| `description` | `?string` | Invoice description |
| `dateIssued` | `?DateTimeImmutable` | Date issued |
| `dateDue` | `?DateTimeImmutable` | Due date |
| `totals` | `?InvoiceTotals` | Invoice totals breakdown |
| `dateModified` | `?DateTimeImmutable` | Last modification date |

### Nested Objects

**InvoiceCustomer:**
| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Customer ID |
| `name` | `?string` | Customer name |

**InvoiceSite:**
| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Site ID |
| `name` | `?string` | Site name |

**InvoiceTotals:**
| Property | Type | Description |
|----------|------|-------------|
| `subtotal` | `?float` | Subtotal (ex tax) |
| `tax` | `?float` | Tax amount |
| `total` | `?float` | Total (inc tax) |

## Examples

### List Pending Invoices

```php
$invoices = $connector->invoices(0)->list()
    ->where('Status', '=', 'Pending')
    ->orderByDesc('DateIssued')
    ->all();

foreach ($invoices as $invoice) {
    echo "{$invoice->invoiceNo}: {$invoice->customer} - \${$invoice->total}\n";
}
```

### Find Overdue Invoices

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$today = date('Y-m-d');

$overdueInvoices = $connector->invoices(0)->list()
    ->search([
        Search::make()->column('DateDue')->lessThan($today),
        Search::make()->column('AmountDue')->greaterThan(0),
    ])
    ->matchAll()
    ->orderBy('DateDue')
    ->all();

$totalOverdue = 0;
foreach ($overdueInvoices as $invoice) {
    $daysOverdue = (strtotime($today) - strtotime($invoice->dateDue)) / 86400;
    echo "{$invoice->invoiceNo}: \${$invoice->amountDue} - {$daysOverdue} days overdue\n";
    $totalOverdue += $invoice->amountDue;
}

echo "Total overdue: \${$totalOverdue}\n";
```

### Get Invoice Summary for Customer

```php
$customerId = 456;

$invoices = $connector->invoices(0)->list()
    ->where('CustomerID', '=', $customerId)
    ->orderByDesc('DateIssued')
    ->all();

$summary = [
    'totalInvoices' => count($invoices),
    'totalBilled' => 0,
    'totalOutstanding' => 0,
    'invoices' => [],
];

foreach ($invoices as $invoice) {
    $summary['totalBilled'] += $invoice->total ?? 0;
    $summary['totalOutstanding'] += $invoice->amountDue ?? 0;

    $summary['invoices'][] = [
        'invoiceNo' => $invoice->invoiceNo,
        'dateIssued' => $invoice->dateIssued,
        'total' => $invoice->total,
        'amountDue' => $invoice->amountDue,
        'status' => $invoice->status,
    ];
}

return $summary;
```

### Get Invoice with Full Details

```php
$invoiceId = 123;
$invoice = $connector->invoices(0)->get($invoiceId);

$details = [
    'invoice' => [
        'id' => $invoice->id,
        'number' => $invoice->invoiceNo,
        'status' => $invoice->status,
        'orderNo' => $invoice->orderNo,
        'description' => $invoice->description,
    ],
    'dates' => [
        'issued' => $invoice->dateIssued?->format('F j, Y'),
        'due' => $invoice->dateDue?->format('F j, Y'),
        'modified' => $invoice->dateModified?->format('Y-m-d H:i'),
    ],
    'totals' => [
        'subtotal' => number_format($invoice->totals?->subtotal ?? 0, 2),
        'tax' => number_format($invoice->totals?->tax ?? 0, 2),
        'total' => number_format($invoice->totals?->total ?? 0, 2),
    ],
];

if ($invoice->customer !== null) {
    $details['customer'] = [
        'id' => $invoice->customer->id,
        'name' => $invoice->customer->name,
    ];
}

if ($invoice->site !== null) {
    $details['site'] = [
        'id' => $invoice->site->id,
        'name' => $invoice->site->name,
    ];
}

return $details;
```

### Calculate Monthly Revenue

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$year = 2024;
$month = 1;
$startDate = sprintf('%04d-%02d-01', $year, $month);
$endDate = date('Y-m-t', strtotime($startDate));

$invoices = $connector->invoices(0)->list()
    ->search(Search::make()->column('DateIssued')->between($startDate, $endDate))
    ->all();

$revenue = [
    'count' => count($invoices),
    'subtotal' => 0,
    'tax' => 0,
    'total' => 0,
];

foreach ($invoices as $invoice) {
    $revenue['total'] += $invoice->total ?? 0;
}

echo "Revenue for {$startDate} to {$endDate}:\n";
echo "  Invoices: {$revenue['count']}\n";
echo "  Total: \$" . number_format($revenue['total'], 2) . "\n";
```

### Aging Report

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$today = date('Y-m-d');

$invoices = $connector->invoices(0)->list()
    ->search(Search::make()->column('AmountDue')->greaterThan(0))
    ->all();

$aging = [
    'current' => 0,      // Not yet due
    '1-30' => 0,         // 1-30 days overdue
    '31-60' => 0,        // 31-60 days overdue
    '61-90' => 0,        // 61-90 days overdue
    '90+' => 0,          // Over 90 days
];

foreach ($invoices as $invoice) {
    if ($invoice->dateDue === null || $invoice->amountDue === null) {
        continue;
    }

    $daysOverdue = (strtotime($today) - strtotime($invoice->dateDue)) / 86400;

    if ($daysOverdue <= 0) {
        $aging['current'] += $invoice->amountDue;
    } elseif ($daysOverdue <= 30) {
        $aging['1-30'] += $invoice->amountDue;
    } elseif ($daysOverdue <= 60) {
        $aging['31-60'] += $invoice->amountDue;
    } elseif ($daysOverdue <= 90) {
        $aging['61-90'] += $invoice->amountDue;
    } else {
        $aging['90+'] += $invoice->amountDue;
    }
}

echo "Accounts Receivable Aging:\n";
foreach ($aging as $period => $amount) {
    echo "  {$period}: \$" . number_format($amount, 2) . "\n";
}
```

### Export Invoices to CSV

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$startDate = '2024-01-01';
$endDate = '2024-12-31';

$invoices = $connector->invoices(0)->list()
    ->search(Search::make()->column('DateIssued')->between($startDate, $endDate))
    ->orderBy('DateIssued')
    ->all();

$csv = "Invoice No,Customer,Date Issued,Due Date,Total,Amount Due,Status\n";
foreach ($invoices as $invoice) {
    $csv .= implode(',', [
        '"' . ($invoice->invoiceNo ?? '') . '"',
        '"' . str_replace('"', '""', $invoice->customer ?? '') . '"',
        $invoice->dateIssued ?? '',
        $invoice->dateDue ?? '',
        $invoice->total ?? 0,
        $invoice->amountDue ?? 0,
        '"' . ($invoice->status ?? '') . '"',
    ]) . "\n";
}

file_put_contents('invoices_export.csv', $csv);
echo "Exported " . count($invoices) . " invoices\n";
```

### Send Payment Reminder Logic

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$reminderDays = 7; // Days before due to send reminder
$reminderDate = date('Y-m-d', strtotime("+{$reminderDays} days"));

$invoices = $connector->invoices(0)->list()
    ->search([
        Search::make()->column('DateDue')->equals($reminderDate),
        Search::make()->column('AmountDue')->greaterThan(0),
        Search::make()->column('Status')->equals('Sent'),
    ])
    ->matchAll()
    ->all();

foreach ($invoices as $invoice) {
    // Get full invoice details
    $full = $connector->invoices(0)->get($invoice->id);

    // Prepare reminder data
    $reminderData = [
        'invoiceNo' => $full->invoiceNo,
        'customerName' => $full->customer?->name,
        'amountDue' => $full->totals?->total,
        'dueDate' => $full->dateDue?->format('F j, Y'),
    ];

    // Send reminder (implement your notification logic)
    // sendPaymentReminder($reminderData);

    echo "Reminder queued for invoice {$full->invoiceNo}\n";
}
```

## Performance Considerations

### Pagination

Invoice lists are paginated automatically. The default page size is 30:

```php
$builder = $connector->invoices(0)->list();

// Change page size if needed
$builder->getPaginator()->setPerPageLimit(100);

// Iterate automatically handles pagination
foreach ($builder->items() as $invoice) {
    // Processes all invoices across all pages
}
```

### Date Range Filtering

Always filter by date range when possible to reduce the result set:

```php
// Good - filtered query
$invoices = $connector->invoices(0)->list()
    ->search(Search::make()->column('DateIssued')->between('2024-01-01', '2024-01-31'))
    ->items();

// Less efficient - no date filter
$invoices = $connector->invoices(0)->list()->items();
```

### Caching Strategy

Consider caching invoice summaries:

```php
use Illuminate\Support\Facades\Cache;

// Cache overdue invoice count for 15 minutes
$overdueCount = Cache::remember('simpro.invoices.overdue_count', 900, function () use ($connector) {
    $today = date('Y-m-d');
    return $connector->invoices(0)->list()
        ->search([
            Search::make()->column('DateDue')->lessThan($today),
            Search::make()->column('AmountDue')->greaterThan(0),
        ])
        ->matchAll()
        ->getTotalResults();
});
```

## Error Handling

### Validation Errors

Handle validation errors when creating or updating invoices:

```php
use Simpro\PhpSdk\Simpro\Exceptions\ValidationException;

try {
    $invoiceId = $connector->invoices(0)->create([
        'Customer' => ['ID' => 99999], // Invalid customer
        'DateIssued' => '2024-01-15',
    ]);
} catch (ValidationException $e) {
    $errors = $e->getErrors();

    foreach ($errors as $field => $messages) {
        echo "{$field}: " . implode(', ', $messages) . "\n";
    }
}
```

### Not Found Errors

Handle cases where an invoice doesn't exist:

```php
use Saloon\Exceptions\Request\ClientException;

try {
    $invoice = $connector->invoices(0)->get(99999);
} catch (ClientException $e) {
    if ($e->getResponse()->status() === 404) {
        echo "Invoice not found\n";
    } else {
        throw $e;
    }
}
```
