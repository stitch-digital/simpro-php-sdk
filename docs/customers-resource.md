# Customers Resource

The Customers resource provides operations for managing customers in your Simpro environment. Customers can be either companies (businesses) or individuals. The SDK currently supports company customers with full CRUD operations.

## Listing Customers

### List All Customers

Returns all customers (both companies and individuals) with pagination support. The list returns `CustomerListItem` objects with minimal fields:

```php
$customers = $connector->customers(companyId: 0)->list();

foreach ($customers->items() as $customer) {
    $name = $customer->companyName ?: "{$customer->givenName} {$customer->familyName}";
    echo "{$customer->id}: {$name}\n";
}
```

**Note:** For full customer details (email, phone, address, etc.), use `getCompany()` to retrieve individual customers by ID.

### List Company Customers Only

Returns only company-type customers:

```php
$companies = $connector->customers(companyId: 0)->listCompanies();

foreach ($companies->items() as $customer) {
    echo "{$customer->id}: {$customer->companyName}\n";
}
```

### List Company Customers with Full Details

Returns company customers with all available fields in a single request, avoiding N+1 queries:

```php
$companies = $connector->customers(companyId: 0)->listCompaniesDetailed();

foreach ($companies->items() as $customer) {
    echo "{$customer->id}: {$customer->companyName}\n";
    echo "  Email: {$customer->email}\n";
    echo "  Phone: {$customer->phone}\n";
    echo "  Amount Owing: {$customer->amountOwing}\n";

    if ($customer->address) {
        echo "  City: {$customer->address->city}\n";
    }

    if ($customer->customerType) {
        echo "  Type: {$customer->customerType->name}\n";
    }

    // Access nested arrays
    foreach ($customer->tags ?? [] as $tag) {
        echo "  Tag: {$tag->name}\n";
    }

    foreach ($customer->contacts ?? [] as $contact) {
        echo "  Contact: {$contact->fullName()}\n";
    }
}
```

**Note:** This method requests all available columns from the API, returning `CustomerCompanyListDetailedItem` objects with complete nested data. Use this when you need full customer details for multiple customers to avoid making individual `getCompany()` calls.

### Filtering Customers

Use the fluent search API or array-based filters:

#### Fluent Search API (Recommended)

```php
use Simpro\PhpSdk\Simpro\Query\Search;

// Search by company name
$customers = $connector->customers(companyId: 0)->list()
    ->search(Search::make()->column('CompanyName')->find('Acme'))
    ->items();

// Search by email
$customers = $connector->customers(companyId: 0)->list()
    ->where('Email', 'like', '@acme.com')
    ->items();

// Search by phone
$customers = $connector->customers(companyId: 0)->list()
    ->where('Phone', 'like', '07')
    ->items();

// Multiple criteria
$customers = $connector->customers(companyId: 0)->list()
    ->search([
        Search::make()->column('CompanyName')->find('Corp'),
        Search::make()->column('IsArchived')->equals(false),
    ])
    ->matchAll()
    ->orderBy('CompanyName')
    ->items();

// Find active customers only
$customers = $connector->customers(companyId: 0)->list()
    ->where('IsArchived', '=', false)
    ->items();

// Search company customers specifically
$customers = $connector->customers(companyId: 0)->listCompanies()
    ->search(Search::make()->column('CompanyName')->startsWith('Acme'))
    ->items();
```

#### Array-Based Syntax

```php
// Search by company name
$customers = $connector->customers(companyId: 0)->list(['CompanyName' => '%25Acme%25']);

// Filter by type
$customers = $connector->customers(companyId: 0)->list(['Type' => 'Company']);

// Order by name
$customers = $connector->customers(companyId: 0)->list(['orderby' => 'CompanyName']);

// Multiple filters
$customers = $connector->customers(companyId: 0)->list([
    'CompanyName' => '%25Corp%25',
    'IsArchived' => 'false',
]);
```

### Ordering Results

