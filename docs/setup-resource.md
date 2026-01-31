# Setup Resource

The Setup resource provides access to system configuration settings in your Simpro environment. This includes webhooks, tax codes, payment methods, custom fields, labor rates, materials settings, and more.

## Access Pattern

```php
$connector->setup(companyId: 0);
```

## Available Resources

### Webhooks

Manage webhook subscriptions for event notifications:

```php
// List webhooks
$webhooks = $connector->setup(companyId: 0)->webhooks()->list();

// Get a webhook
$webhook = $connector->setup(companyId: 0)->webhooks()->get(webhookId: 123);

// Create a webhook
$id = $connector->setup(companyId: 0)->webhooks()->create([
    'URL' => 'https://yourapp.com/webhooks/simpro',
    'Event' => 'job.created',
]);

// Update a webhook
$connector->setup(companyId: 0)->webhooks()->update(webhookId: 123, data: [
    'URL' => 'https://yourapp.com/webhooks/simpro-v2',
]);

// Delete a webhook
$connector->setup(companyId: 0)->webhooks()->delete(webhookId: 123);
```

### Tax Codes

Manage tax codes for invoicing:

```php
// List tax codes
$taxCodes = $connector->setup(companyId: 0)->taxCodes()->list();

// Get a tax code
$taxCode = $connector->setup(companyId: 0)->taxCodes()->get(taxCodeId: 1);

// List tax code combines (combinations)
$combines = $connector->setup(companyId: 0)->taxCodes()->combines()->list();
```

### Payment Methods

```php
// List payment methods
$methods = $connector->setup(companyId: 0)->paymentMethods()->list();

// Get a payment method
$method = $connector->setup(companyId: 0)->paymentMethods()->get(paymentMethodId: 1);
```

### Payment Terms

```php
// List payment terms
$terms = $connector->setup(companyId: 0)->paymentTerms()->list();

// Get a payment term
$term = $connector->setup(companyId: 0)->paymentTerms()->get(paymentTermId: 1);
```

### Customer Groups

```php
// List customer groups
$groups = $connector->setup(companyId: 0)->customerGroups()->list();

// Create a customer group
$id = $connector->setup(companyId: 0)->customerGroups()->create([
    'Name' => 'VIP Customers',
]);
```

### Zones

Geographic zones for service areas:

```php
// List zones
$zones = $connector->setup(companyId: 0)->zones()->list();

// Create a zone
$id = $connector->setup(companyId: 0)->zones()->create([
    'Name' => 'North Region',
]);
```

### Accounting Categories

```php
$categories = $connector->setup(companyId: 0)->accountingCategories()->list();
```

### Business Groups

```php
$groups = $connector->setup(companyId: 0)->businessGroups()->list();
```

### Chart of Accounts

```php
$accounts = $connector->setup(companyId: 0)->chartOfAccounts()->list();
```

### Cost Centers

```php
$centers = $connector->setup(companyId: 0)->costCenters()->list();
```

### Custom Fields

Access custom fields for various entity types:

```php
// Customer custom fields
$fields = $connector->setup(companyId: 0)->customFields()->customers()->list();

// Job custom fields
$fields = $connector->setup(companyId: 0)->customFields()->jobs()->list();

// Quote custom fields
$fields = $connector->setup(companyId: 0)->customFields()->quotes()->list();

// Asset custom fields
$fields = $connector->setup(companyId: 0)->customFields()->assets()->list();

// Employee custom fields
$fields = $connector->setup(companyId: 0)->customFields()->employees()->list();

// Available custom field types:
// customers, jobs, quotes, assets, employees, contractors, invoices,
// leads, plants, prebuildCatalogs, prebuildCatalogItems, projects,
// purchaseOrders, receipts, sites, suppliers, takes, vendorOrders, workOrders
```

### Asset Types

Manage asset types with nested resources:

