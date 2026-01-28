# Customers Resource

The Customers resource provides operations for managing customers in your Simpro environment. Customers can be either companies (businesses) or individuals. The SDK currently supports company customers with full CRUD operations.

## Listing Customers

### List All Customers

Returns all customers (both companies and individuals) with pagination support:

```php
$customers = $connector->customers(0)->list();

foreach ($customers->items() as $customer) {
    echo "{$customer->id}: {$customer->companyName} ({$customer->type})\n";
}
```

### List Company Customers Only

Returns only company-type customers:

```php
$companies = $connector->customers(0)->listCompanies();

foreach ($companies->items() as $customer) {
    echo "{$customer->id}: {$customer->companyName}\n";
}
```

### Filtering Customers

Use the fluent search API or array-based filters:

#### Fluent Search API (Recommended)

```php
use Simpro\PhpSdk\Simpro\Query\Search;

// Search by company name
$customers = $connector->customers(0)->list()
    ->search(Search::make()->column('CompanyName')->find('Acme'))
    ->items();

// Search by email
$customers = $connector->customers(0)->list()
    ->where('Email', 'like', '@acme.com')
    ->items();

// Search by phone
$customers = $connector->customers(0)->list()
    ->where('Phone', 'like', '07')
    ->items();

// Multiple criteria
$customers = $connector->customers(0)->list()
    ->search([
        Search::make()->column('CompanyName')->find('Corp'),
        Search::make()->column('IsArchived')->equals(false),
    ])
    ->matchAll()
    ->orderBy('CompanyName')
    ->items();

// Find active customers only
$customers = $connector->customers(0)->list()
    ->where('IsArchived', '=', false)
    ->items();

// Search company customers specifically
$customers = $connector->customers(0)->listCompanies()
    ->search(Search::make()->column('CompanyName')->startsWith('Acme'))
    ->items();
```

#### Array-Based Syntax

```php
// Search by company name
$customers = $connector->customers(0)->list(['CompanyName' => '%25Acme%25']);

// Filter by type
$customers = $connector->customers(0)->list(['Type' => 'Company']);

// Order by name
$customers = $connector->customers(0)->list(['orderby' => 'CompanyName']);

// Multiple filters
$customers = $connector->customers(0)->list([
    'CompanyName' => '%25Corp%25',
    'IsArchived' => 'false',
]);
```

### Ordering Results

```php
// Order by company name ascending
$customers = $connector->customers(0)->list()
    ->orderBy('CompanyName')
    ->items();

// Order by company name descending
$customers = $connector->customers(0)->list()
    ->orderByDesc('CompanyName')
    ->items();

// Order by ID
$customers = $connector->customers(0)->list()
    ->orderBy('ID')
    ->items();
```

## Getting a Single Customer

### Get Company Customer by ID

Returns complete information for a specific company customer:

```php
$customer = $connector->customers(0)->getCompany(123);

return [
    'id' => $customer->id,
    'companyName' => $customer->companyName,
    'type' => $customer->type,
    'email' => $customer->email,
    'phone' => $customer->phone,
    'altPhone' => $customer->altPhone,
    'fax' => $customer->fax,
    'abn' => $customer->abn,
    'website' => $customer->website,
    'address' => $customer->address,
    'isArchived' => $customer->isArchived,
];
```

### Get with Specific Columns

Optionally specify which columns to return:

```php
$customer = $connector->customers(0)->getCompany(123, ['ID', 'CompanyName', 'Email', 'Phone']);
```

## Creating a Company Customer

Create a new company customer:

```php
$customerId = $connector->customers(0)->createCompany([
    'CompanyName' => 'Acme Corporation',
    'Email' => 'info@acme.com',
    'Phone' => '+61 7 1234 5678',
]);

echo "Created customer with ID: {$customerId}\n";
```

### Create with Full Details

```php
$customerId = $connector->customers(0)->createCompany([
    'CompanyName' => 'Acme Corporation',
    'Email' => 'info@acme.com',
    'Phone' => '+61 7 1234 5678',
    'AltPhone' => '+61 7 8765 4321',
    'Fax' => '+61 7 1234 5679',
    'Website' => 'https://www.acme.com',
    'ABN' => '12 345 678 901',
    'Address' => [
        'Address' => '123 Business Street',
        'City' => 'Brisbane',
        'State' => 'QLD',
        'PostalCode' => '4000',
        'Country' => 'Australia',
    ],
]);
```

## Updating a Company Customer

Update an existing company customer:

```php
$response = $connector->customers(0)->updateCompany(123, [
    'Phone' => '+61 7 9999 8888',
    'Email' => 'new.email@acme.com',
]);

if ($response->successful()) {
    echo "Customer updated successfully\n";
}
```

### Update Multiple Fields

```php
$response = $connector->customers(0)->updateCompany(123, [
    'CompanyName' => 'Acme Corporation Pty Ltd',
    'Website' => 'https://www.acme-corp.com.au',
    'Address' => [
        'Address' => '456 New Business Ave',
        'City' => 'Sydney',
        'State' => 'NSW',
        'PostalCode' => '2000',
    ],
]);
```