```php
// Order by company name ascending
$customers = $connector->customers(companyId: 0)->list()
    ->orderBy('CompanyName')
    ->items();

// Order by company name descending
$customers = $connector->customers(companyId: 0)->list()
    ->orderByDesc('CompanyName')
    ->items();

// Order by ID
$customers = $connector->customers(companyId: 0)->list()
    ->orderBy('ID')
    ->items();
```

## Getting a Single Customer

### Get Company Customer by ID

Returns complete information for a specific company customer:

```php
$customer = $connector->customers(companyId: 0)->getCompany(customerId: 123);

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
$customer = $connector->customers(companyId: 0)->getCompany(customerId: 123, columns: ['ID', 'CompanyName', 'Email', 'Phone']);
```

## Creating a Company Customer

Create a new company customer:

```php
$customerId = $connector->customers(companyId: 0)->createCompany(data: [
    'CompanyName' => 'Acme Corporation',
    'Email' => 'info@acme.com',
    'Phone' => '+61 7 1234 5678',
]);

echo "Created customer with ID: {$customerId}\n";
```

### Create with Full Details

```php
$customerId = $connector->customers(companyId: 0)->createCompany(data: [
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
$response = $connector->customers(companyId: 0)->updateCompany(customerId: 123, data: [
    'Phone' => '+61 7 9999 8888',
    'Email' => 'new.email@acme.com',
]);

if ($response->successful()) {
    echo "Customer updated successfully\n";
}
```

### Update Multiple Fields

```php
$response = $connector->customers(companyId: 0)->updateCompany(customerId: 123, data: [
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
$response = $connector->customers(companyId: 0)->updateCompany(customerId: 123, data: [
    'IsArchived' => true,
]);

echo "Customer archived\n";
```

## Deleting a Company Customer

Delete a company customer:

```php
$response = $connector->customers(companyId: 0)->deleteCompany(customerId: 123);

if ($response->successful()) {
    echo "Customer deleted successfully\n";
}
```

**Note:** Consider archiving customers instead of deleting them to preserve historical data and relationships with jobs, quotes, and invoices.

## Response Structures

### CustomerListItem (List Response)

Lightweight object returned by `list()` and `listCompanies()`. Contains only minimal fields:

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Customer ID |
| `companyName` | `string` | Company name |
| `givenName` | `string` | Given name (for individuals) |
| `familyName` | `string` | Family name (for individuals) |

**Note:** For full customer details, use `listCompaniesDetailed()` for bulk retrieval or `getCompany()` for individual customers.

### CustomerCompanyListDetailedItem (Detailed List Response)

Detailed object returned by `listCompaniesDetailed()` with all available columns:

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Customer ID |
| `companyName` | `?string` | Company name |
| `phone` | `?string` | Phone number |
| `email` | `?string` | Email address |
| `href` | `?string` | API resource URL |
| `address` | `?Address` | Physical address |
| `billingAddress` | `?Address` | Billing address |
| `customerType` | `?CustomerType` | Customer type (ID and Name) |
| `tags` | `?array<CustomerTag>` | Customer tags |
| `amountOwing` | `?float` | Amount currently owed |
| `profile` | `?CustomerProfile` | Customer profile (ID and Name) |
| `banking` | `?CustomerBanking` | Banking details (BSB, Account) |
| `archived` | `?bool` | Whether customer is archived |
| `sites` | `?array<CustomerSite>` | Associated sites |
| `contracts` | `?array<CustomerContract>` | Associated contracts |
| `contacts` | `?array<CustomerContact>` | Associated contacts |
| `responseTimes` | `?array<CustomerResponseTime>` | Response time settings |
| `customFields` | `?array<CustomField>` | Custom field values |
| `dateModified` | `?DateTimeImmutable` | Last modification date |
| `dateCreated` | `?DateTimeImmutable` | Creation date |

**Helper Methods:**
- `displayName(): string` - Returns the company name
- `isArchived(): bool` - Returns true if customer is archived
- `hasAmountOwing(): bool` - Returns true if amount owing > 0

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