```php
// List asset types
$types = $connector->setup(companyId: 0)->assetTypes()->list();

// Get a specific asset type
$type = $connector->setup(companyId: 0)->assetTypes()->get(assetTypeId: 1);

// Access nested resources via scope
$scope = $connector->setup(companyId: 0)->assetType(assetTypeId: 1);

// Asset type files
$files = $scope->files()->list();

// Asset type folders
$folders = $scope->folders()->list();

// Asset type custom fields
$fields = $scope->customFields()->list();

// Asset type service levels
$levels = $scope->serviceLevels()->list();

// Service level failure points (nested further)
$levelScope = $scope->serviceLevel(serviceLevelId: 1);
$failurePoints = $levelScope->failurePoints()->list();

// Failure point recommendations
$fpScope = $levelScope->failurePoint(failurePointId: 1);
$recommendations = $fpScope->recommendations()->list();

// Asset type test readings
$readings = $scope->testReadings()->list();
```

### Labor Settings

Access labor-related configuration:

```php
// Fit times
$fitTimes = $connector->setup(companyId: 0)->labor()->fitTimes()->list();

// Labor rates
$rates = $connector->setup(companyId: 0)->labor()->laborRates()->list();

// Get overhead settings
$overhead = $connector->setup(companyId: 0)->labor()->laborRates()->getOverhead();

// Plant rates (read-only)
$plantRates = $connector->setup(companyId: 0)->labor()->plantRates()->list();

// Schedule rates
$scheduleRates = $connector->setup(companyId: 0)->labor()->scheduleRates()->list();

// Service fees (read-only)
$fees = $connector->setup(companyId: 0)->labor()->serviceFees()->list();
```

### Materials Settings

Access materials-related configuration:

```php
// Pricing tiers
$tiers = $connector->setup(companyId: 0)->materials()->pricingTiers()->list();

// Purchasing stages
$stages = $connector->setup(companyId: 0)->materials()->purchasingStages()->list();

// Stock take reasons
$reasons = $connector->setup(companyId: 0)->materials()->stockTakeReasons()->list();

// Stock transfer reasons
$reasons = $connector->setup(companyId: 0)->materials()->stockTransferReasons()->list();

// Units of measurement
$uoms = $connector->setup(companyId: 0)->materials()->uoms()->list();
```

### Activities

```php
$activities = $connector->setup(companyId: 0)->activities()->list();
```

### Quote Archive Reasons

```php
$reasons = $connector->setup(companyId: 0)->quoteArchiveReasons()->list();
```

### Asset Service Levels

```php
$levels = $connector->setup(companyId: 0)->assetServiceLevels()->list();
```

### Commissions

```php
// List all commissions (basic and advanced combined)
$all = $connector->setup(companyId: 0)->commissions()->list();

// Basic commissions
$basic = $connector->setup(companyId: 0)->commissions()->basic()->list();

// Advanced commissions
$advanced = $connector->setup(companyId: 0)->commissions()->advanced()->list();
```

### Currencies

```php
// List currencies
$currencies = $connector->setup(companyId: 0)->currencies()->list();

// Get a currency
$currency = $connector->setup(companyId: 0)->currencies()->get(currencyId: 1);

// Update currency (no create/delete)
$connector->setup(companyId: 0)->currencies()->update(currencyId: 1, data: [
    'Rate' => 1.25,
]);
```

### Customer Profiles

```php
$profiles = $connector->setup(companyId: 0)->customerProfiles()->list();
```

### Defaults

Get company default settings:

```php
$defaults = $connector->setup(companyId: 0)->defaults()->get();

echo "Default Tax Code ID: {$defaults->defaultTaxCodeId}\n";
echo "Default Payment Term ID: {$defaults->defaultPaymentTermId}\n";
```

### Response Times

```php
$times = $connector->setup(companyId: 0)->responseTimes()->list();
```

### Security Groups

```php
// List security groups (read-only)
$groups = $connector->setup(companyId: 0)->securityGroups()->list();

// Get a security group
$group = $connector->setup(companyId: 0)->securityGroups()->get(securityGroupId: 1);
```

### Status Codes

