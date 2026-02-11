# CurrentUser Resource

The CurrentUser resource provides information about the currently authenticated user. This is useful for identifying who is making API calls and what companies they have access to.

## Getting the Current User

### Basic Usage

Returns information about the authenticated user:

```php
$user = $connector->currentUser()->get();

return [
    'id' => $user->id,
    'name' => $user->name,
    'type' => $user->type,
    'typeId' => $user->typeId,
    'preferredLanguage' => $user->preferredLanguage,
    'accessibleCompanies' => $user->accessibleCompanies,
];
```

### Accessing User Properties

The `CurrentUser` DTO provides all user information:

```php
$user = $connector->currentUser()->get();

// Identity
echo "User ID: {$user->id}\n";
echo "Name: {$user->name}\n";
echo "Type: {$user->type}\n";
echo "Type ID: {$user->typeId}\n";
echo "Preferred Language: {$user->preferredLanguage}\n";
```

### Accessing Company Access

The user's accessible companies are returned as an array of `Reference` objects:

```php
$user = $connector->currentUser()->get();

if ($user->accessibleCompanies !== null) {
    foreach ($user->accessibleCompanies as $company) {
        echo "Company ID: {$company->id}, Name: {$company->name}\n";
    }
}
```

## Response Structure

### CurrentUser DTO

The `CurrentUser` DTO contains the following properties:

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | User ID |
| `name` | `?string` | User or integration name |
| `type` | `?string` | User type (e.g. "employee") |
| `typeId` | `?int` | ID corresponding to the user type |
| `preferredLanguage` | `?string` | Preferred language code (e.g. "en_GB") |
| `accessibleCompanies` | `?array<Reference>` | Companies the user has access to |

### Reference DTO (for AccessibleCompanies)

Each company in the `accessibleCompanies` array is a `Reference` object:

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Company ID |
| `name` | `string` | Company name |

## Examples

### Display Current User Info

```php
$user = $connector->currentUser()->get();

return [
    'identity' => [
        'id' => $user->id,
        'name' => $user->name,
        'type' => $user->type,
        'typeId' => $user->typeId,
    ],
    'preferredLanguage' => $user->preferredLanguage,
];
```

### Check Company Access

Verify the user has access to a specific company:

```php
$user = $connector->currentUser()->get();

$targetCompanyId = 5;
$hasAccess = false;

if ($user->accessibleCompanies !== null) {
    foreach ($user->accessibleCompanies as $company) {
        if ($company->id === $targetCompanyId) {
            $hasAccess = true;
            break;
        }
    }
}

if ($hasAccess) {
    echo "User has access to company {$targetCompanyId}\n";
} else {
    echo "User does not have access to company {$targetCompanyId}\n";
}
```

### Build Company Selector from User Access

```php
$user = $connector->currentUser()->get();

$options = [];
if ($user->accessibleCompanies !== null) {
    foreach ($user->accessibleCompanies as $company) {
        $options[] = [
            'value' => $company->id,
            'label' => $company->name,
        ];
    }
}

return $options;
```

### Welcome Message

```php
$user = $connector->currentUser()->get();

$greeting = "Welcome, {$user->name}!";

if ($user->accessibleCompanies !== null && count($user->accessibleCompanies) > 0) {
    $companyCount = count($user->accessibleCompanies);
    $greeting .= " You have access to {$companyCount} " .
                 ($companyCount === 1 ? 'company' : 'companies') . ".";
}

echo $greeting;
```

### Store User Context

Cache user information for the session:

```php
use Illuminate\Support\Facades\Cache;

// Cache current user for the session (1 hour)
$user = Cache::remember('simpro.current_user', 3600, function () use ($connector) {
    return $connector->currentUser()->get();
});

// Use cached user info
echo "Logged in as: {$user->name}\n";
```

## Authentication Context

The CurrentUser resource returns information based on the authentication method used:

### OAuth Authentication

When using OAuth, the returned user represents the Simpro user who authorized the application:

```php
$connector = new SimproOAuthConnector(
    baseUrl: 'https://example.simprosuite.com',
    clientId: 'your-client-id',
    clientSecret: 'your-client-secret',
    redirectUri: 'https://yourapp.com/oauth/callback'
);

// After OAuth flow completes...
$connector->authenticate($authenticator);

// Get the user who authorized the app
$user = $connector->currentUser()->get();
echo "Authorized by: {$user->name}\n";
```

### API Key Authentication

When using an API key, the returned user represents the Simpro user associated with that API key:

```php
$connector = new SimproApiKeyConnector(
    baseUrl: 'https://example.simprosuite.com/api/v1.0',
    apiKey: 'your-api-key'
);

// Get the user associated with the API key
$user = $connector->currentUser()->get();
echo "API Key belongs to: {$user->name}\n";
```

## Use Cases

### Audit Logging

Track which user is making API calls:

```php
$user = $connector->currentUser()->get();

$auditEntry = [
    'timestamp' => now(),
    'user_id' => $user->id,
    'user_name' => $user->name,
    'action' => 'created_job',
    'resource_id' => $jobId,
];

// Log to your audit system
AuditLog::create($auditEntry);
```

### Permission Checking

While the Simpro API handles permissions internally, you can use user info for UI decisions:

```php
$user = $connector->currentUser()->get();

// Example: Check if user can see all companies
$companyCount = $user->accessibleCompanies !== null ? count($user->accessibleCompanies) : 0;
$showCompanySelector = $companyCount > 1;

return view('dashboard', [
    'user' => $user,
    'showCompanySelector' => $showCompanySelector,
]);
```

### Multi-Tenant Applications

For applications serving multiple Simpro users:

```php
$user = $connector->currentUser()->get();

// Store user's default company preference
$defaultCompanyId = $user->accessibleCompanies[0]->id ?? null;

UserPreferences::updateOrCreate(
    ['simpro_user_id' => $user->id],
    ['default_company_id' => $defaultCompanyId]
);
```
