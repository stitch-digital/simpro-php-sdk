# Companies Resource

The Companies resource provides access to company information in your Simpro environment. Companies are fundamental to Simpro - most other API resources require a company ID context. Use the Info resource's `multiCompany()` method to check if multi-company mode is enabled in your environment.

## Listing Companies

### Simple List (Lightweight)

Returns basic company information (ID and Name only). This is the most efficient method when you just need company identifiers:

```php
$companies = $connector->companies()->list();

foreach ($companies->items() as $company) {
    // $company is CompanyListItem with id and name properties
    echo "{$company->id}: {$company->name}\n";
}
```

**When to use:**
- Building dropdown/select lists
- Quick lookups by ID or name
- Performance is critical (minimal data transfer)
- You'll fetch full details later with `get()` for specific companies

### Detailed List (Complete Data)

Returns complete company information (all 30+ fields) for all companies. This is efficient when you need full data for multiple companies without making individual `get()` calls:

```php
$companies = $connector->companies()->listDetailed();

foreach ($companies->items() as $company) {
    // $company is full Company object with all fields
    return [
        'id' => $company->id,
        'name' => $company->name,
        'email' => $company->email,
        'phone' => $company->phone,
        'timezone' => $company->timezone,
        'currency' => $company->currency,
        'address' => [
            'line1' => $company->address->line1,
            'line2' => $company->address->line2,
        ],
    ];
}
```

**When to use:**
- Exporting company data
- Displaying comprehensive company information
- Avoiding N+1 query pattern (one list + N individual gets)
- Number of companies is small enough for the payload

### Searching and Filtering

The SDK provides two ways to search and filter companies: a **fluent search API** (recommended) and an **array-based syntax** (backward compatible).

#### Fluent Search API (Recommended)

The fluent search API provides a clean, type-safe way to build search queries:

```php
use Simpro\PhpSdk\Simpro\Query\Search;

// Simple wildcard search
$companies = $connector->companies()->listDetailed()
    ->search(Search::make()->column('Name')->find('Test'))
    ->first();

// Multiple search criteria
$companies = $connector->companies()->list()
    ->search([
        Search::make()->column('Name')->find('Corp'),
        Search::make()->column('ID')->greaterThan(5),
    ])
    ->matchAll()  // All criteria must match (default)
    ->orderByDesc('Name')
    ->collect();

// Using OR logic (any criterion can match)
$companies = $connector->companies()->list()
    ->search([
        Search::make()->column('Name')->equals('Company One'),
        Search::make()->column('Address.Line2')->find('Sydney'),
    ])
    ->matchAny()
    ->items();

// Alternative where() syntax
$companies = $connector->companies()->list()
    ->where('Name', 'like', 'Acme')
    ->where('ID', '>=', 10)
    ->where('Address.Line2', 'like', 'Brisbane')
    ->orderBy('Name')
    ->first();

// Range and list searches
$companies = $connector->companies()->list()
    ->search(Search::make()->column('ID')->between(1, 100))
    ->items();

$companies = $connector->companies()->list()
    ->search(Search::make()->column('ID')->in([1, 2, 3, 5, 8]))
    ->items();
```

**Search Methods:**

| Method | Description | Example |
|--------|-------------|---------|
| `equals($value)` | Exact match | `->equals('Test')` |
| `find($value)` | Contains (wildcard) | `->find('Test')` |
| `startsWith($value)` | Starts with | `->startsWith('Acme')` |
| `endsWith($value)` | Ends with | `->endsWith('Corp')` |
| `lessThan($value)` | Less than | `->lessThan(10)` |
| `lessThanOrEqual($value)` | Less than or equal | `->lessThanOrEqual(10)` |
| `greaterThan($value)` | Greater than | `->greaterThan(5)` |
| `greaterThanOrEqual($value)` | Greater than or equal | `->greaterThanOrEqual(5)` |
| `notEqual($value)` | Not equal | `->notEqual('Cancelled')` |
| `between($min, $max)` | Range | `->between(1, 100)` |
| `in($array)` | In list | `->in([1, 2, 3])` |
| `notIn($array)` | Not in list | `->notIn(['Deleted'])` |

**where() Operators:**

| Operator | Description |
|----------|-------------|
| `=`, `==` | Equals |
| `!=` | Not equal |
| `<`, `<=`, `>`, `>=` | Comparisons |
| `like` | Contains (wildcard) |
| `starts with` | Starts with |
| `ends with` | Ends with |
| `in` | In array |
| `not in` | Not in array |
| `between` | Range (pass `[$min, $max]`) |

