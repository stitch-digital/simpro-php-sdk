# Info Resource

The Info resource provides information about your Simpro instance, including version, country, and enabled features.

## Getting Information

### Get Complete Info

Returns all information about the Simpro instance:

```php
$info = $connector->info()->get();

return [
    'version' => $info->version,              // '99.0.0.0.1.1'
    'country' => $info->country,              // 'Australia'
    'maintenancePlanner' => $info->maintenancePlanner,  // true
    'multiCompany' => $info->multiCompany,              // false
];
```

### Convenience Methods

The Info resource provides convenience methods for each field, allowing you to retrieve specific values without calling `get()`:

#### Version

Get the Simpro version:

```php
$version = $connector->info()->version();
// Returns: '99.0.0.0.1.1'
```

#### Country

Get the Simpro country:

```php
$country = $connector->info()->country();
// Returns: 'Australia'
```

#### Feature Flags

Check if specific features are enabled:

```php
// Check if maintenance planner is enabled
if ($connector->info()->maintenancePlanner()) {
    // Maintenance planner is enabled
}

// Check if multi-company is enabled
if ($connector->info()->multiCompany()) {
    // Multi-company is enabled
}

// Check if shared catalog is enabled
if ($connector->info()->sharedCatalog()) {
    // Shared catalog is enabled
}

// Check if shared stock is enabled
if ($connector->info()->sharedStock()) {
    // Shared stock is enabled
}

// Check if shared clients is enabled
if ($connector->info()->sharedClients()) {
    // Shared clients is enabled
}

// Check if shared setup is enabled
if ($connector->info()->sharedSetup()) {
    // Shared setup is enabled
}

// Check if shared defaults is enabled
if ($connector->info()->sharedDefaults()) {
    // Shared defaults is enabled
}

// Check if shared accounts integration is enabled
if ($connector->info()->sharedAccountsIntegration()) {
    // Shared accounts integration is enabled
}

// Check if shared VOIP is enabled
if ($connector->info()->sharedVoip()) {
    // Shared VOIP is enabled
}
```

## Response Structure

The `Info` DTO contains the following properties:

| Property | Type | Description |
|----------|------|-------------|
| `version` | `string` | Simpro version number |
| `country` | `string` | Simpro instance country |
| `maintenancePlanner` | `bool` | Whether maintenance planner is enabled |
| `multiCompany` | `bool` | Whether multi-company is enabled |
| `sharedCatalog` | `bool` | Whether shared catalog is enabled |
| `sharedStock` | `bool` | Whether shared stock is enabled |
| `sharedClients` | `bool` | Whether shared clients is enabled |
| `sharedSetup` | `bool` | Whether shared setup is enabled |
| `sharedDefaults` | `bool` | Whether shared defaults is enabled |
| `sharedAccountsIntegration` | `bool` | Whether shared accounts integration is enabled |
| `sharedVoip` | `bool` | Whether shared VOIP is enabled |

## Examples

### Check Version Before Using Features

```php
$version = $connector->info()->version();

if (version_compare($version, '99.0.0.0', '>=')) {
    // Use features available in version 99+
}
```

### Conditionally Enable Features

```php
$info = $connector->info()->get();

if ($info->maintenancePlanner) {
    // Show maintenance planner UI
}

if ($info->multiCompany) {
    // Enable company selection dropdown
}
```

### Display Instance Information

```php
$info = $connector->info()->get();

return [
    'instance' => [
        'version' => $info->version,
        'country' => $info->country,
    ],
    'features' => [
        'maintenancePlanner' => $info->maintenancePlanner,
        'multiCompany' => $info->multiCompany,
        'sharedCatalog' => $info->sharedCatalog,
        'sharedStock' => $info->sharedStock,
        'sharedClients' => $info->sharedClients,
        'sharedSetup' => $info->sharedSetup,
        'sharedDefaults' => $info->sharedDefaults,
        'sharedAccountsIntegration' => $info->sharedAccountsIntegration,
        'sharedVoip' => $info->sharedVoip,
    ],
];
```
