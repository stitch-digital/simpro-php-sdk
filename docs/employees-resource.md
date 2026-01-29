# Employees Resource

The Employees resource provides full CRUD operations for managing employees (staff members) in your Simpro environment. Employees are users who can be assigned to jobs, schedules, and other operations.

## Listing Employees

### Basic List

Returns all employees with pagination support. The list returns `EmployeeListItem` objects with minimal fields:

```php
$employees = $connector->employees(companyId: 0)->list();

foreach ($employees->items() as $employee) {
    echo "{$employee->id}: {$employee->name}\n";
}
```

**Note:** For full employee details (email, phone, address, etc.), use `get()` to retrieve individual employees by ID.

### Filtering Employees

Use the fluent search API or array-based filters:

#### Fluent Search API (Recommended)

```php
use Simpro\PhpSdk\Simpro\Query\Search;

// Search by name
$employees = $connector->employees(companyId: 0)->list()
    ->search(Search::make()->column('Name')->find('John'))
    ->items();

// Search by email
$employees = $connector->employees(companyId: 0)->list()
    ->where('Email', 'like', '@example.com')
    ->items();

// Multiple criteria
$employees = $connector->employees(companyId: 0)->list()
    ->search([
        Search::make()->column('Name')->find('Smith'),
        Search::make()->column('IsArchived')->equals(false),
    ])
    ->matchAll()
    ->orderBy('Name')
    ->items();

// Find active employees only
$employees = $connector->employees(companyId: 0)->list()
    ->where('IsArchived', '=', false)
    ->items();
```

#### Array-Based Syntax

```php
// Search by name
$employees = $connector->employees(companyId: 0)->list(['Name' => '%25John%25']);

// Filter active employees
$employees = $connector->employees(companyId: 0)->list(['IsArchived' => 'false']);

// Order by name
$employees = $connector->employees(companyId: 0)->list(['orderby' => 'Name']);
```

### Ordering Results

```php
// Order by name ascending
$employees = $connector->employees(companyId: 0)->list()
    ->orderBy('Name')
    ->items();

// Order by name descending
$employees = $connector->employees(companyId: 0)->list()
    ->orderByDesc('Name')
    ->items();

// Order by ID
$employees = $connector->employees(companyId: 0)->list()
    ->orderBy('ID')
    ->items();
```

## Getting a Single Employee

### Get by ID

Returns complete information for a specific employee:

```php
$employee = $connector->employees(companyId: 0)->get(employeeId: 123);

return [
    'id' => $employee->id,
    'name' => $employee->name,
    'givenName' => $employee->givenName,
    'familyName' => $employee->familyName,
    'email' => $employee->email,
    'phone' => $employee->phone,
    'mobile' => $employee->mobile,
    'employeeNo' => $employee->employeeNo,
    'address' => $employee->address,
    'isArchived' => $employee->isArchived,
];
```

### Get with Specific Columns

Optionally specify which columns to return:

```php
$employee = $connector->employees(companyId: 0)->get(employeeId: 123, columns: ['ID', 'Name', 'Email', 'Phone']);
```

## Creating an Employee

Create a new employee with the required fields:

```php
$employeeId = $connector->employees(companyId: 0)->create(data: [
    'GivenName' => 'John',
    'FamilyName' => 'Smith',
    'Email' => 'john.smith@example.com',
    'Phone' => '+61 7 1234 5678',
    'Mobile' => '+61 400 123 456',
]);

echo "Created employee with ID: {$employeeId}\n";
```

### Create with All Details

```php
$employeeId = $connector->employees(companyId: 0)->create(data: [
    'GivenName' => 'Jane',
    'FamilyName' => 'Doe',
    'Email' => 'jane.doe@example.com',
    'Phone' => '+61 7 9876 5432',
    'Mobile' => '+61 400 987 654',
    'EmployeeNo' => 'EMP-001',
    'DateOfBirth' => '1990-05-15',
    'StartDate' => '2024-01-15',
    'Address' => [
        'Address' => '123 Main Street',
        'City' => 'Brisbane',
        'State' => 'QLD',
        'PostalCode' => '4000',
        'Country' => 'Australia',
    ],
]);
```

## Updating an Employee

Update an existing employee:

```php
$response = $connector->employees(companyId: 0)->update(employeeId: 123, data: [
    'Phone' => '+61 7 9999 8888',
    'Mobile' => '+61 400 111 222',
]);

if ($response->successful()) {
    echo "Employee updated successfully\n";
}
```