#### Array-Based Syntax (Backward Compatible)

Both `list()` and `listDetailed()` also accept filter parameters as an array. Simpro's API uses field-specific search where you pass the field name as the parameter key:

```php
// Search by name (exact match)
$companies = $connector->companies()->list(['Name' => 'Company One']);

// Wildcard search (% = any characters, URL-encoded as %25)
$companies = $connector->companies()->list(['Name' => 'Comp%25']);

// Search in nested fields using dot notation
$companies = $connector->companies()->list(['Address.Line2' => '%25NSW%25']);

// Multiple field search
$companies = $connector->companies()->list([
    'Name' => 'Company%25',
    'Address.Line2' => '%25NSW%25',
    'search' => 'all',  // ALL fields must match (default)
]);

// Multiple field search with OR logic
$companies = $connector->companies()->list([
    'Name' => 'Company One',
    'Address.Line2' => 'Sydney',
    'search' => 'any',  // ANY field must match
]);

// Using operators
$companies = $connector->companies()->list([
    'ID' => 'gt(5)',  // Greater than 5
]);

// Ordering results
$companies = $connector->companies()->list([
    'orderby' => 'Name',    // Ascending
]);

$companies = $connector->companies()->list([
    'orderby' => '-Name',   // Descending (prefix with -)
]);
```

**Available Operators:**
- `lt(value)` - Less than
- `le(value)` - Less than or equal to
- `gt(value)` - Greater than
- `ge(value)` - Greater than or equal to
- `ne(value)` - Not equal to (use `ne()` for null)
- `between(min,max)` - Range of values
- `in(val1,val2,...)` - Match any value in list
- `!in(val1,val2,...)` - Exclude values in list

**Important Notes:**
- **Wildcards**: The `%` character is used for wildcards but must be URL-encoded as `%25`
- **Spaces**: Spaces in values are automatically URL-encoded by the SDK
- **Search parameter**: Controls matching logic - `'search' => 'all'` requires ALL fields to match (default), `'search' => 'any'` requires ANY field to match

## Getting a Single Company

### Get by ID

Returns complete information for a specific company:

```php
$company = $connector->companies()->get(0);

return [
    'name' => $company->name,
    'email' => $company->email,
    'phone' => $company->phone,
    'timezone' => $company->timezone,
    'defaultLanguage' => $company->defaultLanguage->value,
];
```

### Get Default Company

Convenience method to get the default company (ID = 0), which is the primary company in single-company environments:

```php
$defaultCompany = $connector->companies()->getDefault();
```

### Get with Specific Columns

Optionally specify which columns to return:

```php
$company = $connector->companies()->get(0, ['ID', 'Name', 'Email', 'Phone']);
```

## Convenience Methods

### Find by Name

Convenience methods for searching by name. These are wrappers around `list()` and `listDetailed()`:

```php
// Simple list (returns CompanyListItem objects)
// Equivalent to: list(['Name' => 'Acme Corp'])
$companies = $connector->companies()->findByName('Acme Corp');

// Detailed list (returns full Company objects)
// Equivalent to: listDetailed(['Name' => 'Acme Corp'])
$companies = $connector->companies()->findByNameDetailed('Acme Corp');

// For wildcard search, use list() directly
$companies = $connector->companies()->list(['Name' => 'Acme%25']);
```

## Response Structures

### CompanyListItem (Simple List)

Lightweight object returned by `list()` and `findByName()`:

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Company ID |
| `name` | `string` | Company name |

### Company (Detailed)

Complete company object returned by `get()`, `getDefault()`, `listDetailed()`, and `findByNameDetailed()`:

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Company ID |
| `name` | `string` | Company name |
| `phone` | `string` | Phone number |
| `fax` | `string` | Fax number |
| `email` | `string` | Email address |
| `address` | `Address` | Physical address (line1, line2) |
| `billingAddress` | `Address` | Billing address (line1, line2) |
| `ein` | `string` | EIN / ABN / GST Number / VAT Reg Number |
| `companyNo` | `string` | Company Number / ACN |
| `licence` | `string` | Company licence Number / UTR No |
| `website` | `string` | Website URL |
| `banking` | `Banking` | Banking details |
| `cisCertNo` | `string` | CIS Certification Number (UK only) |
| `employerTaxRefNo` | `string` | Employer Tax Reference Number (UK only) |
| `timezone` | `string` | Timezone (e.g., "Australia/Brisbane") |
| `timezoneOffset` | `string` | Timezone offset (e.g., "+10:00") |
| `defaultLanguage` | `DefaultLanguage` | Default language enum |
| `template` | `bool` | Whether this is a template company |
| `multiCompanyLabel` | `?string` | Multi-company label (nullable) |
| `multiCompanyColor` | `?string` | Multi-company color hex code (nullable) |
| `currency` | `string` | Currency code (e.g., "AUD") |
| `country` | `string` | Country name |
| `taxName` | `string` | Tax name (e.g., "GST") |
| `uiDateFormat` | `string` | UI date format |
| `uiTimeFormat` | `string` | UI time format |
| `scheduleFormat` | `ScheduleFormat` | Schedule format enum (15 or 30 minutes) |
| `singleCostCenterMode` | `bool` | Whether single cost center mode is enabled |
| `dateModified` | `DateTimeImmutable` | Last modification date |
| `defaultCostCenter` | `?DefaultCostCenter` | Default cost center (nullable) |