**Address (used by both Address and BillingAddress):**
| Property | Type | Description |
|----------|------|-------------|
| `address` | `?string` | Street address |
| `city` | `?string` | City |
| `state` | `?string` | State/province |
| `postalCode` | `?string` | Postal/ZIP code |
| `country` | `?string` | Country |

**CustomerType:**
| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Customer type ID |
| `name` | `?string` | Customer type name |

**CustomerTag:**
| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Tag ID |
| `name` | `?string` | Tag name |

**CustomerProfile:**
| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Profile ID |
| `name` | `?string` | Profile name |

**CustomerBanking:**
| Property | Type | Description |
|----------|------|-------------|
| `bsb` | `?string` | BSB code |
| `accountNumber` | `?string` | Account number |
| `accountName` | `?string` | Account name |

**CustomerSite:**
| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Site ID |
| `name` | `?string` | Site name |

**CustomerContract:**
| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Contract ID |
| `name` | `?string` | Contract name |

**CustomerContact:**
| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Contact ID |
| `givenName` | `?string` | Given name |
| `familyName` | `?string` | Family name |
| `email` | `?string` | Email address |
| `phone` | `?string` | Phone number |

Helper method: `fullName(): string` - Returns "GivenName FamilyName"

**CustomerResponseTime:**
| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Response time ID |
| `name` | `?string` | Response time name |
| `description` | `?string` | Response time description |

## Examples

### List All Active Customers

```php
$customers = $connector->customers(companyId: 0)->list()
    ->where('IsArchived', '=', false)
    ->orderBy('CompanyName')
    ->all();

foreach ($customers as $customer) {
    $name = $customer->companyName ?: "{$customer->givenName} {$customer->familyName}";
    echo "{$customer->id}: {$name}\n";
}
```

### Build Customer Dropdown

```php
$customers = $connector->customers(companyId: 0)->listCompanies()
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

$customers = $connector->customers(companyId: 0)->list()
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
$customer = $connector->customers(companyId: 0)->getCompany(customerId: 123);

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

    return $this->connector->customers(companyId: 0)->createCompany($data);
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

$customers = $connector->customers(companyId: 0)->listCompanies()
    ->where('Address.City', 'like', $city)
    ->orderBy('CompanyName')
    ->all();

echo "Customers in {$city}:\n";
foreach ($customers as $customer) {
    $full = $connector->customers(companyId: 0)->getCompany(customerId: $customer->id);
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
        $connector->customers(companyId: 0)->updateCompany(customerId: $id, data: [
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
$customers = $connector->customers(companyId: 0)->listCompanies()
    ->where('IsArchived', '=', false)
    ->orderBy('CompanyName')
    ->all();

$export = [];
foreach ($customers as $customer) {
    // Get full details for each customer
    $full = $connector->customers(companyId: 0)->getCompany(customerId: $customer->id);

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
$customers = $connector->customers(companyId: 0)->listCompanies()
    ->where('IsArchived', '=', false)
    ->all();

$missingEmail = [];
foreach ($customers as $customer) {
    // Get full customer details to check email
    $full = $connector->customers(companyId: 0)->getCompany(customerId: $customer->id);

    if (empty($full->email)) {
        $missingEmail[] = [
            'id' => $full->id,
            'name' => $full->companyName,
            'phone' => $full->phone,
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
$allCustomers = $connector->customers(companyId: 0)->list()->all();

$summary = [
    'total' => count($allCustomers),
    'companies' => 0,
    'individuals' => 0,
];

foreach ($allCustomers as $customer) {
    // Determine type based on whether companyName is set
    if (!empty($customer->companyName)) {
        $summary['companies']++;
    } else {
        $summary['individuals']++;
    }
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
$builder = $connector->customers(companyId: 0)->list();

// Change page size if needed
$builder->getPaginator()->setPerPageLimit(100);

// Iterate automatically handles pagination
foreach ($builder->items() as $customer) {
    // Processes all customers across all pages
}
```