### Update Multiple Fields

```php
$response = $connector->employees(companyId: 0)->update(employeeId: 123, data: [
    'GivenName' => 'Jonathan',
    'Email' => 'jonathan.smith@example.com',
    'Address' => [
        'Address' => '456 New Street',
        'City' => 'Sydney',
        'State' => 'NSW',
        'PostalCode' => '2000',
    ],
]);
```

### Archive an Employee

```php
$response = $connector->employees(companyId: 0)->update(employeeId: 123, data: [
    'IsArchived' => true,
]);

echo "Employee archived\n";
```

## Deleting an Employee

Delete an employee permanently:

```php
$response = $connector->employees(companyId: 0)->delete(employeeId: 123);

if ($response->successful()) {
    echo "Employee deleted successfully\n";
}
```

**Note:** Consider archiving employees instead of deleting them to preserve historical data and relationships.

## Response Structures

### EmployeeListItem (List Response)

Lightweight object returned by `list()`. Contains only minimal fields:

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Employee ID |
| `name` | `string` | Full name |

**Note:** For full employee details (email, phone, address, isArchived, etc.), use `get()` to retrieve the complete employee.

### Employee (Detailed Response)

Complete employee object returned by `get()`:

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Employee ID |
| `name` | `string` | Full name |
| `givenName` | `?string` | First name |
| `familyName` | `?string` | Last name / surname |
| `email` | `?string` | Email address |
| `phone` | `?string` | Phone number |
| `mobile` | `?string` | Mobile phone number |
| `address` | `?EmployeeAddress` | Address details |
| `employeeNo` | `?string` | Employee number/code |
| `dateOfBirth` | `?DateTimeImmutable` | Date of birth |
| `startDate` | `?DateTimeImmutable` | Employment start date |
| `isArchived` | `?bool` | Whether employee is archived |
| `dateModified` | `?DateTimeImmutable` | Last modification date |

### Nested Objects

**EmployeeAddress:**
| Property | Type | Description |
|----------|------|-------------|
| `address` | `?string` | Street address |
| `city` | `?string` | City |
| `state` | `?string` | State/province |
| `postalCode` | `?string` | Postal/ZIP code |
| `country` | `?string` | Country |

## Examples

### List All Active Employees

```php
$employees = $connector->employees(companyId: 0)->list()
    ->where('IsArchived', '=', false)
    ->orderBy('Name')
    ->all();

foreach ($employees as $employee) {
    echo "{$employee->id}: {$employee->name}\n";
}
```

### Build Employee Dropdown

```php
$employees = $connector->employees(companyId: 0)->list()
    ->where('IsArchived', '=', false)
    ->orderBy('Name')
    ->all();

$options = [];
foreach ($employees as $employee) {
    $options[] = [
        'value' => $employee->id,
        'label' => $employee->name,
    ];
}

return $options;
```

### Search for Employees by Name

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$searchTerm = 'Smith';

$employees = $connector->employees(companyId: 0)->list()
    ->search(Search::make()->column('Name')->find($searchTerm))
    ->all();

if (count($employees) === 0) {
    echo "No employees found matching '{$searchTerm}'\n";
} else {
    foreach ($employees as $employee) {
        echo "{$employee->id}: {$employee->name}\n";
    }
}
```

### Get Employee with Contact Details

```php
$employee = $connector->employees(companyId: 0)->get(employeeId: 123);

$contactInfo = [
    'name' => $employee->name,
    'contacts' => [
        'email' => $employee->email,
        'phone' => $employee->phone,
        'mobile' => $employee->mobile,
    ],
];

if ($employee->address !== null) {
    $contactInfo['address'] = [
        'street' => $employee->address->address,
        'city' => $employee->address->city,
        'state' => $employee->address->state,
        'postalCode' => $employee->address->postalCode,
        'country' => $employee->address->country,
    ];
}

return $contactInfo;
```

### Create Employee with Validation

```php
function createEmployee(array $data): int
{
    // Validate required fields
    $required = ['GivenName', 'FamilyName', 'Email'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            throw new InvalidArgumentException("Missing required field: {$field}");
        }
    }

    // Validate email format
    if (!filter_var($data['Email'], FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException("Invalid email format");
    }

    return $this->connector->employees(companyId: 0)->create($data);
}