### Archive a Customer

```php
$response = $connector->customers(0)->updateCompany(123, [
    'IsArchived' => true,
]);

echo "Customer archived\n";
```

## Deleting a Company Customer

Delete a company customer:

```php
$response = $connector->customers(0)->deleteCompany(123);

if ($response->successful()) {
    echo "Customer deleted successfully\n";
}
```

**Note:** Consider archiving customers instead of deleting them to preserve historical data and relationships with jobs, quotes, and invoices.

## Response Structures

### CustomerListItem (List Response)

Lightweight object returned by `list()` and `listCompanies()`:

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Customer ID |
| `companyName` | `string` | Company name |
| `type` | `string` | Customer type (Company or Individual) |
| `email` | `?string` | Email address |
| `phone` | `?string` | Phone number |

### Customer (Detailed Response)

Complete customer object returned by `getCompany()`:

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Customer ID |
| `companyName` | `string` | Company name |
| `type` | `string` | Customer type (Company or Individual) |
| `givenName` | `?string` | Given name (for individuals) |
| `familyName` | `?string` | Family name (for individuals) |
| `email` | `?string` | Email address |
| `phone` | `?string` | Phone number |
| `altPhone` | `?string` | Alternative phone number |
| `fax` | `?string` | Fax number |
| `address` | `?CustomerAddress` | Address details |
| `abn` | `?string` | ABN / EIN / VAT number |
| `website` | `?string` | Website URL |
| `isArchived` | `?bool` | Whether customer is archived |
| `dateModified` | `?DateTimeImmutable` | Last modification date |

### Nested Objects

**CustomerAddress:**
| Property | Type | Description |
|----------|------|-------------|
| `address` | `?string` | Street address |
| `city` | `?string` | City |
| `state` | `?string` | State/province |
| `postalCode` | `?string` | Postal/ZIP code |
| `country` | `?string` | Country |

## Examples

### List All Active Customers

```php
$customers = $connector->customers(0)->list()
    ->where('IsArchived', '=', false)
    ->orderBy('CompanyName')
    ->all();

foreach ($customers as $customer) {
    echo "{$customer->companyName} - {$customer->email}\n";
}
```

### Build Customer Dropdown

```php
$customers = $connector->customers(0)->listCompanies()
    ->where('IsArchived', '=', false)
    ->orderBy('CompanyName')
    ->all();

$options = [];
foreach ($customers as $customer) {
    $options[] = [
        'value' => $customer->id,
        'label' => $customer->companyName,
    ];
}

return $options;
```

### Search Customers by Name

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$searchTerm = 'Acme';

$customers = $connector->customers(0)->list()
    ->search(Search::make()->column('CompanyName')->find($searchTerm))
    ->all();

if (count($customers) === 0) {
    echo "No customers found matching '{$searchTerm}'\n";
} else {
    foreach ($customers as $customer) {
        echo "{$customer->id}: {$customer->companyName}\n";
    }
}
```

### Get Customer with Contact Details

```php
$customer = $connector->customers(0)->getCompany(123);

$contactInfo = [
    'name' => $customer->companyName,
    'contacts' => [
        'email' => $customer->email,
        'phone' => $customer->phone,
        'altPhone' => $customer->altPhone,
        'fax' => $customer->fax,
        'website' => $customer->website,
    ],
];

if ($customer->address !== null) {
    $contactInfo['address'] = [
        'street' => $customer->address->address,
        'city' => $customer->address->city,
        'state' => $customer->address->state,
        'postalCode' => $customer->address->postalCode,
        'country' => $customer->address->country,
    ];
}