### Avoiding N+1 Queries

When you need full details for multiple customers, use `listCompaniesDetailed()`:

```php
// Recommended: Use listCompaniesDetailed() for full data in a single request
$customers = $connector->customers(companyId: 0)->listCompaniesDetailed()->all();
foreach ($customers as $customer) {
    // All fields available without additional API calls
    echo "{$customer->companyName}: {$customer->email}\n";
    echo "  Phone: {$customer->phone}\n";
    echo "  Amount Owing: {$customer->amountOwing}\n";
    echo "  Type: {$customer->customerType?->name}\n";

    foreach ($customer->sites ?? [] as $site) {
        echo "  Site: {$site->name}\n";
    }
}

// Alternative: listCompanies() for basic info only
$customers = $connector->customers(companyId: 0)->listCompanies()->all();
foreach ($customers as $customer) {
    // Use list data directly when you only need basic info
    echo "{$customer->id}: {$customer->companyName}\n";
}

// Avoid: Individual getCompany() calls create N+1 queries
$customers = $connector->customers(companyId: 0)->listCompanies()->all();
foreach ($customers as $customer) {
    $full = $connector->customers(companyId: 0)->getCompany(customerId: $customer->id); // N+1 requests - avoid this
}
```

### Caching Strategy

Consider caching customer data for dropdown lists:

```php
use Illuminate\Support\Facades\Cache;

// Cache customer list for 15 minutes
$customers = Cache::remember('simpro.customers.active', 900, function () use ($connector) {
    return $connector->customers(companyId: 0)->listCompanies()
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
    $customerId = $connector->customers(companyId: 0)->createCompany(data: [
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
    $customer = $connector->customers(companyId: 0)->getCompany(99999);
} catch (ClientException $e) {
    if ($e->getResponse()->status() === 404) {
        echo "Customer not found\n";
    } else {
        throw $e;
    }
}
```

## Individual Customers

Individual customers are people (not companies) in your Simpro system.

### Accessing Individual Customers

```php
$individuals = $connector->customers(companyId: 0)->individuals();
```

### List Individual Customers

```php
// Basic list with minimal fields
$individuals = $connector->customers(companyId: 0)->individuals()->list();

foreach ($individuals->items() as $customer) {
    echo "{$customer->id}: {$customer->givenName} {$customer->familyName}\n";
}
```

### List Individual Customers with Full Details

Returns all available fields in a single request:

```php
$individuals = $connector->customers(companyId: 0)->individuals()->listDetailed();

foreach ($individuals->items() as $customer) {
    echo "{$customer->id}: {$customer->givenName} {$customer->familyName}\n";
    echo "  Email: {$customer->email}\n";
    echo "  Phone: {$customer->phone}\n";
    echo "  Cell: {$customer->cellPhone}\n";
    echo "  Amount Owing: {$customer->amountOwing}\n";

    if ($customer->address) {
        echo "  City: {$customer->address->city}\n";
    }

    foreach ($customer->tags ?? [] as $tag) {
        echo "  Tag: {$tag->name}\n";
    }
}
```

### Get Individual Customer

```php
$customer = $connector->customers(companyId: 0)->individuals()->get(customerId: 456);

echo "{$customer->title} {$customer->givenName} {$customer->familyName}\n";
echo "  Phone: {$customer->phone}\n";
echo "  Email: {$customer->email}\n";
```

### Create Individual Customer

```php
$customerId = $connector->customers(companyId: 0)->individuals()->create([
    'Title' => 'Mr',
    'GivenName' => 'John',
    'FamilyName' => 'Smith',
    'Email' => 'john.smith@email.com',
    'Phone' => '555-1234',
]);

echo "Created individual customer: {$customerId}\n";
```

### Update Individual Customer

