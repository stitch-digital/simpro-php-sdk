# Recurring Invoices Resource

A recurring invoice is a template Simpro raises invoices from on a schedule: weekly, monthly or yearly, in advance or in arrears, optionally bound to a customer contract and carrying its own inflation rate. It is "a regular financial record independent of any jobs", which makes it the right object for a subscription or monitoring charge.

The template's prices live on cost-centre line items under its sections, so a complete template is built in three steps: the header, a section with one cost centre per Simpro cost centre, and the line items on each. See [Recurring Invoice Nested Resources](./recurring-invoices/README.md) for the sections, cost centres and line items.

## Listing Recurring Invoices

### Basic List

The basic `list()` returns lightweight `RecurringInvoiceListItem` objects (ID, description and total):

```php
$templates = $connector->recurringInvoices(companyId: 0)->list();

foreach ($templates->items() as $template) {
    echo "{$template->id}: {$template->description} ({$template->total->incTax})\n";
}
```

### Detailed List

`listDetailed()` requests every column and hydrates full `RecurringInvoice` DTOs:

```php
$templates = $connector->recurringInvoices(companyId: 0)->listDetailed();

foreach ($templates->items() as $template) {
    echo "{$template->id}: {$template->name} every {$template->recurringQuantity} {$template->recurringFrequency->value}\n";
}
```

### Filtering

```php
use Simpro\PhpSdk\Simpro\Query\Search;

// Live templates for one customer
$templates = $connector->recurringInvoices(companyId: 0)->listDetailed()
    ->search(Search::make()->column('Customer.ID')->equals(2048))
    ->orderBy('NextRecurringDate')
    ->all();

// Array filters are passed straight through as query parameters
$templates = $connector->recurringInvoices(companyId: 0)->list(['Archived' => 'false'])->all();
```

## Getting a Single Recurring Invoice

```php
$template = $connector->recurringInvoices(companyId: 0)->get(recurringInvoiceId: 501);

echo "{$template->name} for {$template->customer->givenName} {$template->customer->familyName}\n";
echo "Next invoice: {$template->nextRecurringDate?->format('Y-m-d')}\n";
echo "Inflation: {$template->inflationRate}% on {$template->inflationAnniversaryDate?->format('Y-m-d')}\n";

// Only the columns you need
$template = $connector->recurringInvoices(companyId: 0)->get(501, ['ID', 'Name', 'NextRecurringDate']);
```

## Creating a Recurring Invoice

`Type` and `Site` are required by Simpro. The IDs are the customer's, the contract's, the site's and the payment term's.

```php
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\InvoiceDatePeriod;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\RecurringFrequency;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\RecurringInvoiceType;

$recurringInvoiceId = $connector->recurringInvoices(companyId: 0)->create([
    'Type' => RecurringInvoiceType::Service->value,
    'Customer' => 2048,
    'CustomerContract' => 77,
    'Site' => 9001,
    'Name' => 'Qolsys subscription',
    'RecurringStartDate' => '2026-12-01',
    'RecurringFrequency' => RecurringFrequency::Month->value,
    'RecurringQuantity' => 1,
    'InvoiceDatePeriod' => InvoiceDatePeriod::Advance->value,
    'PaymentTermID' => 3,
    'InflationRate' => 4,
    'InflationAnniversaryDate' => '2027-12-01T00:00:00+00:00',
]);
```

Then add a section, a cost centre and the lines:

```php
$template = $connector->recurringInvoices(companyId: 0)->recurringInvoice($recurringInvoiceId);

$sectionId = $template->sections()->create(['DisplayOrder' => 1]);

$costCenterId = $template->section($sectionId)->costCenters()->create([
    'CostCenter' => 458,
]);

$template->section($sectionId)->costCenter($costCenterId)->oneOffs()->create([
    'Type' => 'Material',
    'Description' => 'Qolsys subscription',
    'EstimatedCost' => 12.00,
    'SellPriceExDiscount' => 18.75,
    'SalesTaxCode' => 6,
    'IncomeAccount' => 40,
    'Total' => ['Qty' => 1],
]);
```

### Create Fields

| Field | Type | Notes |
|-------|------|-------|
| `Type` | `Project\|Service\|Prepaid` | Required |
| `Site` | `int` | Required. Site ID |
| `Customer` | `int` | Customer ID |
| `CustomerContract` | `int` | Contract ID. Once the contract expires, Simpro raises no more invoices from the template |
| `CustomerContact`, `SiteContact` | `int` | Contact IDs |
| `AdditionalContacts` | `int[]` | Contact IDs |
| `Name`, `OrderNo`, `Description`, `Notes` | `string` | Description and Notes accept HTML |
| `RecurringStartDate` | `YYYY-MM-DD` | |
| `RecurringFrequency` | `week\|month\|year` | |
| `RecurringQuantity` | `int` | The *N* in "every N weeks/months/years" |
| `InvoiceDatePeriod` | `Advance\|Arrears` | |
| `PaymentTermID` | `int` | Preferred over the deprecated `PaymentTerms{Days, Type}` |
| `Tags`, `Technicians` | `int[]` | |
| `Salesperson`, `ProjectManager`, `PricingTier`, `LaborFitTime`, `ItemTaxCode`, `LabourTaxCode` | `int` | IDs |
| `LaborOverhead`, `MaterialMarkup` | `float` | Null uses the system default |
| `InflationRate` | `float` | Percentage applied to the template's prices on the anniversary |
| `InflationAnniversaryDate` | RFC 3339 | Cannot be in the past; rolls on 12 months each time it is applied |