### Nested Objects

**Address:**
- `line1` (string)
- `line2` (string)

**Banking:**
- `bank` (string) - Bank name
- `branchCode` (string) - Branch Code / BIC Code
- `accountName` (string) - Bank account name
- `routingNo` (string) - Routing number / BSB / Bank Code / Sort Code
- `accountNo` (string) - Account number
- `iban` (string) - IBAN (UK only)
- `swiftCode` (string) - Swift code

**DefaultCostCenter:**
- `id` (int) - Cost center ID
- `name` (string) - Cost center name

### Enums

**DefaultLanguage:**
- `EN_AU` = "en_AU"
- `EN_GB` = "en_GB"
- `EN_US` = "en_US"
- `ES_US` = "es_US.utf8"

**ScheduleFormat:**
- `FIFTEEN_MINUTES` = 15
- `THIRTY_MINUTES` = 30

## Examples

### Check Multi-Company and List Companies

```php
// Check if multi-company is enabled
$isMultiCompany = $connector->info()->multiCompany();

if ($isMultiCompany) {
    $companies = $connector->companies()
        ->listDetailed()
        ->collect()
        ->reject(fn ($company) => $company->template) // Exclude the template company
        ->map(fn ($company) => [
            'id' => $company->id,
            'name' => $company->name,
            'email' => $company->email,
            'timezone' => $company->timezone,
        ])
        ->values()
        ->all();

    return $companies;
}

// Single-company environment
$company = $connector->companies()->getDefault();

return [
    'id' => $company->id,
    'name' => $company->name,
    'email' => $company->email,
];
```

### Build Company Selector Dropdown

```php
$options = $connector
  ->companies()
  ->listDetailed()
  ->collect()
  ->reject(fn($company) => $company->template) // Exclude the template company
  ->map(
    fn($company) => [
      "value" => $company->id,
      "label" => $company->name
    ]
  )
  ->values()
  ->all();

return $options;
```

### Search Companies with Wildcards

```php
// Find all companies starting with "Acme"
$companies = $connector->companies()->list(['Name' => 'Acme%25']);

// Find all companies containing "Corp"
$companies = $connector->companies()->list(['Name' => '%25Corp%25']);

// Find companies in specific location
$companies = $connector->companies()->list(['Address.Line2' => '%25Sydney%25']);
```

### Advanced Filtering

```php
// Companies with ID greater than 5
$companies = $connector->companies()->list(['ID' => 'gt(5)']);

// Companies not equal to a specific name
$companies = $connector->companies()->list(['Name' => 'ne(Test Company)']);

// Companies with ID in a specific range
$companies = $connector->companies()->list(['ID' => 'between(1,10)']);

// Multiple filters with OR logic
$companies = $connector->companies()->listDetailed([
    'Name' => '%25Acme%25',
    'Address.Line2' => '%25Brisbane%25',
    'search' => 'any',  // Match ANY condition
]);
```

### Get Complete Company Profile

```php
$company = $connector->companies()->get(0);

return [
    'basic' => [
        'id' => $company->id,
        'name' => $company->name,
        'email' => $company->email,
        'phone' => $company->phone,
        'website' => $company->website,
    ],
    'location' => [
        'address' => "{$company->address->line1}, {$company->address->line2}",
        'country' => $company->country,
        'timezone' => $company->timezone,
        'timezoneOffset' => $company->timezoneOffset,
    ],
    'settings' => [
        'currency' => $company->currency,
        'taxName' => $company->taxName,
        'language' => $company->defaultLanguage->value,
        'scheduleInterval' => $company->scheduleFormat->value . ' minutes',
        'singleCostCenter' => $company->singleCostCenterMode,
    ],
    'banking' => [
        'bank' => $company->banking->bank,
        'accountName' => $company->banking->accountName,
        'accountNo' => $company->banking->accountNo,
    ],
];
```

