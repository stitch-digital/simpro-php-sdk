# Invoices Resource

The Invoices resource provides full CRUD operations for managing customer invoices in your Simpro environment. Invoices represent billing documents sent to customers for completed work or services.

## Listing Invoices

### Basic List

Returns all invoices with pagination support. The list returns `InvoiceListItem` objects with limited fields:

```php
$invoices = $connector->invoices(companyId: 0)->list();

foreach ($invoices->items() as $invoice) {
    $customerName = $invoice->customer?->companyName ?? 'Unknown';
    $total = $invoice->total?->incTax ?? 0;
    echo "{$invoice->id}: {$customerName} - \${$total}\n";
}
```

**Note:** For full invoice details (invoice number, dates, status, etc.), use `get()` to retrieve individual invoices by ID.

### Filtering Invoices

Use the fluent search API or array-based filters:

#### Fluent Search API (Recommended)

```php
use Simpro\PhpSdk\Simpro\Query\Search;

// Search by customer
$invoices = $connector->invoices(companyId: 0)->list()
    ->where('Customer.ID', '=', 456)
    ->items();

// Filter by paid status
$invoices = $connector->invoices(companyId: 0)->list()
    ->where('IsPaid', '=', false)
    ->items();

// Find invoices within a date range
$invoices = $connector->invoices(companyId: 0)->list()
    ->search(Search::make()->column('DateIssued')->between('2024-01-01', '2024-01-31'))
    ->orderByDesc('DateIssued')
    ->items();

// Filter by type
$invoices = $connector->invoices(companyId: 0)->list()
    ->where('Type', '=', 'TaxInvoice')
    ->items();
```

**Note:** Advanced filtering by invoice number, status, or date comparisons may require fetching individual invoices with `get()` and filtering client-side.

#### Array-Based Syntax

```php
// Filter by customer ID
$invoices = $connector->invoices(companyId: 0)->list(['Customer.ID' => 456]);

// Filter by type
$invoices = $connector->invoices(companyId: 0)->list(['Type' => 'TaxInvoice']);

// Order by date issued descending
$invoices = $connector->invoices(companyId: 0)->list(['orderby' => '-DateIssued']);
```

### Ordering Results

```php
// Order by date issued (newest first)
$invoices = $connector->invoices(companyId: 0)->list()
    ->orderByDesc('DateIssued')
    ->items();

// Order by amount due (highest first)
$invoices = $connector->invoices(companyId: 0)->list()
    ->orderByDesc('AmountDue')
    ->items();

// Order by due date
$invoices = $connector->invoices(companyId: 0)->list()
    ->orderBy('DateDue')
    ->items();
```

## Getting a Single Invoice

### Get by ID

Returns complete information for a specific invoice:

```php
$invoice = $connector->invoices(companyId: 0)->get(invoiceId: 123);

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
$invoice = $connector->invoices(companyId: 0)->get(invoiceId: 123, columns: ['ID', 'InvoiceNo', 'Status', 'Total']);
```

## Creating an Invoice

Create a new invoice:

```php
$invoiceId = $connector->invoices(companyId: 0)->create(data: [
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
$invoiceId = $connector->invoices(companyId: 0)->create(data: [
    'Job' => ['ID' => 789],
    'Customer' => ['ID' => 456],
    'DateIssued' => date('Y-m-d'),
    'DateDue' => date('Y-m-d', strtotime('+30 days')),
]);
```

## Updating an Invoice

Update an existing invoice:

```php
$response = $connector->invoices(companyId: 0)->update(invoiceId: 123, data: [
    'DateDue' => '2024-03-15',
    'Description' => 'Updated description',
]);

if ($response->successful()) {
    echo "Invoice updated successfully\n";
}
```

### Update Invoice Status

```php
$response = $connector->invoices(companyId: 0)->update(invoiceId: 123, data: [
    'Status' => 'Sent',
]);
```

## Deleting an Invoice

Delete an invoice:

```php
$response = $connector->invoices(companyId: 0)->delete(invoiceId: 123);

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
| `type` | `string` | Invoice type (e.g., "TaxInvoice") |
| `customer` | `?InvoiceListCustomer` | Customer object with id, companyName |
| `jobs` | `array<InvoiceListJob>` | Associated jobs |
| `total` | `?InvoiceTotal` | Total object with exTax, tax, incTax |
| `isPaid` | `bool` | Whether invoice is fully paid |

**Note:** For full invoice details (invoice number, dates, status, amount due), use `get()` to retrieve the complete invoice.

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

### List Unpaid Invoices

```php
$invoices = $connector->invoices(companyId: 0)->list()
    ->where('IsPaid', '=', false)
    ->orderByDesc('DateIssued')
    ->all();

foreach ($invoices as $invoice) {
    $customerName = $invoice->customer?->companyName ?? 'Unknown';
    $total = $invoice->total?->incTax ?? 0;
    echo "{$invoice->id}: {$customerName} - \${$total}\n";
}
```

### Find Overdue Invoices

```php
// Note: Date comparison operators may not be supported, so we filter client-side
// Get unpaid invoices and check due dates individually
$today = new DateTimeImmutable();

$unpaidInvoices = $connector->invoices(companyId: 0)->list()
    ->where('IsPaid', '=', false)
    ->all();

$totalOverdue = 0;
foreach ($unpaidInvoices as $invoice) {
    // Get full invoice details to access dateDue and amountDue
    $full = $connector->invoices(companyId: 0)->get(invoiceId: $invoice->id);

    if ($full->dateDue !== null && $full->dateDue < $today) {
        $daysOverdue = $today->diff($full->dateDue)->days;
        $amountDue = $full->totals?->total ?? 0;
        echo "{$full->invoiceNo}: \${$amountDue} - {$daysOverdue} days overdue\n";
        $totalOverdue += $amountDue;
    }
}

echo "Total overdue: \${$totalOverdue}\n";
```

### Get Invoice Summary for Customer

```php
$customerId = 456;

$invoices = $connector->invoices(companyId: 0)->list()
    ->where('Customer.ID', '=', $customerId)
    ->orderByDesc('DateIssued')
    ->all();

$summary = [
    'totalInvoices' => count($invoices),
    'totalBilled' => 0,
    'totalPaid' => 0,
    'invoices' => [],
];

foreach ($invoices as $invoice) {
    $total = $invoice->total?->incTax ?? 0;
    $summary['totalBilled'] += $total;
    if ($invoice->isPaid) {
        $summary['totalPaid'] += $total;
    }

    $summary['invoices'][] = [
        'id' => $invoice->id,
        'type' => $invoice->type,
        'total' => $total,
        'isPaid' => $invoice->isPaid,
    ];
}

return $summary;
```

### Get Invoice with Full Details

```php
$invoiceId = 123;
$invoice = $connector->invoices(companyId: 0)->get(invoiceId: $invoiceId);

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

$invoices = $connector->invoices(companyId: 0)->list()
    ->search(Search::make()->column('DateIssued')->between($startDate, $endDate))
    ->all();

$revenue = [
    'count' => count($invoices),
    'subtotal' => 0,
    'tax' => 0,
    'total' => 0,
];

foreach ($invoices as $invoice) {
    // Total is an InvoiceTotal object with exTax, tax, incTax properties
    $revenue['subtotal'] += $invoice->total?->exTax ?? 0;
    $revenue['tax'] += $invoice->total?->tax ?? 0;
    $revenue['total'] += $invoice->total?->incTax ?? 0;
}

echo "Revenue for {$startDate} to {$endDate}:\n";
echo "  Invoices: {$revenue['count']}\n";
echo "  Subtotal: \$" . number_format($revenue['subtotal'], 2) . "\n";
echo "  Tax: \$" . number_format($revenue['tax'], 2) . "\n";
echo "  Total: \$" . number_format($revenue['total'], 2) . "\n";
```

### Aging Report

```php
$today = new DateTimeImmutable();