```php
// Customer invoice status codes
$codes = $connector->setup(companyId: 0)->statusCodes()->customerInvoices()->list();

// Project status codes
$codes = $connector->setup(companyId: 0)->statusCodes()->projects()->list();

// Vendor order status codes
$codes = $connector->setup(companyId: 0)->statusCodes()->vendorOrders()->list();
```

### Tags

```php
// Customer tags
$tags = $connector->setup(companyId: 0)->tags()->customers()->list();

// Project tags
$tags = $connector->setup(companyId: 0)->tags()->projects()->list();
```

### Task Categories

```php
$categories = $connector->setup(companyId: 0)->taskCategories()->list();
```

### Teams

```php
// List teams (read-only)
$teams = $connector->setup(companyId: 0)->teams()->list();

// Get a team
$team = $connector->setup(companyId: 0)->teams()->get(teamId: 1);
```

## Common Patterns

### CRUD Operations

Most setup resources support full CRUD operations:

```php
// List
$items = $connector->setup(companyId: 0)->webhooks()->list();

// Get
$item = $connector->setup(companyId: 0)->webhooks()->get(webhookId: 123);

// Create (returns new ID)
$id = $connector->setup(companyId: 0)->webhooks()->create([...]);

// Update (returns Response)
$response = $connector->setup(companyId: 0)->webhooks()->update(webhookId: 123, data: [...]);

// Delete (returns Response)
$response = $connector->setup(companyId: 0)->webhooks()->delete(webhookId: 123);
```

### Read-Only Resources

Some resources are read-only:
- `plantRates()` - List and Get only
- `serviceFees()` - List and Get only
- `securityGroups()` - List and Get only
- `teams()` - List and Get only
- `currencies()` - List, Get, and Update only (no Create/Delete)
- `defaults()` - Get only

### Filtering and Ordering

```php
use Simpro\PhpSdk\Simpro\Query\Search;

// Filter by name
$webhooks = $connector->setup(companyId: 0)->webhooks()->list()
    ->search(Search::make()->column('Name')->find('Invoice'))
    ->items();

// Order results
$taxCodes = $connector->setup(companyId: 0)->taxCodes()->list()
    ->orderBy('Name')
    ->items();
```

## Examples

### Sync Tax Codes to Local Database

```php
$taxCodes = $connector->setup(companyId: 0)->taxCodes()->list()->all();

foreach ($taxCodes as $taxCode) {
    LocalTaxCode::updateOrCreate(
        ['simpro_id' => $taxCode->id],
        [
            'name' => $taxCode->name,
            'code' => $taxCode->code,
            'rate' => $taxCode->rate,
        ]
    );
}
```

### Configure Webhook for Job Events

```php
$webhookId = $connector->setup(companyId: 0)->webhooks()->create([
    'URL' => 'https://yourapp.com/api/simpro/webhooks',
    'Event' => 'job.created',
    'Secret' => 'your-webhook-secret',
]);

echo "Webhook created with ID: {$webhookId}\n";
```

### Get All Custom Field Definitions

```php
$allCustomFields = [];

$entityTypes = [
    'customers', 'jobs', 'quotes', 'assets', 'employees',
    'contractors', 'invoices', 'leads', 'sites', 'suppliers',
];

foreach ($entityTypes as $type) {
    $fields = $connector->setup(companyId: 0)->customFields()->{$type}()->list()->all();
    $allCustomFields[$type] = $fields;
}

// Now $allCustomFields contains all custom field definitions by entity type
```

### Export Labor Rates

```php
$rates = $connector->setup(companyId: 0)->labor()->laborRates()->list()->all();

$csv = "ID,Name,Rate,Type\n";
foreach ($rates as $rate) {
    $csv .= implode(',', [
        $rate->id,
        '"' . str_replace('"', '""', $rate->name ?? '') . '"',
        $rate->rate ?? 0,
        $rate->type ?? '',
    ]) . "\n";
}

file_put_contents('labor_rates.csv', $csv);
```