```php
$response = $connector->customers(companyId: 0)->individuals()->update(customerId: 456, data: [
    'Phone' => '555-9999',
    'Email' => 'new.email@email.com',
]);
```

### Delete Individual Customer

```php
$response = $connector->customers(companyId: 0)->individuals()->delete(customerId: 456);
```

---

## Customer Contacts

Manage contacts for a specific customer.

### Accessing Contacts

```php
$contacts = $connector->customers(companyId: 0)->customer(customerId: 123)->contacts();
```

### List Contacts

```php
// Basic list
$contacts = $connector->customers(companyId: 0)->customer(123)->contacts()->list();

foreach ($contacts->items() as $contact) {
    echo "{$contact->id}: {$contact->givenName} {$contact->familyName}\n";
}
```

### List Contacts with Full Details

Returns all available fields including contact role flags:

```php
$contacts = $connector->customers(companyId: 0)->customer(123)->contacts()->listDetailed();

foreach ($contacts->items() as $contact) {
    echo "{$contact->title} {$contact->givenName} {$contact->familyName}\n";
    echo "  Email: {$contact->email}\n";
    echo "  Work Phone: {$contact->workPhone}\n";
    echo "  Cell Phone: {$contact->cellPhone}\n";
    echo "  Department: {$contact->department}\n";
    echo "  Position: {$contact->position}\n";

    // Contact role flags
    if ($contact->primaryJobContact) {
        echo "  ✓ Primary Job Contact\n";
    }
    if ($contact->primaryQuoteContact) {
        echo "  ✓ Primary Quote Contact\n";
    }
    if ($contact->primaryInvoiceContact) {
        echo "  ✓ Primary Invoice Contact\n";
    }
}
```

### Get Contact

```php
$contact = $connector->customers(companyId: 0)->customer(123)->contacts()->get(contactId: 1);

echo "{$contact->givenName} {$contact->familyName}\n";
echo "  Email: {$contact->email}\n";
echo "  Position: {$contact->position}\n";
```

### Create Contact

```php
$contactId = $connector->customers(companyId: 0)->customer(123)->contacts()->create([
    'Title' => 'Ms',
    'GivenName' => 'Jane',
    'FamilyName' => 'Doe',
    'Email' => 'jane.doe@company.com',
    'WorkPhone' => '555-0100',
    'Position' => 'Manager',
    'PrimaryJobContact' => true,
]);

echo "Created contact: {$contactId}\n";
```

### Update Contact

```php
$response = $connector->customers(companyId: 0)->customer(123)->contacts()->update(contactId: 1, data: [
    'Position' => 'Senior Manager',
    'WorkPhone' => '555-0199',
]);
```

### Delete Contact

```php
$response = $connector->customers(companyId: 0)->customer(123)->contacts()->delete(contactId: 1);
```

### Contact Response Structure

The `Contact` DTO includes the following fields:

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Contact ID |
| `title` | `?string` | Title (Mr, Ms, etc.) |
| `givenName` | `?string` | First name |
| `familyName` | `?string` | Last name |
| `email` | `?string` | Email address |
| `workPhone` | `?string` | Work phone |
| `fax` | `?string` | Fax number |
| `cellPhone` | `?string` | Mobile phone |
| `altPhone` | `?string` | Alternative phone |
| `department` | `?string` | Department |
| `position` | `?string` | Job position |
| `notes` | `?string` | Notes |
| `customFields` | `?array<CustomField>` | Custom field values |
| `dateModified` | `?DateTimeImmutable` | Last modification date |
| `quoteContact` | `?bool` | Receives quotes |
| `jobContact` | `?bool` | Receives job notifications |
| `invoiceContact` | `?bool` | Receives invoices |
| `statementContact` | `?bool` | Receives statements |
| `primaryQuoteContact` | `?bool` | Primary contact for quotes |
| `primaryJobContact` | `?bool` | Primary contact for jobs |
| `primaryInvoiceContact` | `?bool` | Primary contact for invoices |
| `primaryStatementContact` | `?bool` | Primary contact for statements |