`PATCH` accepts the same fields except `Type`, plus `NextRecurringDate` and `LaborType`.

## Updating a Recurring Invoice

```php
$response = $connector->recurringInvoices(companyId: 0)->update(501, [
    'InflationRate' => 4.5,
    'NextRecurringDate' => '2027-02-01',
]);
```

Simpro's help centre warns that editing a template which has already generated invoices archives the original and creates a new template. Read the template back if you store its ID.

## Deleting a Recurring Invoice

```php
$response = $connector->recurringInvoices(companyId: 0)->delete(501);
```

## Bulk Operations

```php
$result = $connector->recurringInvoices(companyId: 0)->bulkCreate([
    ['Type' => 'Service', 'Site' => 9001, 'Customer' => 2048],
    ['Type' => 'Service', 'Site' => 9002, 'Customer' => 2049],
]);

$result = $connector->recurringInvoices(companyId: 0)->bulkUpdate([
    ['ID' => 501, 'InflationRate' => 4],
    ['ID' => 502, 'InflationRate' => 4],
]);

$result = $connector->recurringInvoices(companyId: 0)->bulkDelete([501, 502]);
```

## Enums

| Enum | Cases |
|------|-------|
| `RecurringInvoiceType` | `Project`, `Service`, `Prepaid` |
| `RecurringFrequency` | `Week` (`week`), `Month` (`month`), `Year` (`year`) |
| `InvoiceDatePeriod` | `Advance`, `Arrears` |

The DTOs hydrate these with `tryFrom`, so a value Simpro adds later reads as `null` rather than throwing.

## Response Structure

### RecurringInvoiceListItem

| Property | Type |
|----------|------|
| `id` | `int` |
| `description` | `?string` |
| `total` | `?JobTotal` (`exTax`, `tax`, `incTax`) |

### RecurringInvoice

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | |
| `type` | `?RecurringInvoiceType` | |
| `customer` | `?RecurringInvoiceCustomer` | `id`, `companyName`, `givenName`, `familyName` |
| `customerContract` | `?RecurringInvoiceContract` | `id`, `name`, `startDate`, `endDate`, `contractNo` |
| `customerContact`, `siteContact` | `?RecurringInvoiceContact` | `id`, `givenName`, `familyName` |
| `additionalContacts` | `?RecurringInvoiceContact[]` | |
| `site` | `?Reference` | |
| `requestNo` | `?string` | Deprecated by Simpro |
| `name`, `orderNo`, `description`, `notes` | `?string` | |
| `recurringStartDate`, `nextRecurringDate` | `?DateTimeImmutable` | |
| `recurringQuantity` | `?int` | |
| `invoiceDatePeriod` | `?InvoiceDatePeriod` | |
| `recurringFrequency` | `?RecurringFrequency` | |
| `tags` | `?Reference[]` | |
| `paymentTermId` | `?int` | |
| `paymentTerms` | `?RecurringInvoicePaymentTerms` | `days`, `type` (deprecated pair) |
| `salesperson`, `projectManager` | `?StaffReference` | |
| `technicians` | `?StaffReference[]` | |
| `technician` | `?StaffReference` | Deprecated by Simpro in favour of `technicians` |
| `pricingTier` | `?PricingTier` | `id`, `name`, `defaultMarkup` |
| `laborOverhead` | `?float` | |
| `laborType` | `?Reference` | |
| `laborFitTime` | `?FitTime` | |
| `itemTaxCode`, `labourTaxCode` | `?TaxCode` | |
| `materialMarkup` | `?float` | |
| `inflationRate` | `?float` | |
| `inflationAnniversaryDate` | `?DateTimeImmutable` | |
| `dateModified` | `?DateTimeImmutable` | |
| `total` | `?JobTotal` | |
| `totals` | `?JobTotals` | Estimate and revised cost, markup and profit breakdown |
| `customFields` | `?CustomField[]` | |
| `archived`, `removed` | `?bool` | |

## What the API does not expose

The help centre's form has an end date, a "repeat on" pattern, an end-of-month flag and the **Automated Invoicing** toggle (generate and email each invoice). None of them is in the API schema: an API-created template inherits whatever Simpro defaults to, and only the UI can change it. Skip-a-period and archive are UI list actions too; `archived` is read-only.

## Nested Resources

```php
$template = $connector->recurringInvoices(companyId: 0)->recurringInvoice(501);

$template->sections()->list();
$template->section(61)->costCenters()->list();
$template->section(61)->costCenter(7001)->oneOffs()->list();
$template->section(61)->costCenter(7001)->serviceFees()->list();
$template->section(61)->costCenter(7001)->catalogs()->list();
$template->section(61)->costCenter(7001)->labor()->list();
$template->section(61)->costCenter(7001)->prebuilds()->list();
```

See [Recurring Invoice Nested Resources](./recurring-invoices/README.md). For a flat list of cost centres across every template, see [Recurring Invoice Cost Centers](./recurring-invoice-cost-centers-resource.md).