### Export All Company Data

```php
// Use detailed list to avoid N+1 queries
$companies = $connector->companies()->listDetailed();

$export = [];
foreach ($companies->items() as $company) {
    $export[] = [
        'ID' => $company->id,
        'Name' => $company->name,
        'Email' => $company->email,
        'Phone' => $company->phone,
        'Address' => $company->address->line1 . ', ' . $company->address->line2,
        'Country' => $company->country,
        'Currency' => $company->currency,
        'Timezone' => $company->timezone,
        'Language' => $company->defaultLanguage->value,
        'Last Modified' => $company->dateModified->format('Y-m-d H:i:s'),
    ];
}

return $export;
```

### Working with Multi-Company Labels and Colors

```php
$companies = $connector->companies()->listDetailed();

foreach ($companies->items() as $company) {
    // Multi-company specific fields are nullable
    if ($company->multiCompanyLabel !== null) {
        echo "Label: {$company->multiCompanyLabel}\n";
    }

    if ($company->multiCompanyColor !== null) {
        echo "Color: {$company->multiCompanyColor}\n";
    }
}
```

### Check Default Cost Center

```php
$company = $connector->companies()->get(0);

if ($company->defaultCostCenter !== null) {
    echo "Default Cost Center: {$company->defaultCostCenter->name} (ID: {$company->defaultCostCenter->id})\n";
} else {
    echo "No default cost center configured\n";
}
```

### Real-World Search Example

Search for active companies in a specific location with detailed information:

```php
use Simpro\PhpSdk\Simpro\Query\Search;

// Using fluent API (recommended)
$companies = $connector->companies()->listDetailed()
    ->search([
        Search::make()->column('Name')->find('Construction'),
        Search::make()->column('Address.Line2')->find('Sydney'),
    ])
    ->matchAll()
    ->orderBy('Name')
    ->collect()
    ->filter(fn($company) => !$company->template)
    ->map(fn($company) => [
        'id' => $company->id,
        'name' => $company->name,
        'contact' => [
            'email' => $company->email,
            'phone' => $company->phone,
        ],
        'location' => [
            'address' => "{$company->address->line1}, {$company->address->line2}",
            'timezone' => $company->timezone,
        ],
    ])
    ->values()
    ->all();

// Or using array syntax
$companies = $connector->companies()->listDetailed([
    'Name' => '%25Construction%25',
    'Address.Line2' => '%25Sydney%25',
    'search' => 'all',
    'orderby' => 'Name',
]);

$results = [];
foreach ($companies->items() as $company) {
    if (!$company->template) {
        $results[] = [
            'id' => $company->id,
            'name' => $company->name,
            'contact' => [
                'email' => $company->email,
                'phone' => $company->phone,
            ],
            'location' => [
                'address' => "{$company->address->line1}, {$company->address->line2}",
                'timezone' => $company->timezone,
            ],
        ];
    }
}

return $results;
```

## Performance Considerations

### Simple vs Detailed Lists

The Companies resource provides two list methods with different performance characteristics:

**Simple List (`list()`):**
- Returns only ID and Name (2 fields)
- Minimal payload size (~50-100 bytes per company)
- Fast parsing and iteration
- Ideal for 100+ companies
- Use when you need a quick overview or will fetch details later

**Detailed List (`listDetailed()`):**
- Returns all 30+ fields including nested objects
- Larger payload size (~1-2 KB per company)
- More parsing overhead
- Efficient for avoiding N+1 queries
- Use when you need complete data for all companies upfront

### Pagination

Both list methods return paginated results. The default page size is 30 companies:

```php
$paginator = $connector->companies()->list();

// Change page size if needed
$paginator->setPerPageLimit(100);

// Iterate automatically handles pagination
foreach ($paginator->items() as $company) {
    // Processes all companies across all pages
}
```

### Caching Strategy

Consider caching company data, especially in single-company environments:

```php
use Illuminate\Support\Facades\Cache;

// Cache default company for 1 hour
$company = Cache::remember('simpro.company.default', 3600, function () use ($connector) {
    return $connector->companies()->getDefault();
});

// Cache company list for 30 minutes
$companies = Cache::remember('simpro.companies.list', 1800, function () use ($connector) {
    return $connector->companies()->list()->collect()->all();
});
```