---

## Customer Contracts

Manage service contracts for a specific customer.

### Accessing Contracts

```php
$contracts = $connector->customers(companyId: 0)->customer(customerId: 123)->contracts();
```

### List Contracts

```php
// Basic list
$contracts = $connector->customers(companyId: 0)->customer(123)->contracts()->list();

foreach ($contracts->items() as $contract) {
    echo "{$contract->id}: {$contract->name} ({$contract->contractNo})\n";
    echo "  {$contract->startDate} - {$contract->endDate}\n";
}
```

### List Contracts with Full Details

Returns all available fields including rates and service levels:

```php
$contracts = $connector->customers(companyId: 0)->customer(123)->contracts()->listDetailed();

foreach ($contracts->items() as $contract) {
    echo "{$contract->name} ({$contract->contractNo})\n";
    echo "  Value: \${$contract->value}\n";
    echo "  Markup: {$contract->markup}%\n";
    echo "  Pricing Tier: {$contract->pricingTier->name}\n";

    if ($contract->expired) {
        echo "  ⚠️ EXPIRED\n";
    }

    foreach ($contract->serviceLevels ?? [] as $level) {
        echo "  Service Level: {$level->serviceLevel->name}\n";
    }
}
```

### Get Contract

```php
$contract = $connector->customers(companyId: 0)->customer(123)->contracts()->get(contractId: 1);

echo "{$contract->name}\n";
echo "  Contract No: {$contract->contractNo}\n";
echo "  Value: \${$contract->value}\n";
echo "  Start: {$contract->startDate}\n";
echo "  End: {$contract->endDate}\n";
```

### Create Contract

```php
$contractId = $connector->customers(companyId: 0)->customer(123)->contracts()->create([
    'Name' => 'Annual Maintenance',
    'ContractNo' => 'CON-2024-001',
    'StartDate' => '2024-01-01',
    'EndDate' => '2024-12-31',
    'Value' => 12000.00,
]);

echo "Created contract: {$contractId}\n";
```

### Update Contract

```php
$response = $connector->customers(companyId: 0)->customer(123)->contracts()->update(contractId: 1, data: [
    'Value' => 15000.00,
    'Notes' => 'Updated contract value for 2024',
]);
```

### Delete Contract

```php
$response = $connector->customers(companyId: 0)->customer(123)->contracts()->delete(contractId: 1);
```

### Contract Nested Resources

Contracts have several nested resources for detailed configuration:

#### Contract Inflation

```php
$inflation = $connector->customers(companyId: 0)->customer(123)->contracts()->contract(1)->inflation();

// List inflation records
foreach ($inflation->list()->items() as $record) {
    echo "{$record->date}: {$record->percentage}%\n";
}
```

#### Contract Labor Rates

```php
$laborRates = $connector->customers(companyId: 0)->customer(123)->contracts()->contract(1)->laborRates();

foreach ($laborRates->list()->items() as $rate) {
    echo "{$rate->name}: \${$rate->rate}/hr\n";
}
```

#### Contract Service Levels

```php
$serviceLevels = $connector->customers(companyId: 0)->customer(123)->contracts()->contract(1)->serviceLevels();

foreach ($serviceLevels->list()->items() as $level) {
    echo "{$level->name}\n";
}

// Get asset types for a service level
$assetTypes = $serviceLevels->serviceLevel(1)->assetTypes();
foreach ($assetTypes->list()->items() as $type) {
    echo "  Asset Type: {$type->name}\n";
}
```

#### Contract Custom Fields

```php
$customFields = $connector->customers(companyId: 0)->customer(123)->contracts()->contract(1)->customFields();

foreach ($customFields->list()->items() as $field) {
    echo "{$field->name}: {$field->value}\n";
}
```

---

## Future Enhancements

The following features are planned for future SDK releases:

- **Customer Sites**: Full CRUD operations for sites/locations (basic info available via `listCompaniesDetailed()`)