return $contactInfo;
```

### Create Customer with Validation

```php
function createCustomer(array $data): int
{
    // Validate required fields
    if (empty($data['CompanyName'])) {
        throw new InvalidArgumentException("Company name is required");
    }

    // Validate email format if provided
    if (!empty($data['Email']) && !filter_var($data['Email'], FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException("Invalid email format");
    }

    return $this->connector->customers(0)->createCompany($data);
}

// Usage
try {
    $customerId = createCustomer([
        'CompanyName' => 'New Customer Ltd',
        'Email' => 'contact@newcustomer.com',
        'Phone' => '+61 7 1234 5678',
    ]);
    echo "Created customer: {$customerId}\n";
} catch (InvalidArgumentException $e) {
    echo "Validation error: {$e->getMessage()}\n";
}
```

### Find Customers by Location

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$city = 'Brisbane';

$customers = $connector->customers(0)->listCompanies()
    ->where('Address.City', 'like', $city)
    ->orderBy('CompanyName')
    ->all();

echo "Customers in {$city}:\n";
foreach ($customers as $customer) {
    $full = $connector->customers(0)->getCompany($customer->id);
    $address = $full->address;

    echo "  {$full->companyName}\n";
    if ($address) {
        echo "    {$address->address}, {$address->city}\n";
    }
}
```

### Bulk Update Customer Status

Archive multiple customers:

```php
$customerIds = [101, 102, 103];

foreach ($customerIds as $id) {
    try {
        $connector->customers(0)->updateCompany($id, [
            'IsArchived' => true,
        ]);
        echo "Archived customer {$id}\n";
    } catch (Exception $e) {
        echo "Failed to archive customer {$id}: {$e->getMessage()}\n";
    }
}
```

### Export Customers to Array

```php
$customers = $connector->customers(0)->listCompanies()
    ->where('IsArchived', '=', false)
    ->orderBy('CompanyName')
    ->all();

$export = [];
foreach ($customers as $customer) {
    // Get full details for each customer
    $full = $connector->customers(0)->getCompany($customer->id);

    $export[] = [
        'ID' => $full->id,
        'CompanyName' => $full->companyName,
        'Email' => $full->email,
        'Phone' => $full->phone,
        'AltPhone' => $full->altPhone,
        'Website' => $full->website,
        'ABN' => $full->abn,
        'City' => $full->address?->city,
        'State' => $full->address?->state,
        'IsArchived' => $full->isArchived ? 'Yes' : 'No',
    ];
}

return $export;
```

### Find Customers Without Email

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$customers = $connector->customers(0)->listCompanies()
    ->where('IsArchived', '=', false)
    ->all();

$missingEmail = [];
foreach ($customers as $customer) {
    if (empty($customer->email)) {
        $missingEmail[] = [
            'id' => $customer->id,
            'name' => $customer->companyName,
            'phone' => $customer->phone,
        ];
    }
}

echo "Customers missing email addresses:\n";
foreach ($missingEmail as $customer) {
    echo "  {$customer['id']}: {$customer['name']}\n";
}
```

### Customer Summary Dashboard

```php
$allCustomers = $connector->customers(0)->list()->all();

$summary = [
    'total' => count($allCustomers),
    'companies' => 0,
    'individuals' => 0,
    'active' => 0,
    'archived' => 0,
];

foreach ($allCustomers as $customer) {
    if ($customer->type === 'Company') {
        $summary['companies']++;
    } else {
        $summary['individuals']++;
    }

    // Note: isArchived not available in list response
    // Would need to fetch full details to check
}

echo "Customer Summary:\n";
echo "  Total: {$summary['total']}\n";
echo "  Companies: {$summary['companies']}\n";
echo "  Individuals: {$summary['individuals']}\n";
```

## Performance Considerations

### list() vs listCompanies()

- `list()` returns all customers (companies and individuals)
- `listCompanies()` returns only company customers

Use `listCompanies()` when you specifically need company customers to reduce the result set.

### Pagination

Customer lists are paginated automatically. The default page size is 30:

```php
$builder = $connector->customers(0)->list();

// Change page size if needed
$builder->getPaginator()->setPerPageLimit(100);

// Iterate automatically handles pagination
foreach ($builder->items() as $customer) {
    // Processes all customers across all pages
}
```

### Avoiding N+1 Queries

When you need full details for multiple customers, consider the performance trade-off:

```php
// Option 1: Multiple detailed requests (more data, more requests)
$customers = $connector->customers(0)->listCompanies()->all();
foreach ($customers as $customer) {
    $full = $connector->customers(0)->getCompany($customer->id); // N+1 requests
}

// Option 2: Use list data when sufficient
$customers = $connector->customers(0)->listCompanies()->all();
foreach ($customers as $customer) {
    // Use list data directly when you only need basic info
    echo "{$customer->companyName}: {$customer->email}\n";
}
```

### Caching Strategy

Consider caching customer data for dropdown lists:

```php
use Illuminate\Support\Facades\Cache;

// Cache customer list for 15 minutes
$customers = Cache::remember('simpro.customers.active', 900, function () use ($connector) {
    return $connector->customers(0)->listCompanies()
        ->where('IsArchived', '=', false)
        ->orderBy('CompanyName')
        ->all();
});
```

## Error Handling

### Validation Errors

Handle validation errors when creating or updating customers:

```php
use Simpro\PhpSdk\Simpro\Exceptions\ValidationException;

try {
    $customerId = $connector->customers(0)->createCompany([
        'CompanyName' => '', // Empty name
        'Email' => 'invalid-email', // Invalid format
    ]);
} catch (ValidationException $e) {
    // Get all validation errors
    $errors = $e->getErrors();

    foreach ($errors as $field => $messages) {
        echo "{$field}: " . implode(', ', $messages) . "\n";
    }
}
```

### Not Found Errors

Handle cases where a customer doesn't exist:

```php
use Saloon\Exceptions\Request\ClientException;

try {
    $customer = $connector->customers(0)->getCompany(99999);
} catch (ClientException $e) {
    if ($e->getResponse()->status() === 404) {
        echo "Customer not found\n";
    } else {
        throw $e;
    }
}
```

## Future Enhancements

The following features are planned for future SDK releases:

- **Individual Customers**: `createIndividual()`, `getIndividual()`, `updateIndividual()`, `deleteIndividual()`
- **Customer Contacts**: Managing contacts associated with company customers
- **Customer Contracts**: Service contracts, SLAs, and recurring billing
- **Customer Sites**: Sites/locations associated with customers