// Usage
try {
    $employeeId = createEmployee([
        'GivenName' => 'John',
        'FamilyName' => 'Smith',
        'Email' => 'john.smith@example.com',
    ]);
    echo "Created employee: {$employeeId}\n";
} catch (InvalidArgumentException $e) {
    echo "Validation error: {$e->getMessage()}\n";
}
```

### Bulk Update Employee Status

Archive multiple employees:

```php
$employeeIds = [101, 102, 103];

foreach ($employeeIds as $id) {
    try {
        $connector->employees(companyId: 0)->update(employeeId: $id, data: [
            'IsArchived' => true,
        ]);
        echo "Archived employee {$id}\n";
    } catch (Exception $e) {
        echo "Failed to archive employee {$id}: {$e->getMessage()}\n";
    }
}
```

### Export Employees to Array

```php
$employees = $connector->employees(companyId: 0)->list()
    ->orderBy('Name')
    ->all();

$export = [];
foreach ($employees as $employee) {
    // Get full details for each employee
    $full = $connector->employees(companyId: 0)->get(employeeId: $employee->id);

    $export[] = [
        'ID' => $full->id,
        'EmployeeNo' => $full->employeeNo,
        'GivenName' => $full->givenName,
        'FamilyName' => $full->familyName,
        'Email' => $full->email,
        'Phone' => $full->phone,
        'Mobile' => $full->mobile,
        'City' => $full->address?->city,
        'StartDate' => $full->startDate?->format('Y-m-d'),
        'IsArchived' => $full->isArchived ? 'Yes' : 'No',
    ];
}

return $export;
```

### Find Recently Modified Employees

```php
// Get all employees and filter by modification date client-side
$cutoffDate = new DateTimeImmutable('-7 days');

$employees = $connector->employees(companyId: 0)->list()
    ->orderBy('Name')
    ->all();

$recentlyModified = [];
foreach ($employees as $employee) {
    $full = $connector->employees(companyId: 0)->get(employeeId: $employee->id);

    if ($full->dateModified !== null && $full->dateModified >= $cutoffDate) {
        $recentlyModified[] = $full;
    }
}

foreach ($recentlyModified as $employee) {
    echo "{$employee->name} - Modified: {$employee->dateModified->format('Y-m-d H:i')}\n";
}
```

## Performance Considerations

### Pagination

Employee lists are paginated automatically. The default page size is 30:

```php
$builder = $connector->employees(companyId: 0)->list();

// Change page size if needed
$builder->getPaginator()->setPerPageLimit(100);

// Iterate automatically handles pagination
foreach ($builder->items() as $employee) {
    // Processes all employees across all pages
}
```

### Avoiding N+1 Queries

When you need full details for multiple employees, consider the performance trade-off:

```php
// Option 1: Multiple detailed requests (more data, more requests)
$employees = $connector->employees(companyId: 0)->list()->all();
foreach ($employees as $employee) {
    $full = $connector->employees(companyId: 0)->get(employeeId: $employee->id); // N+1 requests
}

// Option 2: Use list data when sufficient
$employees = $connector->employees(companyId: 0)->list()->all();
foreach ($employees as $employee) {
    // Use list data directly when you only need basic info
    echo "{$employee->name}: {$employee->email}\n";
}
```

### Caching Strategy

Consider caching employee data for dropdown lists:

```php
use Illuminate\Support\Facades\Cache;

// Cache employee list for 15 minutes
$employees = Cache::remember('simpro.employees.active', 900, function () use ($connector) {
    return $connector->employees(companyId: 0)->list()
        ->where('IsArchived', '=', false)
        ->orderBy('Name')
        ->all();
});
```

## Error Handling

### Validation Errors

Handle validation errors when creating or updating employees:

```php
use Simpro\PhpSdk\Simpro\Exceptions\ValidationException;

try {
    $employeeId = $connector->employees(companyId: 0)->create(data: [
        'GivenName' => 'John',
        // Missing FamilyName
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

Handle cases where an employee doesn't exist:

```php
use Saloon\Exceptions\Request\ClientException;

try {
    $employee = $connector->employees(companyId: 0)->get(employeeId: 99999);
} catch (ClientException $e) {
    if ($e->getResponse()->status() === 404) {
        echo "Employee not found\n";
    } else {
        throw $e;
    }
}
```