// Get unpaid invoices
$invoices = $connector->invoices(companyId: 0)->list()
    ->where('IsPaid', '=', false)
    ->all();

$aging = [
    'current' => 0,      // Not yet due
    '1-30' => 0,         // 1-30 days overdue
    '31-60' => 0,        // 31-60 days overdue
    '61-90' => 0,        // 61-90 days overdue
    '90+' => 0,          // Over 90 days
];

foreach ($invoices as $invoice) {
    // Get full invoice to access dateDue
    $full = $connector->invoices(companyId: 0)->get(invoiceId: $invoice->id);

    if ($full->dateDue === null) {
        continue;
    }

    $amountDue = $full->totals?->total ?? 0;
    $daysOverdue = $today->diff($full->dateDue)->days;
    $isOverdue = $full->dateDue < $today;

    if (!$isOverdue) {
        $aging['current'] += $amountDue;
    } elseif ($daysOverdue <= 30) {
        $aging['1-30'] += $amountDue;
    } elseif ($daysOverdue <= 60) {
        $aging['31-60'] += $amountDue;
    } elseif ($daysOverdue <= 90) {
        $aging['61-90'] += $amountDue;
    } else {
        $aging['90+'] += $amountDue;
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

$invoices = $connector->invoices(companyId: 0)->list()
    ->search(Search::make()->column('DateIssued')->between($startDate, $endDate))
    ->orderBy('DateIssued')
    ->all();

$csv = "ID,Customer,Type,Total,Is Paid\n";
foreach ($invoices as $invoice) {
    $csv .= implode(',', [
        $invoice->id,
        '"' . str_replace('"', '""', $invoice->customer?->companyName ?? '') . '"',
        '"' . ($invoice->type ?? '') . '"',
        $invoice->total?->incTax ?? 0,
        $invoice->isPaid ? 'Yes' : 'No',
    ]) . "\n";
}

file_put_contents('invoices_export.csv', $csv);
echo "Exported " . count($invoices) . " invoices\n";

// For detailed export with invoice numbers and dates, use get() for each invoice
```

### Send Payment Reminder Logic

```php
$reminderDays = 7; // Days before due to send reminder
$reminderDate = new DateTimeImmutable("+{$reminderDays} days");

// Get unpaid invoices and filter by due date client-side
$invoices = $connector->invoices(companyId: 0)->list()
    ->where('IsPaid', '=', false)
    ->all();

foreach ($invoices as $invoice) {
    // Get full invoice details to check due date
    $full = $connector->invoices(companyId: 0)->get(invoiceId: $invoice->id);

    // Check if due date matches our reminder date
    if ($full->dateDue?->format('Y-m-d') !== $reminderDate->format('Y-m-d')) {
        continue;
    }

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
$builder = $connector->invoices(companyId: 0)->list();

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
$invoices = $connector->invoices(companyId: 0)->list()
    ->search(Search::make()->column('DateIssued')->between('2024-01-01', '2024-01-31'))
    ->items();

// Less efficient - no date filter
$invoices = $connector->invoices(companyId: 0)->list()->items();
```

### Caching Strategy

Consider caching invoice summaries:

```php
use Illuminate\Support\Facades\Cache;

// Cache unpaid invoice count for 15 minutes
$unpaidCount = Cache::remember('simpro.invoices.unpaid_count', 900, function () use ($connector) {
    return $connector->invoices(companyId: 0)->list()
        ->where('IsPaid', '=', false)
        ->getTotalResults();
});
```

## Error Handling

### Validation Errors

Handle validation errors when creating or updating invoices:

```php
use Simpro\PhpSdk\Simpro\Exceptions\ValidationException;

try {
    $invoiceId = $connector->invoices(companyId: 0)->create(data: [
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
    $invoice = $connector->invoices(companyId: 0)->get(invoiceId: 99999);
} catch (ClientException $e) {
    if ($e->getResponse()->status() === 404) {
        echo "Invoice not found\n";
    } else {
        throw $e;
    }
}
```
