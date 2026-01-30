# Simpro API Endpoints Implementation Tracker

> **Last Updated:** 2026-01-30 | **Progress:** 211/1325 endpoints (15.9%)

This file serves as the single source of truth for SDK development progress, architecture decisions, and documentation requirements.

---

## Table of Contents

1. [Quick Status](#quick-status)
2. [Documentation Status](#documentation-status)
3. [Architecture Reference](#architecture-reference)
4. [Implementation Plan](#implementation-plan)
5. [Revision Notes](#revision-notes-validation-review)
6. [Endpoint Checklist](#endpoint-checklist)

---

## Quick Status

### Implementation Progress

| Category | Implemented | Remaining | Total |
|----------|-------------|-----------|-------|
| Companies | 7 | ~195 | ~202 |
| Jobs | 121 | ~0 | ~121 |
| Customers | 41 | ~9 | ~50 |
| Quotes | 5 | ~35 | ~40 |
| Invoices | 5 | ~20 | ~25 |
| Schedules | 2 | ~28 | ~30 |
| Employees | 29 | ~0 | ~29 |
| CurrentUser | 1 | 0 | 1 |
| Info | 1 | 0 | 1 |
| **Other** | 0 | ~891 | ~891 |
| **Total** | **211** | **1114** | **1325** |

### Implemented Resources Summary

| Resource | Methods | Request Classes | DTOs |
|----------|---------|-----------------|------|
| Info | `get()` + 9 convenience methods | 1 | 1 |
| Companies | `list()`, `listDetailed()`, `get()`, `getDefault()`, `findByName()` | 3 | 6 |
| ActivitySchedules | `list()`, `get()`, `create()`, `update()`, `delete()` | 5 | 2 |
| Jobs | `list()`, `listDetailed()`, `get()`, `create()`, `update()`, `delete()` + nested resources | 126 | 47+ |
| Customers | `list()`, `listCompanies()`, `listCompaniesDetailed()`, `getCompany()`, `createCompany()`, `updateCompany()`, `deleteCompany()` | 41 | 30+ |
| ↳ Individuals | `list()`, `listDetailed()`, `get()`, `create()`, `update()`, `delete()` | (included above) | |
| ↳ Contacts | `list()`, `listDetailed()`, `get()`, `create()`, `update()`, `delete()` + customFields | (included above) | |
| ↳ Contracts | `list()`, `listDetailed()`, `get()`, `create()`, `update()`, `delete()` + inflation, laborRates, serviceLevels, customFields | (included above) | |
| Quotes | `list()`, `get()`, `create()`, `update()`, `delete()` | 5 | 5 |
| Invoices | `list()`, `get()`, `create()`, `update()`, `delete()` | 5 | 5 |
| Schedules | `list()`, `get()` | 2 | 4 |
| Employees | `list()`, `get()`, `create()`, `update()`, `delete()` + nested resources | 29 | 8 |
| CurrentUser | `get()` | 1 | 1 |

---

## Documentation Status

Track documentation for each implemented resource:

| Resource | Documentation File | README Listed | Status |
|----------|-------------------|---------------|--------|
| Info | [x] `docs/info-resource.md` | [x] | COMPLETE |
| Companies | [x] `docs/companies-resource.md` | [x] | COMPLETE |
| ActivitySchedules | [ ] `docs/activity-schedules-resource.md` | [ ] | PENDING |
| Jobs | [x] `docs/jobs-resource.md` | [x] | COMPLETE |
| Customers | [x] `docs/customers-resource.md` | [x] | COMPLETE |
| Quotes | [x] `docs/quotes-resource.md` | [x] | COMPLETE |
| Invoices | [x] `docs/invoices-resource.md` | [x] | COMPLETE |
| Schedules | [x] `docs/schedules-resource.md` | [x] | COMPLETE |
| Employees | [x] `docs/employees-resource.md` | [x] | NEEDS UPDATE (nested resources) |
| CurrentUser | [x] `docs/current-user-resource.md` | [x] | COMPLETE |

### Documentation Workflow

When implementing a new resource:

1. **Before Implementation** - Mark resource as "IN PROGRESS" in this file
2. **During Implementation** - Update endpoint checkboxes as completed
3. **After Implementation**:
   - Create `docs/{resource}-resource.md`
   - Update README.md Available Resources section
   - Mark documentation checkbox complete above
   - Update implementation summary counts

---

## Architecture Reference

### Final File Structure

```
src/
├── Connectors/
│   ├── AbstractSimproConnector.php  # Base class with pagination, error handling
│   ├── SimproApiKeyConnector.php    # Server-to-server integrations
│   └── SimproOAuthConnector.php     # Web applications with user auth
├── Concerns/
│   └── Supports{Resource}Endpoints.php  # One trait per resource
├── Resources/
│   └── {Resource}Resource.php  # One class per resource
├── Requests/
│   ├── Abstract/  # Base request classes
│   │   ├── AbstractListRequest.php
│   │   ├── AbstractGetRequest.php
│   │   ├── AbstractCreateRequest.php
│   │   ├── AbstractUpdateRequest.php
│   │   └── AbstractDeleteRequest.php
│   └── {Resource}/  # One folder per resource
│       ├── List{Resource}Request.php
│       ├── Get{Resource}Request.php
│       ├── Create{Resource}Request.php
│       ├── Update{Resource}Request.php
│       └── Delete{Resource}Request.php
├── Data/
│   ├── Common/  # Shared DTOs
│   │   ├── Money.php
│   │   ├── Reference.php
│   │   ├── Address.php
│   │   ├── CustomField.php
│   │   ├── Attachment.php
│   │   └── Note.php
│   └── {Resource}/  # One folder per resource
│       ├── {Resource}.php       # Detailed DTO
│       └── {Resource}ListItem.php  # List DTO
├── Query/  # Query builder components
│   ├── QueryBuilder.php
│   ├── Search.php
│   ├── SearchLogic.php
│   └── SortDirection.php
└── Support/  # Utility classes
    └── PathBuilder.php
```

### Layer Responsibilities

| Layer | Responsibility | Access Pattern |
|-------|---------------|----------------|
| **Connector** | HTTP client, authentication, base URL | Entry point |
| **Concern** | Resource method registration on connector | `$connector->jobs()` |
| **Resource** | API operation methods | `->list()`, `->get($id)` |
| **Request** | Endpoint definition, DTO transformation | Internal |
| **DTO** | Immutable data objects | Return values |
| **QueryBuilder** | Fluent search/filter/order | List operations |

### Common Patterns

**CRUD Resource Pattern:**
```php
$connector->jobs(0)->list();           // QueryBuilder
$connector->jobs(0)->get($id);         // Job DTO
$connector->jobs(0)->create($data);    // int (created ID)
$connector->jobs(0)->update($id, $data); // Response
$connector->jobs(0)->delete($id);      // Response
```

**QueryBuilder Pattern:**
```php
$connector->jobs(0)->list()
    ->search(Search::make()->column('Name')->find('Project'))
    ->where('Status', '=', 'Active')
    ->orderByDesc('DateIssued')
    ->first();
```

---

## CRITICAL: DTO Field Specification

> **WARNING: NEVER GUESS DTO FIELDS**
>
> All DTO (Data Transfer Object) fields **MUST** come from one of these sources:
>
> 1. **Primary Source:** The `swagger 2.json` file in the repository root
> 2. **Secondary Source:** Explicit user instructions
>
> **DO NOT** make educated guesses about field names, types, or structures based on:
> - Similar endpoints in other APIs
> - Common naming conventions
> - Fields that "should" exist based on context
>
> **When implementing a new DTO:**
> 1. Find the exact endpoint in `swagger 2.json`
> 2. Read the `responses.200.schema.properties` section
> 3. Map each swagger field to the DTO property exactly
> 4. If unsure about any field, **ASK** rather than guess
>
> **Example: Finding swagger schema for employees**
> ```bash
> # Search for the endpoint
> grep -n "/api/v1.0/companies/{companyID}/employees/" "swagger 2.json"
> # Read the schema from the returned line number
> ```
>
> This rule exists because guessed fields will:
> - Cause runtime errors when the API returns unexpected data
> - Create maintenance burden when fixing incorrect mappings
> - Break type safety that DTOs are designed to provide

---

## Implementation Plan

This section outlines the phased approach for implementing the remaining endpoints.

### User Preferences

- **Priority:** Jobs & Scheduling + Customers & Sales (highest priority)
- **Approach:** Manual implementation following established patterns
- **Operations:** Full CRUD together (GET, POST, PATCH, DELETE for each resource)

### Architecture Patterns

| Layer | Pattern | Example |
|-------|---------|---------|
| Resources | `final class {Name}Resource extends BaseResource` | `CompanyResource.php` |
| Requests | `final class {Op}{Name}Request extends Request` | `ListCompaniesRequest.php` |
| DTOs | `final readonly class` with `fromArray()`/`fromResponse()` | `Company.php` |
| Concerns | `Supports{Name}Endpoints` trait | `SupportsCompaniesEndpoints.php` |

### Infrastructure to Create

**Abstract Base Request Classes** (`src/Requests/Abstract/`)
- `AbstractListRequest.php` - Base for all list endpoints with Paginatable
- `AbstractGetRequest.php` - Base for single-item GET endpoints
- `AbstractCreateRequest.php` - Base for POST endpoints
- `AbstractUpdateRequest.php` - Base for PATCH endpoints
- `AbstractDeleteRequest.php` - Base for DELETE endpoints

**Common DTOs** (`src/Data/Common/`)
- `Money.php` - For {ExTax, Tax, IncTax} structures
- `Reference.php` - For {ID, Name} pairs
- `Address.php` - Reusable address structure
- `CustomField.php` - Generic custom field structure
- `Attachment.php` - Generic attachment structure
- `Note.php` - Generic note structure

**Shared Resource Traits** (`src/Resources/Concerns/`)
- `HasCustomFields.php` - Adds customFields() method
- `HasAttachments.php` - Adds attachments() method
- `HasNotes.php` - Adds notes() method

**Path Builder Utility** (`src/Support/PathBuilder.php`)
- Fluent interface for building nested endpoint paths

### Implementation Tiers

#### Tier 1: Core Business (~230 endpoints) - HIGH PRIORITY

| Resource | Endpoints | Description |
|----------|-----------|-------------|
| Jobs | ~60 | Primary work unit - sections, costCenters, attachments |
| Customers | ~50 | Companies, individuals, contacts, contracts |
| Quotes | ~40 | Quote sections, costCenters, approvals |
| Invoices | ~25 | Customer invoices, credit notes |
| Schedules | ~30 | Activity and job scheduling |
| Employees/Staff | ~25 | User management |

#### Tier 2: Supporting Operations (~170 endpoints)

| Resource | Endpoints | Description |
|----------|-----------|-------------|
| Catalogs | ~30 | Products/services, pricing |
| Sites | ~25 | Location management, assets |
| Contacts | ~20 | Contact management |
| CustomerContracts | ~40 | Recurring revenue |
| Leads | ~20 | Sales pipeline |
| Notes | ~34 | Cross-resource notes |

#### Tier 3: Financial & Inventory (~155 endpoints)

| Resource | Endpoints | Description |
|----------|-----------|-------------|
| Accounts (Payable/Receivable) | ~30 | Accounting integration |
| Vendors/VendorOrders | ~50 | Supply chain |
| CustomerPayments | ~15 | Payment tracking |
| CreditNotes | ~20 | Financial adjustments |
| InventoryJournals | ~15 | Stock management |
| StockAllocations/Transfers | ~25 | Warehouse operations |

#### Tier 4: Advanced Features (~240 endpoints)

| Resource | Endpoints | Description |
|----------|-----------|-------------|
| Contractors | ~40 | Subcontractor management |
| RecurringJobs/Invoices | ~50 | Automation |
| CustomerAssets | ~30 | Asset tracking |
| Reports | ~20 | Business intelligence |
| Setup | ~100 | System configuration |

#### Tier 5: Remaining Resources (~527 endpoints)

- PlantTypes, Prebuilds, PrebuildGroups
- TakeOffTemplates, TakeOffTemplateGroups
- DataFeedEvents, StorageDevices
- ActivitySchedules, Logs, Tasks

### Phase Breakdown

#### Phase 1: Foundation
- [x] Abstract base request classes
- [x] Common DTOs (Money, Reference, Address, CustomField, Attachment, Note)
- [x] Shared resource traits (HasCustomFields, HasAttachments, HasNotes)
- [x] PathBuilder utility
- [x] CurrentUser endpoint (1 endpoint)

#### Phase 2: Jobs (~121 endpoints) ✅ COMPLETE
- [x] Core Jobs CRUD (List, ListDetailed, Get, Create, Update, Delete)
- [x] Job Sections with CostCenters (5 endpoints each)
- [x] Job Attachments (files + folders) - 10 endpoints
- [x] Job CustomFields - 3 endpoints
- [x] Job Notes - 4 endpoints
- [x] Job Lock - 2 endpoints
- [x] Job Tasks - 2 endpoints
- [x] Job Timelines - 1 endpoint
- [x] Cost Center sub-resources (assets, catalogs, contractorJobs, labor, oneOffs, prebuilds, schedules, serviceFees, stock, tasks, workOrders, lock) - 57 endpoints
- [x] Deep nesting (ContractorJobs nested, WorkOrders nested, Test Results, Mobile Signatures) - 30 endpoints
- [x] Scope-based fluent API for navigation up to 6 levels deep

**Note:** Job Invoices (5 endpoints) and Customer Invoices (2 endpoints) are DEPRECATED and intentionally NOT implemented. Use `/accounts/receivable/invoices/` instead.

#### Phase 3: Schedules & Employees (~55 endpoints) ✅ COMPLETE
- [x] ActivitySchedules (5 endpoints)
- [x] Employees CRUD (List, Get, Create, Update, Delete)
- [x] Schedules (List, Get)
- [x] Employee Timesheets (1 endpoint)
- [x] Employee Attachments Files (5 endpoints)
- [x] Employee Attachments Folders (5 endpoints)
- [x] Employee CustomFields (3 endpoints)
- [x] Employee Licences (5 endpoints)
- [x] Employee Licence Attachments (5 endpoints)

#### Phase 4: Customers (~50 endpoints)
- [x] Customer companies (List, Get, Create, Update, Delete)
- [x] General customers list
- [ ] Customer individuals
- [ ] Customer contacts
- [ ] Customer contracts (inflation, laborRates, serviceLevels)

#### Phase 5: Quotes (~40 endpoints)
- [x] Quote CRUD (List, Get, Create, Update, Delete)
- [ ] Quote sections
- [ ] Quote costCenters
- [ ] Quote approvals

#### Phase 6+: Remaining Tiers (~1100+ endpoints)
- [x] Invoices CRUD (List, Get, Create, Update, Delete)
- [ ] Payments
- [ ] Vendors, Contractors
- [ ] Setup, Reports
- [ ] All remaining resources

### Verification Steps

After each phase:
1. Run `composer format` - Code style check
2. Run `composer analyse` - PHPStan level 5
3. Run `composer test` - All tests pass

---

## Revision Notes (Validation Review)

This section documents issues discovered during validation review and their resolution status.

### 1. Attachment DTO - Context-Specific Fields ✅ RESOLVED

**Issue:** The common `Attachment` DTO (`src/Data/Common/Attachment.php`) didn't account for all context-specific fields.

**Resolution:** Updated `Attachment.php` with all context-specific fields:
- Added `public` (bool) - For Job/Quote/Lead attachments (Customer Portal visibility)
- Added `email` (bool) - For Job/Quote/Lead attachments (forms tab availability)
- Added `default` (bool) - For Catalog/Employee/Contractor attachments (default signature)
- Added `base64Data` (string) - Optional, returned when `?display=Base64` is used
- Added `fileSizeBytes` (int) - Correct API field name
- Added `dateAdded` (DateTimeImmutable) - Correct API field name
- Added `addedBy` (StaffReference) - With type/typeId for employee/contractor/plant
- Added helper methods: `isImage()`, `extension()`, `isPublic()`, `isEmailEnabled()`, `isDefault()`, `hasBase64Data()`

### 2. Note DTO - Field Name Verification ✅ RESOLVED

**Resolution:** Completely rewrote `Note.php` based on actual swagger schema:
- Correct fields: `id`, `subject`, `note`, `dateCreated`, `followUpDate`
- Added `assignTo` (StaffReference) - Task assignment
- Added `submittedBy` (StaffReference) - Note creator
- Added `reference` (NoteReference) - Job/Quote/etc reference with type, number, text
- Added `attachments` (NoteAttachment[]) - Note attachments with href/filename
- Added helper methods: `hasContent()`, `preview()`, `hasFollowUp()`, `hasAttachments()`

### 3. Money/Total DTO - TaxCode Field ✅ RESOLVED

**Resolution:** Updated `Money.php` with optional TaxCode:
- Added `taxCode` (TaxCode|null) property
- Created `TaxCode.php` DTO with `id`, `code`, `type`, `rate` fields
- Added `hasTaxCode()` helper method
- TaxCode includes `isSingle()` and `isCompound()` type checks

### 4. Endpoint Accuracy Verification

**Status:** ENDPOINTS.md contains 1325 total endpoints (32 implemented, 1293 remaining)

**Catalog Currencies Note:**
- The `/catalogs/{catalogID}/currencies/{currencyID}` endpoint only supports DELETE (not GET/POST)
- This is intentional - currencies are defined in Setup, this just removes the association
- The main currency endpoints are under `/setup/currencies/`

**Pending (User Action):**
- [ ] User to validate a sample of endpoints against live Postman collection
- [ ] Mark any deprecated/removed endpoints if discovered

### 5. FileSizeBytes vs Size Field Naming ✅ RESOLVED

**Resolution:** Attachment DTO now uses correct API field names:
- `fileSizeBytes` - Maps from `FileSizeBytes` or `Size` in API response
- `dateAdded` - Maps from `DateAdded` or `DateCreated` in API response
- `filename` - Maps from `Filename` or `FileName` in API response
- `mimeType` - Maps from `MimeType` or `ContentType` in API response

### 6. AddedBy/CreatedBy Staff Reference ✅ RESOLVED

**Resolution:** Created `StaffReference.php` DTO:
- Fields: `id`, `name`, `type`, `typeId`
- Type helpers: `isEmployee()`, `isContractor()`, `isPlant()`
- Used in `Attachment.addedBy`, `Note.assignTo`, `Note.submittedBy`

### Additional DTOs Created

The following supporting DTOs were also created:
- `NoteReference.php` - Reference info for notes (type, number, text)
- `NoteAttachment.php` - Simplified attachment for notes (href, filename)

---

## Endpoint Checklist

This section contains the detailed checklist of all Simpro API endpoints and their implementation status.

### Companies

- [x] `GET /api/v1.0/companies/`
  - **Description**: List all companies.
  - **Parameters**: `search`?, `columns`?, `orderby`?
  - **Response**: Array of object

- [x] `GET /api/v1.0/companies/{companyID}`
  - **Description**: Retrieve details for a specific company.
  - **Parameters**: `companyID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}`
  - **Description**: Update a company.
  - **Parameters**: `companyID`
  - **Response**: No Content

### Nested: Accounts

#### Journals

- [ ] `GET /api/v1.0/companies/{companyID}/accounts/journals/`
  - **Description**: List all journals.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

#### Payable > contacts

- [ ] `GET /api/v1.0/companies/{companyID}/accounts/payable/contacts/`
  - **Description**: List all payable contacts.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/accounts/payable/contacts/{contactID}`
  - **Description**: Retrieve details for a specific payable contact.
  - **Parameters**: `companyID`, `contactID`, `columns`?
  - **Response**: object

#### Payable > invoices

- [ ] `GET /api/v1.0/companies/{companyID}/accounts/payable/invoices/`
  - **Description**: List all payable invoices.
  - **Parameters**: `companyID`, `search`?, `columns`?
  - **Response**: Array of object

#### Receivable > contacts

- [ ] `GET /api/v1.0/companies/{companyID}/accounts/receivable/contacts/`
  - **Description**: List all receivable contacts.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/accounts/receivable/contacts/{contactID}`
  - **Description**: Retrieve details for a specific receivable contact.
  - **Parameters**: `companyID`, `contactID`, `columns`?
  - **Response**: object

#### Receivable > invoices

- [ ] `GET /api/v1.0/companies/{companyID}/accounts/receivable/invoices/`
  - **Description**: List all receivable invoices.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/accounts/receivable/invoices/{invoiceID}`
  - **Description**: Retrieve details for a specific receivable invoice.
  - **Parameters**: `companyID`, `invoiceID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/accounts/receivable/invoices/{invoiceID}`
  - **Description**: Update a receivable invoice.
  - **Parameters**: `companyID`, `invoiceID`
  - **Response**: No Content

#### Receivable > payments

- [ ] `GET /api/v1.0/companies/{companyID}/accounts/receivable/payments/`
  - **Description**: List all receivable payments.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/accounts/receivable/payments/{paymentID}`
  - **Description**: Retrieve details for a specific receivable payment.
  - **Parameters**: `companyID`, `paymentID`, `columns`?
  - **Response**: object

### Nested: ActivitySchedules

- [x] `GET /api/v1.0/companies/{companyID}/activitySchedules/`
  - **Description**: List all activity schedules.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [x] `POST /api/v1.0/companies/{companyID}/activitySchedules/`
  - **Description**: Create a new activity schedule.
  - **Parameters**: `companyID`
  - **Response**: Created

- [x] `GET /api/v1.0/companies/{companyID}/activitySchedules/{scheduleID}`
  - **Description**: Retrieve details for a specific activity schedule.
  - **Parameters**: `companyID`, `scheduleID`, `columns`?
  - **Response**: object

- [x] `PATCH /api/v1.0/companies/{companyID}/activitySchedules/{scheduleID}`
  - **Description**: Update a activity schedule.
  - **Parameters**: `companyID`, `scheduleID`
  - **Response**: No Content

- [x] `DELETE /api/v1.0/companies/{companyID}/activitySchedules/{scheduleID}`
  - **Description**: Delete a activity schedule.
  - **Parameters**: `companyID`, `scheduleID`
  - **Response**: No Content

### Nested: CatalogGroups

- [ ] `GET /api/v1.0/companies/{companyID}/catalogGroups/`
  - **Description**: List all catalog groups.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/catalogGroups/`
  - **Description**: Create a new catalog group.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/catalogGroups/{groupID}`
  - **Description**: Retrieve details for a specific catalog group.
  - **Parameters**: `companyID`, `groupID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/catalogGroups/{groupID}`
  - **Description**: Update a catalog group.
  - **Parameters**: `companyID`, `groupID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/catalogGroups/{groupID}`
  - **Description**: Delete a catalog group.
  - **Parameters**: `companyID`, `groupID`
  - **Response**: No Content

### Nested: Catalogs

- [ ] `GET /api/v1.0/companies/{companyID}/catalogs/`
  - **Description**: List all catalog items.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/catalogs/`
  - **Description**: Create a new catalog item.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/catalogs/{catalogID}`
  - **Description**: Retrieve details for a specific catalog item.
  - **Parameters**: `companyID`, `catalogID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/catalogs/{catalogID}`
  - **Description**: Update a catalog item.
  - **Parameters**: `companyID`, `catalogID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/catalogs/{catalogID}`
  - **Description**: Delete a catalog item.
  - **Parameters**: `companyID`, `catalogID`
  - **Response**: No Content

#### Attachments > files

- [ ] `GET /api/v1.0/companies/{companyID}/catalogs/{catalogID}/attachments/files/`
  - **Description**: List all catalog attachments.
  - **Parameters**: `companyID`, `catalogID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/catalogs/{catalogID}/attachments/files/`
  - **Description**: Create a new catalog attachment.
  - **Parameters**: `companyID`, `catalogID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/catalogs/{catalogID}/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific catalog attachment.
  - **Parameters**: `companyID`, `catalogID`, `fileID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/catalogs/{catalogID}/attachments/files/{fileID}`
  - **Description**: Update a catalog attachment.
  - **Parameters**: `companyID`, `catalogID`, `fileID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/catalogs/{catalogID}/attachments/files/{fileID}`
  - **Description**: Delete a catalog attachment.
  - **Parameters**: `companyID`, `catalogID`, `fileID`
  - **Response**: No Content

#### Attachments > folders

- [ ] `GET /api/v1.0/companies/{companyID}/catalogs/{catalogID}/attachments/folders/`
  - **Description**: List all catalog attachment folders.
  - **Parameters**: `companyID`, `catalogID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/catalogs/{catalogID}/attachments/folders/`
  - **Description**: Create a new catalog attachment folder.
  - **Parameters**: `companyID`, `catalogID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/catalogs/{catalogID}/attachments/folders/{folderID}`
  - **Description**: Retrieve details for a specific catalog attachment folder.
  - **Parameters**: `companyID`, `catalogID`, `folderID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/catalogs/{catalogID}/attachments/folders/{folderID}`
  - **Description**: Update a catalog attachment folder.
  - **Parameters**: `companyID`, `catalogID`, `folderID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/catalogs/{catalogID}/attachments/folders/{folderID}`
  - **Description**: Delete a catalog attachment folder.
  - **Parameters**: `companyID`, `catalogID`, `folderID`
  - **Response**: No Content

#### Currencies

- [ ] `DELETE /api/v1.0/companies/{companyID}/catalogs/{catalogID}/currencies/{currencyID}`
  - **Description**: Delete a catalog currencies.
  - **Parameters**: `companyID`, `catalogID`, `currencyID`
  - **Response**: No Content

#### CustomFields

- [ ] `GET /api/v1.0/companies/{companyID}/catalogs/{catalogID}/customFields/`
  - **Description**: List all catalog custom fields.
  - **Parameters**: `companyID`, `catalogID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/catalogs/{catalogID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific catalog custom field.
  - **Parameters**: `companyID`, `catalogID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/catalogs/{catalogID}/customFields/{customFieldID}`
  - **Description**: Update a catalog custom field.
  - **Parameters**: `companyID`, `catalogID`, `customFieldID`
  - **Response**: No Content

#### Inventories

- [ ] `GET /api/v1.0/companies/{companyID}/catalogs/{catalogID}/inventories/`
  - **Description**: List all catalog inventories.
  - **Parameters**: `companyID`, `catalogID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/catalogs/{catalogID}/inventories/{storageDeviceID}`
  - **Description**: Retrieve details for a specific catalog inventory.
  - **Parameters**: `companyID`, `catalogID`, `storageDeviceID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/catalogs/{catalogID}/inventories/{storageDeviceID}`
  - **Description**: Update a catalog inventory.
  - **Parameters**: `companyID`, `catalogID`, `storageDeviceID`
  - **Response**: No Content

#### MergeCatalogs

- [ ] `POST /api/v1.0/companies/{companyID}/catalogs/{catalogID}/mergeCatalogs/`
  - **Description**: Create a new merge catalog.
  - **Parameters**: `companyID`, `catalogID`
  - **Response**: Unknown

#### PricingTiers

- [ ] `GET /api/v1.0/companies/{companyID}/catalogs/{catalogID}/pricingTiers/`
  - **Description**: List all catalog pricingtiers.
  - **Parameters**: `companyID`, `catalogID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/catalogs/{catalogID}/pricingTiers/{pricingTierID}`
  - **Description**: Retrieve details for a specific catalog pricingtier.
  - **Parameters**: `companyID`, `catalogID`, `pricingTierID`, `columns`?
  - **Response**: object

#### PricingTiers > prices

- [ ] `GET /api/v1.0/companies/{companyID}/catalogs/{catalogID}/pricingTiers/{pricingTierID}/prices/`
  - **Description**: List all catalog pricingtier prices.
  - **Parameters**: `companyID`, `catalogID`, `pricingTierID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/catalogs/{catalogID}/pricingTiers/{pricingTierID}/prices/`
  - **Description**: Create a new catalog pricingtier price.
  - **Parameters**: `companyID`, `catalogID`, `pricingTierID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/catalogs/{catalogID}/pricingTiers/{pricingTierID}/prices/{priceID}`
  - **Description**: Retrieve details for a specific catalog pricingtier price.
  - **Parameters**: `companyID`, `catalogID`, `pricingTierID`, `priceID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/catalogs/{catalogID}/pricingTiers/{pricingTierID}/prices/{priceID}`
  - **Description**: Update a catalog pricingtier price.
  - **Parameters**: `companyID`, `catalogID`, `pricingTierID`, `priceID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/catalogs/{catalogID}/pricingTiers/{pricingTierID}/prices/{priceID}`
  - **Description**: Delete a catalog pricingtier price.
  - **Parameters**: `companyID`, `catalogID`, `pricingTierID`, `priceID`
  - **Response**: No Content

#### Vendors

- [ ] `GET /api/v1.0/companies/{companyID}/catalogs/{catalogID}/vendors/`
  - **Description**: List all catalog item vendors.
  - **Parameters**: `companyID`, `catalogID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/catalogs/{catalogID}/vendors/`
  - **Description**: Create a new catalog item vendor.
  - **Parameters**: `companyID`, `catalogID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/catalogs/{catalogID}/vendors/{catalogVendorID}`
  - **Description**: Retrieve details for a specific catalog item vendor.
  - **Parameters**: `companyID`, `catalogID`, `catalogVendorID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/catalogs/{catalogID}/vendors/{catalogVendorID}`
  - **Description**: Update a catalog item vendor.
  - **Parameters**: `companyID`, `catalogID`, `catalogVendorID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/catalogs/{catalogID}/vendors/{catalogVendorID}`
  - **Description**: Delete a catalog item vendor.
  - **Parameters**: `companyID`, `catalogID`, `catalogVendorID`
  - **Response**: No Content

### Nested: Contacts

- [ ] `GET /api/v1.0/companies/{companyID}/contacts/`
  - **Description**: List all contacts.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/contacts/`
  - **Description**: Create a new contact.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/contacts/{contactID}`
  - **Description**: Retrieve details for a specific contact.
  - **Parameters**: `companyID`, `contactID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/contacts/{contactID}`
  - **Description**: Update a contact.
  - **Parameters**: `companyID`, `contactID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/contacts/{contactID}`
  - **Description**: Delete a contact.
  - **Parameters**: `companyID`, `contactID`
  - **Response**: No Content

#### CustomFields

- [ ] `GET /api/v1.0/companies/{companyID}/contacts/{contactID}/customFields/`
  - **Description**: List all custom fields.
  - **Parameters**: `companyID`, `contactID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/contacts/{contactID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific custom field.
  - **Parameters**: `companyID`, `contactID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/contacts/{contactID}/customFields/{customFieldID}`
  - **Description**: Update a custom field.
  - **Parameters**: `companyID`, `contactID`, `customFieldID`
  - **Response**: No Content

### Nested: ContractorInvoices

- [ ] `GET /api/v1.0/companies/{companyID}/contractorInvoices/`
  - **Description**: List all contractor invoices.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/contractorInvoices/`
  - **Description**: Create a new contractor invoice.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/contractorInvoices/{contractorInvoiceID}`
  - **Description**: Retrieve details for a specific contractor invoice.
  - **Parameters**: `companyID`, `contractorInvoiceID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/contractorInvoices/{contractorInvoiceID}`
  - **Description**: Update a contractor invoice.
  - **Parameters**: `companyID`, `contractorInvoiceID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/contractorInvoices/{contractorInvoiceID}`
  - **Description**: Delete a contractor invoice.
  - **Parameters**: `companyID`, `contractorInvoiceID`
  - **Response**: No Content

#### Attachments > files

- [ ] `GET /api/v1.0/companies/{companyID}/contractorInvoices/{contractorInvoiceID}/attachments/files/`
  - **Description**: List all contractor invoice attachments.
  - **Parameters**: `companyID`, `contractorInvoiceID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/contractorInvoices/{contractorInvoiceID}/attachments/files/`
  - **Description**: Create a new contractor invoice attachment.
  - **Parameters**: `companyID`, `contractorInvoiceID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/contractorInvoices/{contractorInvoiceID}/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific contractor invoice attachment.
  - **Parameters**: `companyID`, `contractorInvoiceID`, `fileID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/contractorInvoices/{contractorInvoiceID}/attachments/files/{fileID}`
  - **Description**: Update a contractor invoice attachment.
  - **Parameters**: `companyID`, `contractorInvoiceID`, `fileID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/contractorInvoices/{contractorInvoiceID}/attachments/files/{fileID}`
  - **Description**: Delete a contractor invoice attachment.
  - **Parameters**: `companyID`, `contractorInvoiceID`, `fileID`
  - **Response**: No Content

#### Attachments > folders

- [ ] `GET /api/v1.0/companies/{companyID}/contractorInvoices/{contractorInvoiceID}/attachments/folders/`
  - **Description**: List all contractor invoice attachment folders.
  - **Parameters**: `companyID`, `contractorInvoiceID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/contractorInvoices/{contractorInvoiceID}/attachments/folders/`
  - **Description**: Create a new contractor invoice attachment folder.
  - **Parameters**: `companyID`, `contractorInvoiceID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/contractorInvoices/{contractorInvoiceID}/attachments/folders/{folderID}`
  - **Description**: Retrieve details for a specific contractor invoice attachment folder.
  - **Parameters**: `companyID`, `contractorInvoiceID`, `folderID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/contractorInvoices/{contractorInvoiceID}/attachments/folders/{folderID}`
  - **Description**: Update a contractor invoice attachment folder.
  - **Parameters**: `companyID`, `contractorInvoiceID`, `folderID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/contractorInvoices/{contractorInvoiceID}/attachments/folders/{folderID}`
  - **Description**: Delete a contractor invoice attachment folder.
  - **Parameters**: `companyID`, `contractorInvoiceID`, `folderID`
  - **Response**: No Content

#### ContractorVariances

- [ ] `POST /api/v1.0/companies/{companyID}/contractorInvoices/{contractorInvoiceID}/contractorVariances/`
  - **Description**: Create a new contractor invoice variance.
  - **Parameters**: `companyID`, `contractorInvoiceID`
  - **Response**: Unknown

#### CustomFields

- [ ] `GET /api/v1.0/companies/{companyID}/contractorInvoices/{contractorInvoiceID}/customFields/`
  - **Description**: List all contractor invoice custom fields.
  - **Parameters**: `companyID`, `contractorInvoiceID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/contractorInvoices/{contractorInvoiceID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific contractor invoice custom field.
  - **Parameters**: `companyID`, `contractorInvoiceID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/contractorInvoices/{contractorInvoiceID}/customFields/{customFieldID}`
  - **Description**: Update a contractor invoice custom field.
  - **Parameters**: `companyID`, `contractorInvoiceID`, `customFieldID`
  - **Response**: No Content

#### Variances

- [ ] `DELETE /api/v1.0/companies/{companyID}/contractorInvoices/{contractorInvoiceID}/variances/{varianceID}`
  - **Description**: Delete a contractor invoice variance.
  - **Parameters**: `companyID`, `contractorInvoiceID`, `varianceID`
  - **Response**: No Content

### Nested: ContractorJobs

- [ ] `GET /api/v1.0/companies/{companyID}/contractorJobs/`
  - **Description**: List all contractor jobs.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/contractorJobs/{contractorJobID}`
  - **Description**: Retrieve details for a specific contractor job.
  - **Parameters**: `companyID`, `contractorJobID`, `columns`?
  - **Response**: object

#### CustomFields

- [ ] `GET /api/v1.0/companies/{companyID}/contractorJobs/{contractorJobID}/customFields/`
  - **Description**: List all contractor job custom fields.
  - **Parameters**: `companyID`, `contractorJobID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/contractorJobs/{contractorJobID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific contractor job custom field.
  - **Parameters**: `companyID`, `contractorJobID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/contractorJobs/{contractorJobID}/customFields/{customFieldID}`
  - **Description**: Update a contractor job custom field.
  - **Parameters**: `companyID`, `contractorJobID`, `customFieldID`
  - **Response**: No Content

### Nested: ContractorVariances

- [ ] `GET /api/v1.0/companies/{companyID}/contractorVariances/`
  - **Description**: List all contractor variances.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/contractorVariances/`
  - **Description**: Create a new contractor variance.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/contractorVariances/{contractorVarianceID}`
  - **Description**: Retrieve details for a specific contractor variance.
  - **Parameters**: `companyID`, `contractorVarianceID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/contractorVariances/{contractorVarianceID}`
  - **Description**: Update a contractor variance.
  - **Parameters**: `companyID`, `contractorVarianceID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/contractorVariances/{contractorVarianceID}`
  - **Description**: Delete a contractor variance.
  - **Parameters**: `companyID`, `contractorVarianceID`
  - **Response**: No Content

### Nested: Contractors

- [ ] `GET /api/v1.0/companies/{companyID}/contractors/`
  - **Description**: List all contractors.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/contractors/`
  - **Description**: Create a new contractor.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/contractors/{contractorID}`
  - **Description**: Retrieve details for a specific contractor.
  - **Parameters**: `companyID`, `contractorID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/contractors/{contractorID}`
  - **Description**: Update a contractor.
  - **Parameters**: `companyID`, `contractorID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/contractors/{contractorID}`
  - **Description**: Delete a contractor.
  - **Parameters**: `companyID`, `contractorID`
  - **Response**: No Content

#### Attachments > files

- [ ] `GET /api/v1.0/companies/{companyID}/contractors/{contractorID}/attachments/files/`
  - **Description**: List all contractor attachments.
  - **Parameters**: `companyID`, `contractorID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/contractors/{contractorID}/attachments/files/`
  - **Description**: Create a new contractor attachment.
  - **Parameters**: `companyID`, `contractorID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/contractors/{contractorID}/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific contractor attachment.
  - **Parameters**: `companyID`, `contractorID`, `fileID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/contractors/{contractorID}/attachments/files/{fileID}`
  - **Description**: Update a contractor attachment.
  - **Parameters**: `companyID`, `contractorID`, `fileID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/contractors/{contractorID}/attachments/files/{fileID}`
  - **Description**: Delete a contractor attachment.
  - **Parameters**: `companyID`, `contractorID`, `fileID`
  - **Response**: No Content

#### Attachments > folders

- [ ] `GET /api/v1.0/companies/{companyID}/contractors/{contractorID}/attachments/folders/`
  - **Description**: List all contractor attachment folders.
  - **Parameters**: `companyID`, `contractorID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/contractors/{contractorID}/attachments/folders/`
  - **Description**: Create a new contractor attachment folder.
  - **Parameters**: `companyID`, `contractorID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/contractors/{contractorID}/attachments/folders/{folderID}`
  - **Description**: Retrieve details for a specific contractor attachment folder.
  - **Parameters**: `companyID`, `contractorID`, `folderID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/contractors/{contractorID}/attachments/folders/{folderID}`
  - **Description**: Update a contractor attachment folder.
  - **Parameters**: `companyID`, `contractorID`, `folderID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/contractors/{contractorID}/attachments/folders/{folderID}`
  - **Description**: Delete a contractor attachment folder.
  - **Parameters**: `companyID`, `contractorID`, `folderID`
  - **Response**: No Content

#### CustomFields

- [ ] `GET /api/v1.0/companies/{companyID}/contractors/{contractorID}/customFields/`
  - **Description**: List all contractor custom fields.
  - **Parameters**: `companyID`, `contractorID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/contractors/{contractorID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific contractor custom field.
  - **Parameters**: `companyID`, `contractorID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/contractors/{contractorID}/customFields/{customFieldID}`
  - **Description**: Update a contractor custom field.
  - **Parameters**: `companyID`, `contractorID`, `customFieldID`
  - **Response**: No Content

#### Licences

- [ ] `GET /api/v1.0/companies/{companyID}/contractors/{contractorID}/licences/`
  - **Description**: List all contractor licences.
  - **Parameters**: `companyID`, `contractorID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/contractors/{contractorID}/licences/`
  - **Description**: Create a new contractor licence.
  - **Parameters**: `companyID`, `contractorID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/contractors/{contractorID}/licences/{licenceID}`
  - **Description**: Retrieve details for a specific contractor licence.
  - **Parameters**: `companyID`, `contractorID`, `licenceID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/contractors/{contractorID}/licences/{licenceID}`
  - **Description**: Update a contractor licence.
  - **Parameters**: `companyID`, `contractorID`, `licenceID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/contractors/{contractorID}/licences/{licenceID}`
  - **Description**: Delete a contractor licence.
  - **Parameters**: `companyID`, `contractorID`, `licenceID`
  - **Response**: No Content

#### Licences > attachments > files

- [ ] `GET /api/v1.0/companies/{companyID}/contractors/{contractorID}/licences/{licenceID}/attachments/files/`
  - **Description**: List all contractor licence attachments.
  - **Parameters**: `companyID`, `contractorID`, `licenceID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/contractors/{contractorID}/licences/{licenceID}/attachments/files/`
  - **Description**: Create a new contractor licence attachment.
  - **Parameters**: `companyID`, `contractorID`, `licenceID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/contractors/{contractorID}/licences/{licenceID}/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific contractor licence attachment.
  - **Parameters**: `companyID`, `contractorID`, `licenceID`, `fileID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/contractors/{contractorID}/licences/{licenceID}/attachments/files/{fileID}`
  - **Description**: Update a contractor licence attachment.
  - **Parameters**: `companyID`, `contractorID`, `licenceID`, `fileID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/contractors/{contractorID}/licences/{licenceID}/attachments/files/{fileID}`
  - **Description**: Delete a contractor licence attachment.
  - **Parameters**: `companyID`, `contractorID`, `licenceID`, `fileID`
  - **Response**: No Content

#### Timesheets

- [ ] `GET /api/v1.0/companies/{companyID}/contractors/{contractorID}/timesheets/`
  - **Description**: List all contractor timesheets.
  - **Parameters**: `companyID`, `contractorID`, `search`?, `columns`?, `UID`?, `StartDate`?, `EndDate`?, `Includes`?, `ScheduleType`?
  - **Response**: Array of object

### Nested: CreditNotes

- [ ] `GET /api/v1.0/companies/{companyID}/creditNotes/`
  - **Description**: List all credit notes.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/creditNotes/`
  - **Description**: Create a new credit note.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/creditNotes/{creditNoteID}`
  - **Description**: Retrieve details for a specific credit note.
  - **Parameters**: `companyID`, `creditNoteID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/creditNotes/{creditNoteID}`
  - **Description**: Update a credit note.
  - **Parameters**: `companyID`, `creditNoteID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/creditNotes/{creditNoteID}`
  - **Description**: Delete a credit note.
  - **Parameters**: `companyID`, `creditNoteID`
  - **Response**: No Content

#### CustomFields

- [ ] `GET /api/v1.0/companies/{companyID}/creditNotes/{creditNoteID}/customFields/`
  - **Description**: List all credit note custom fields.
  - **Parameters**: `companyID`, `creditNoteID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/creditNotes/{creditNoteID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific credit note custom field.
  - **Parameters**: `companyID`, `creditNoteID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/creditNotes/{creditNoteID}/customFields/{customFieldID}`
  - **Description**: Update a credit note custom field.
  - **Parameters**: `companyID`, `creditNoteID`, `customFieldID`
  - **Response**: No Content

#### Notes

- [ ] `GET /api/v1.0/companies/{companyID}/creditNotes/{creditNoteID}/notes/`
  - **Description**: List all notes.
  - **Parameters**: `companyID`, `creditNoteID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/creditNotes/{creditNoteID}/notes/`
  - **Description**: Create a new note.
  - **Parameters**: `companyID`, `creditNoteID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/creditNotes/{creditNoteID}/notes/{noteID}`
  - **Description**: Retrieve details for a specific note.
  - **Parameters**: `companyID`, `creditNoteID`, `noteID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/creditNotes/{creditNoteID}/notes/{noteID}`
  - **Description**: Update a note.
  - **Parameters**: `companyID`, `creditNoteID`, `noteID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/creditNotes/{creditNoteID}/notes/{noteID}`
  - **Description**: Delete a note.
  - **Parameters**: `companyID`, `creditNoteID`, `noteID`
  - **Response**: No Content

### Nested: CustomerAssets

- [ ] `GET /api/v1.0/companies/{companyID}/customerAssets/`
  - **Description**: List all customer assets with sites.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/customerAssets/{customerAssetID}`
  - **Description**: Retrieve details for a specific customer asset with site.
  - **Parameters**: `companyID`, `customerAssetID`, `columns`?
  - **Response**: object

### Nested: CustomerContracts

- [ ] `GET /api/v1.0/companies/{companyID}/customerContracts/`
  - **Description**: List all customer contract search.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

### Nested: CustomerInvoices

- [ ] `GET /api/v1.0/companies/{companyID}/customerInvoices/`
  - **Description**: List all customer invoices.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/customerInvoices/{customerInvoiceID}`
  - **Description**: Retrieve details for a specific customer invoice.
  - **Parameters**: `companyID`, `customerInvoiceID`, `columns`?
  - **Response**: object

### Nested: CustomerPayments

- [ ] `GET /api/v1.0/companies/{companyID}/customerPayments/`
  - **Description**: List all customer payments.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/customerPayments/`
  - **Description**: Create a new customer payment.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/customerPayments/{customerPaymentID}`
  - **Description**: Retrieve details for a specific customer payment.
  - **Parameters**: `companyID`, `customerPaymentID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/customerPayments/{customerPaymentID}`
  - **Description**: Update a customer payment.
  - **Parameters**: `companyID`, `customerPaymentID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/customerPayments/{customerPaymentID}`
  - **Description**: Delete a customer payment.
  - **Parameters**: `companyID`, `customerPaymentID`
  - **Response**: No Content

### Nested: Customers

- [x] `GET /api/v1.0/companies/{companyID}/customers/`
  - **Description**: List all customers.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

#### Attachments > files

- [ ] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/attachments/files/`
  - **Description**: List all customer attachments.
  - **Parameters**: `companyID`, `customerID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/customers/{customerID}/attachments/files/`
  - **Description**: Create a new customer attachment.
  - **Parameters**: `companyID`, `customerID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific customer attachment.
  - **Parameters**: `companyID`, `customerID`, `fileID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/customers/{customerID}/attachments/files/{fileID}`
  - **Description**: Update a customer attachment.
  - **Parameters**: `companyID`, `customerID`, `fileID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/customers/{customerID}/attachments/files/{fileID}`
  - **Description**: Delete a customer attachment.
  - **Parameters**: `companyID`, `customerID`, `fileID`
  - **Response**: No Content

#### Attachments > folders

- [ ] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/attachments/folders/`
  - **Description**: List all customer attachment folders.
  - **Parameters**: `companyID`, `customerID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/customers/{customerID}/attachments/folders/`
  - **Description**: Create a new customer attachment folder.
  - **Parameters**: `companyID`, `customerID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/attachments/folders/{folderID}`
  - **Description**: Retrieve details for a specific customer attachment folder.
  - **Parameters**: `companyID`, `customerID`, `folderID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/customers/{customerID}/attachments/folders/{folderID}`
  - **Description**: Update a customer attachment folder.
  - **Parameters**: `companyID`, `customerID`, `folderID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/customers/{customerID}/attachments/folders/{folderID}`
  - **Description**: Delete a customer attachment folder.
  - **Parameters**: `companyID`, `customerID`, `folderID`
  - **Response**: No Content

#### Companies

- [x] `GET /api/v1.0/companies/{companyID}/customers/companies/`
  - **Description**: List all company customers.
  - **Parameters**: `companyID`, `customerType`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [x] `POST /api/v1.0/companies/{companyID}/customers/companies/`
  - **Description**: Create a new company customer.
  - **Parameters**: `companyID`, `customerType`, `createSite`?
  - **Response**: Created

- [x] `GET /api/v1.0/companies/{companyID}/customers/companies/{customerID}`
  - **Description**: Retrieve details for a specific company customer.
  - **Parameters**: `companyID`, `customerType`, `customerID`, `columns`?
  - **Response**: object

- [x] `PATCH /api/v1.0/companies/{companyID}/customers/companies/{customerID}`
  - **Description**: Update a company customer.
  - **Parameters**: `companyID`, `customerType`, `customerID`, `createSite`?
  - **Response**: No Content

- [x] `DELETE /api/v1.0/companies/{companyID}/customers/companies/{customerID}`
  - **Description**: Delete a company customer.
  - **Parameters**: `companyID`, `customerType`, `customerID`
  - **Response**: No Content

- [ ] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/companies/`
  - **Description**: List all customer assigned companies.
  - **Parameters**: `companyID`, `customerID`, `search`?, `columns`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/customers/{customerID}/companies/`
  - **Description**: Create a new customer assigned companies.
  - **Parameters**: `companyID`, `customerID`
  - **Response**: Unknown

- [ ] `DELETE /api/v1.0/companies/{companyID}/customers/{customerID}/companies/{customerCompanyID}`
  - **Description**: Delete a customer assigned companies.
  - **Parameters**: `companyID`, `customerID`, `customerCompanyID`
  - **Response**: No Content

#### Contacts

- [x] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/contacts/`
  - **Description**: List all customer contacts.
  - **Parameters**: `companyID`, `customerID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [x] `POST /api/v1.0/companies/{companyID}/customers/{customerID}/contacts/`
  - **Description**: Create a new customer contact.
  - **Parameters**: `companyID`, `customerID`
  - **Response**: Created

- [x] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/contacts/{contactID}`
  - **Description**: Retrieve details for a specific customer contact.
  - **Parameters**: `companyID`, `customerID`, `contactID`, `columns`?
  - **Response**: object

- [x] `PATCH /api/v1.0/companies/{companyID}/customers/{customerID}/contacts/{contactID}`
  - **Description**: Update a customer contact.
  - **Parameters**: `companyID`, `customerID`, `contactID`
  - **Response**: No Content

- [x] `DELETE /api/v1.0/companies/{companyID}/customers/{customerID}/contacts/{contactID}`
  - **Description**: Delete a customer contact.
  - **Parameters**: `companyID`, `customerID`, `contactID`
  - **Response**: No Content

#### Contacts > customFields

- [x] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/contacts/{contactID}/customFields/`
  - **Description**: List all customer contact custom fields.
  - **Parameters**: `companyID`, `customerID`, `contactID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [x] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/contacts/{contactID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific customer contact custom field.
  - **Parameters**: `companyID`, `customerID`, `contactID`, `customFieldID`, `columns`?
  - **Response**: object

- [x] `PATCH /api/v1.0/companies/{companyID}/customers/{customerID}/contacts/{contactID}/customFields/{customFieldID}`
  - **Description**: Update a customer contact custom field.
  - **Parameters**: `companyID`, `customerID`, `contactID`, `customFieldID`
  - **Response**: No Content

#### Contracts

- [x] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/`
  - **Description**: List all customer contracts.
  - **Parameters**: `companyID`, `customerID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [x] `POST /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/`
  - **Description**: Create a new customer contract.
  - **Parameters**: `companyID`, `customerID`
  - **Response**: Created

- [x] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}`
  - **Description**: Retrieve details for a specific customer contract.
  - **Parameters**: `companyID`, `customerID`, `contractID`, `columns`?
  - **Response**: object

- [x] `PATCH /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}`
  - **Description**: Update a customer contract.
  - **Parameters**: `companyID`, `customerID`, `contractID`
  - **Response**: No Content

- [x] `DELETE /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}`
  - **Description**: Delete a customer contract.
  - **Parameters**: `companyID`, `customerID`, `contractID`
  - **Response**: No Content

#### Contracts > customFields

- [x] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}/customFields/`
  - **Description**: List all customer contract custom fields.
  - **Parameters**: `companyID`, `customerID`, `contractID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [x] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific customer contract custom field.
  - **Parameters**: `companyID`, `customerID`, `contractID`, `customFieldID`, `columns`?
  - **Response**: object

- [x] `PATCH /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}/customFields/{customFieldID}`
  - **Description**: Update a customer contract custom field.
  - **Parameters**: `companyID`, `customerID`, `contractID`, `customFieldID`
  - **Response**: No Content

#### Contracts > inflation

- [x] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}/inflation/`
  - **Description**: List all customer contract inflation.
  - **Parameters**: `companyID`, `customerID`, `contractID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [x] `POST /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}/inflation/`
  - **Description**: Create a new customer contract inflation.
  - **Parameters**: `companyID`, `customerID`, `contractID`
  - **Response**: Created

- [x] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}/inflation/{inflationID}`
  - **Description**: Retrieve details for a specific customer contract inflation.
  - **Parameters**: `companyID`, `customerID`, `contractID`, `inflationID`, `columns`?
  - **Response**: object

- [x] `PATCH /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}/inflation/{inflationID}`
  - **Description**: Update a customer contract inflation.
  - **Parameters**: `companyID`, `customerID`, `contractID`, `inflationID`
  - **Response**: No Content

- [x] `DELETE /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}/inflation/{inflationID}`
  - **Description**: Delete a customer contract inflation.
  - **Parameters**: `companyID`, `customerID`, `contractID`, `inflationID`
  - **Response**: No Content

#### Contracts > laborRates

- [x] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}/laborRates/`
  - **Description**: List all customer contract labor rates.
  - **Parameters**: `companyID`, `customerID`, `contractID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [x] `POST /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}/laborRates/`
  - **Description**: Create a new customer contract labor rate.
  - **Parameters**: `companyID`, `customerID`, `contractID`
  - **Response**: Created

- [x] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}/laborRates/{laborRateID}`
  - **Description**: Retrieve details for a specific customer contract labor rate.
  - **Parameters**: `companyID`, `customerID`, `contractID`, `laborRateID`, `columns`?
  - **Response**: object

- [x] `PATCH /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}/laborRates/{laborRateID}`
  - **Description**: Update a customer contract labor rate.
  - **Parameters**: `companyID`, `customerID`, `contractID`, `laborRateID`
  - **Response**: No Content

- [x] `DELETE /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}/laborRates/{laborRateID}`
  - **Description**: Delete a customer contract labor rate.
  - **Parameters**: `companyID`, `customerID`, `contractID`, `laborRateID`
  - **Response**: No Content

#### Contracts > serviceLevels

- [x] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}/serviceLevels/`
  - **Description**: List all customer contract service levels.
  - **Parameters**: `companyID`, `customerID`, `contractID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

#### Contracts > serviceLevels > assetTypes

- [x] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}/serviceLevels/{serviceLevelID}/assetTypes/{assetTypeID}`
  - **Description**: Retrieve details for a specific customer contract service level asset type.
  - **Parameters**: `companyID`, `customerID`, `contractID`, `serviceLevelID`, `assetTypeID`, `columns`?
  - **Response**: object

- [x] `PATCH /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}/serviceLevels/{serviceLevelID}/assetTypes/{assetTypeID}`
  - **Description**: Update a customer contract service level asset type.
  - **Parameters**: `companyID`, `customerID`, `contractID`, `serviceLevelID`, `assetTypeID`
  - **Response**: No Content

#### CustomFields

- [ ] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/customFields/`
  - **Description**: List all customer custom fields.
  - **Parameters**: `companyID`, `customerID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific customer custom field.
  - **Parameters**: `companyID`, `customerID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/customers/{customerID}/customFields/{customFieldID}`
  - **Description**: Update a customer custom field.
  - **Parameters**: `companyID`, `customerID`, `customFieldID`
  - **Response**: No Content

#### Individuals

- [x] `GET /api/v1.0/companies/{companyID}/customers/individuals/`
  - **Description**: List all individual customers.
  - **Parameters**: `companyID`, `customerType`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [x] `POST /api/v1.0/companies/{companyID}/customers/individuals/`
  - **Description**: Create a new individual customer.
  - **Parameters**: `companyID`, `customerType`, `createSite`?
  - **Response**: Created

- [x] `GET /api/v1.0/companies/{companyID}/customers/individuals/{customerID}`
  - **Description**: Retrieve details for a specific individual customer.
  - **Parameters**: `companyID`, `customerType`, `customerID`, `columns`?
  - **Response**: object

- [x] `PATCH /api/v1.0/companies/{companyID}/customers/individuals/{customerID}`
  - **Description**: Update a individual customer.
  - **Parameters**: `companyID`, `customerType`, `customerID`, `createSite`?
  - **Response**: No Content

- [x] `DELETE /api/v1.0/companies/{companyID}/customers/individuals/{customerID}`
  - **Description**: Delete a individual customer.
  - **Parameters**: `companyID`, `customerType`, `customerID`
  - **Response**: No Content

#### LaborRates

- [ ] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/laborRates/`
  - **Description**: List all customer labor rates.
  - **Parameters**: `companyID`, `customerID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/customers/{customerID}/laborRates/`
  - **Description**: Create a new customer labor rate.
  - **Parameters**: `companyID`, `customerID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/laborRates/{laborRateID}`
  - **Description**: Retrieve details for a specific customer labor rate.
  - **Parameters**: `companyID`, `customerID`, `laborRateID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/customers/{customerID}/laborRates/{laborRateID}`
  - **Description**: Update a customer labor rate.
  - **Parameters**: `companyID`, `customerID`, `laborRateID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/customers/{customerID}/laborRates/{laborRateID}`
  - **Description**: Delete a customer labor rate.
  - **Parameters**: `companyID`, `customerID`, `laborRateID`
  - **Response**: No Content

#### Notes

- [ ] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/notes/`
  - **Description**: List all customer notes.
  - **Parameters**: `companyID`, `customerID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/customers/{customerID}/notes/`
  - **Description**: Create a new customer note.
  - **Parameters**: `companyID`, `customerID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/notes/{noteID}`
  - **Description**: Retrieve details for a specific customer note.
  - **Parameters**: `companyID`, `customerID`, `noteID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/customers/{customerID}/notes/{noteID}`
  - **Description**: Update a customer note.
  - **Parameters**: `companyID`, `customerID`, `noteID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/customers/{customerID}/notes/{noteID}`
  - **Description**: Delete a customer note.
  - **Parameters**: `companyID`, `customerID`, `noteID`
  - **Response**: No Content

#### ResponseTimes

- [ ] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/responseTimes/`
  - **Description**: List all customer response times.
  - **Parameters**: `companyID`, `customerID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/customers/{customerID}/responseTimes/`
  - **Description**: Create a new customer response time.
  - **Parameters**: `companyID`, `customerID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/responseTimes/{responseTimeID}`
  - **Description**: Retrieve details for a specific customer response time.
  - **Parameters**: `companyID`, `customerID`, `responseTimeID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/customers/{customerID}/responseTimes/{responseTimeID}`
  - **Description**: Update a customer response time.
  - **Parameters**: `companyID`, `customerID`, `responseTimeID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/customers/{customerID}/responseTimes/{responseTimeID}`
  - **Description**: Delete a customer response time.
  - **Parameters**: `companyID`, `customerID`, `responseTimeID`
  - **Response**: No Content

#### Tasks

- [ ] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/tasks/`
  - **Description**: List all customer tasks.
  - **Parameters**: `companyID`, `customerID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/customers/{customerID}/tasks/{taskID}`
  - **Description**: Retrieve details for a specific customer task.
  - **Parameters**: `companyID`, `customerID`, `taskID`, `columns`?
  - **Response**: object

### Nested: DataFeedEvents

- [ ] `GET /api/v1.0/companies/{companyID}/dataFeedEvents/`
  - **Description**: List all datafeedevents.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/dataFeedEvents/`
  - **Description**: Create a new datafeedevent.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/dataFeedEvents/{DataFeedEventID}`
  - **Description**: Retrieve details for a specific datafeedevent.
  - **Parameters**: `companyID`, `DataFeedEventID`, `columns`?
  - **Response**: object

- [ ] `DELETE /api/v1.0/companies/{companyID}/dataFeedEvents/{DataFeedEventID}`
  - **Description**: Delete a datafeedevent.
  - **Parameters**: `companyID`, `DataFeedEventID`
  - **Response**: No Content

### Nested: Employees

- [x] `GET /api/v1.0/companies/{companyID}/employees/`
  - **Description**: List all employees.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [x] `POST /api/v1.0/companies/{companyID}/employees/`
  - **Description**: Create a new employee.
  - **Parameters**: `companyID`
  - **Response**: Created

- [x] `GET /api/v1.0/companies/{companyID}/employees/{employeeID}`
  - **Description**: Retrieve details for a specific employee.
  - **Parameters**: `companyID`, `employeeID`, `columns`?
  - **Response**: object

- [x] `PATCH /api/v1.0/companies/{companyID}/employees/{employeeID}`
  - **Description**: Update a employee.
  - **Parameters**: `companyID`, `employeeID`
  - **Response**: No Content

- [x] `DELETE /api/v1.0/companies/{companyID}/employees/{employeeID}`
  - **Description**: Delete a employee.
  - **Parameters**: `companyID`, `employeeID`
  - **Response**: No Content

#### Attachments > files

- [x] `GET /api/v1.0/companies/{companyID}/employees/{employeeID}/attachments/files/`
  - **Description**: List all employee attachments.
  - **Parameters**: `companyID`, `employeeID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [x] `POST /api/v1.0/companies/{companyID}/employees/{employeeID}/attachments/files/`
  - **Description**: Create a new employee attachment.
  - **Parameters**: `companyID`, `employeeID`
  - **Response**: Created

- [x] `GET /api/v1.0/companies/{companyID}/employees/{employeeID}/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific employee attachment.
  - **Parameters**: `companyID`, `employeeID`, `fileID`, `columns`?
  - **Response**: object

- [x] `PATCH /api/v1.0/companies/{companyID}/employees/{employeeID}/attachments/files/{fileID}`
  - **Description**: Update a employee attachment.
  - **Parameters**: `companyID`, `employeeID`, `fileID`
  - **Response**: No Content

- [x] `DELETE /api/v1.0/companies/{companyID}/employees/{employeeID}/attachments/files/{fileID}`
  - **Description**: Delete a employee attachment.
  - **Parameters**: `companyID`, `employeeID`, `fileID`
  - **Response**: No Content

#### Attachments > folders

- [x] `GET /api/v1.0/companies/{companyID}/employees/{employeeID}/attachments/folders/`
  - **Description**: List all employee attachment folders.
  - **Parameters**: `companyID`, `employeeID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [x] `POST /api/v1.0/companies/{companyID}/employees/{employeeID}/attachments/folders/`
  - **Description**: Create a new employee attachment folder.
  - **Parameters**: `companyID`, `employeeID`
  - **Response**: Created

- [x] `GET /api/v1.0/companies/{companyID}/employees/{employeeID}/attachments/folders/{folderID}`
  - **Description**: Retrieve details for a specific employee attachment folder.
  - **Parameters**: `companyID`, `employeeID`, `folderID`, `columns`?
  - **Response**: object

- [x] `PATCH /api/v1.0/companies/{companyID}/employees/{employeeID}/attachments/folders/{folderID}`
  - **Description**: Update a employee attachment folder.
  - **Parameters**: `companyID`, `employeeID`, `folderID`
  - **Response**: No Content

- [x] `DELETE /api/v1.0/companies/{companyID}/employees/{employeeID}/attachments/folders/{folderID}`
  - **Description**: Delete a employee attachment folder.
  - **Parameters**: `companyID`, `employeeID`, `folderID`
  - **Response**: No Content

#### CustomFields

- [x] `GET /api/v1.0/companies/{companyID}/employees/{employeeID}/customFields/`
  - **Description**: List all employee custom fields.
  - **Parameters**: `companyID`, `employeeID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [x] `GET /api/v1.0/companies/{companyID}/employees/{employeeID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific employee custom field.
  - **Parameters**: `companyID`, `employeeID`, `customFieldID`, `columns`?
  - **Response**: object

- [x] `PATCH /api/v1.0/companies/{companyID}/employees/{employeeID}/customFields/{customFieldID}`
  - **Description**: Update a employee custom field.
  - **Parameters**: `companyID`, `employeeID`, `customFieldID`
  - **Response**: No Content

#### Licences

- [x] `GET /api/v1.0/companies/{companyID}/employees/{employeeID}/licences/`
  - **Description**: List all employee licences.
  - **Parameters**: `companyID`, `employeeID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [x] `POST /api/v1.0/companies/{companyID}/employees/{employeeID}/licences/`
  - **Description**: Create a new employee licence.
  - **Parameters**: `companyID`, `employeeID`
  - **Response**: Created

- [x] `GET /api/v1.0/companies/{companyID}/employees/{employeeID}/licences/{licenceID}`
  - **Description**: Retrieve details for a specific employee licence.
  - **Parameters**: `companyID`, `employeeID`, `licenceID`, `columns`?
  - **Response**: object

- [x] `PATCH /api/v1.0/companies/{companyID}/employees/{employeeID}/licences/{licenceID}`
  - **Description**: Update a employee licence.
  - **Parameters**: `companyID`, `employeeID`, `licenceID`
  - **Response**: No Content

- [x] `DELETE /api/v1.0/companies/{companyID}/employees/{employeeID}/licences/{licenceID}`
  - **Description**: Delete a employee licence.
  - **Parameters**: `companyID`, `employeeID`, `licenceID`
  - **Response**: No Content

#### Licences > attachments > files

- [x] `GET /api/v1.0/companies/{companyID}/employees/{employeeID}/licences/{licenceID}/attachments/files/`
  - **Description**: List all employee licence attachments.
  - **Parameters**: `companyID`, `employeeID`, `licenceID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [x] `POST /api/v1.0/companies/{companyID}/employees/{employeeID}/licences/{licenceID}/attachments/files/`
  - **Description**: Create a new employee licence attachment.
  - **Parameters**: `companyID`, `employeeID`, `licenceID`
  - **Response**: Created

- [x] `GET /api/v1.0/companies/{companyID}/employees/{employeeID}/licences/{licenceID}/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific employee licence attachment.
  - **Parameters**: `companyID`, `employeeID`, `licenceID`, `fileID`, `columns`?
  - **Response**: object

- [x] `PATCH /api/v1.0/companies/{companyID}/employees/{employeeID}/licences/{licenceID}/attachments/files/{fileID}`
  - **Description**: Update a employee licence attachment.
  - **Parameters**: `companyID`, `employeeID`, `licenceID`, `fileID`
  - **Response**: No Content

- [x] `DELETE /api/v1.0/companies/{companyID}/employees/{employeeID}/licences/{licenceID}/attachments/files/{fileID}`
  - **Description**: Delete a employee licence attachment.
  - **Parameters**: `companyID`, `employeeID`, `licenceID`, `fileID`
  - **Response**: No Content

#### Timesheets

- [x] `GET /api/v1.0/companies/{companyID}/employees/{employeeID}/timesheets/`
  - **Description**: List all employee timesheets.
  - **Parameters**: `companyID`, `employeeID`, `search`?, `columns`?, `UID`?, `StartDate`?, `EndDate`?, `Includes`?, `ScheduleType`?
  - **Response**: Array of object

### Nested: InventoryJournals

- [ ] `GET /api/v1.0/companies/{companyID}/inventoryJournals/`
  - **Description**: List all inventory journals.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/inventoryJournals/{inventoryJournalID}`
  - **Description**: Retrieve details for a specific inventory journal.
  - **Parameters**: `companyID`, `inventoryJournalID`, `columns`?
  - **Response**: object

### Nested: Invoices

- [x] `GET /api/v1.0/companies/{companyID}/invoices/`
  - **Description**: List all invoices.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [x] `POST /api/v1.0/companies/{companyID}/invoices/`
  - **Description**: Create a new invoice.
  - **Parameters**: `companyID`
  - **Response**: Created

- [x] `GET /api/v1.0/companies/{companyID}/invoices/{invoiceID}`
  - **Description**: Retrieve details for a specific invoice.
  - **Parameters**: `companyID`, `invoiceID`, `columns`?
  - **Response**: object

- [x] `PATCH /api/v1.0/companies/{companyID}/invoices/{invoiceID}`
  - **Description**: Update a invoice.
  - **Parameters**: `companyID`, `invoiceID`
  - **Response**: No Content

- [x] `DELETE /api/v1.0/companies/{companyID}/invoices/{invoiceID}`
  - **Description**: Delete a invoice.
  - **Parameters**: `companyID`, `invoiceID`
  - **Response**: No Content

#### CostCenters

- [ ] `GET /api/v1.0/companies/{companyID}/invoices/{invoiceID}/costCenters/`
  - **Description**: List all invoice cost centers.
  - **Parameters**: `companyID`, `invoiceID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/invoices/{invoiceID}/costCenters/{costCenterID}`
  - **Description**: Retrieve details for a specific invoice cost center.
  - **Parameters**: `companyID`, `invoiceID`, `costCenterID`, `columns`?
  - **Response**: object

#### CreditNotes

- [ ] `GET /api/v1.0/companies/{companyID}/invoices/{invoiceID}/creditNotes/`
  - **Description**: List all invoice credit notes.
  - **Parameters**: `companyID`, `invoiceID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/invoices/{invoiceID}/creditNotes/`
  - **Description**: Create a new invoice credit note.
  - **Parameters**: `companyID`, `invoiceID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/invoices/{invoiceID}/creditNotes/{creditNoteID}`
  - **Description**: Retrieve details for a specific invoice credit note.
  - **Parameters**: `companyID`, `invoiceID`, `creditNoteID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/invoices/{invoiceID}/creditNotes/{creditNoteID}`
  - **Description**: Update a invoice credit note.
  - **Parameters**: `companyID`, `invoiceID`, `creditNoteID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/invoices/{invoiceID}/creditNotes/{creditNoteID}`
  - **Description**: Delete a invoice credit note.
  - **Parameters**: `companyID`, `invoiceID`, `creditNoteID`
  - **Response**: No Content

#### CreditNotes > customFields

- [ ] `GET /api/v1.0/companies/{companyID}/invoices/{invoiceID}/creditNotes/{creditNoteID}/customFields/`
  - **Description**: List all invoice credit note custom fields.
  - **Parameters**: `companyID`, `invoiceID`, `creditNoteID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/invoices/{invoiceID}/creditNotes/{creditNoteID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific invoice credit note custom field.
  - **Parameters**: `companyID`, `invoiceID`, `creditNoteID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/invoices/{invoiceID}/creditNotes/{creditNoteID}/customFields/{customFieldID}`
  - **Description**: Update a invoice credit note custom field.
  - **Parameters**: `companyID`, `invoiceID`, `creditNoteID`, `customFieldID`
  - **Response**: No Content

#### CreditNotes > notes

- [ ] `GET /api/v1.0/companies/{companyID}/invoices/{invoiceID}/creditNotes/{creditNoteID}/notes/`
  - **Description**: List all credit note notes.
  - **Parameters**: `companyID`, `invoiceID`, `creditNoteID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/invoices/{invoiceID}/creditNotes/{creditNoteID}/notes/`
  - **Description**: Create a new note.
  - **Parameters**: `companyID`, `invoiceID`, `creditNoteID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/invoices/{invoiceID}/creditNotes/{creditNoteID}/notes/{noteID}`
  - **Description**: Retrieve details for a specific note.
  - **Parameters**: `companyID`, `invoiceID`, `creditNoteID`, `noteID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/invoices/{invoiceID}/creditNotes/{creditNoteID}/notes/{noteID}`
  - **Description**: Update a note.
  - **Parameters**: `companyID`, `invoiceID`, `creditNoteID`, `noteID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/invoices/{invoiceID}/creditNotes/{creditNoteID}/notes/{noteID}`
  - **Description**: Delete a note.
  - **Parameters**: `companyID`, `invoiceID`, `creditNoteID`, `noteID`
  - **Response**: No Content

#### CustomFields

- [ ] `GET /api/v1.0/companies/{companyID}/invoices/{invoiceID}/customFields/`
  - **Description**: List all invoice custom fields.
  - **Parameters**: `companyID`, `invoiceID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/invoices/{invoiceID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific invoice custom field.
  - **Parameters**: `companyID`, `invoiceID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/invoices/{invoiceID}/customFields/{customFieldID}`
  - **Description**: Update a invoice custom field.
  - **Parameters**: `companyID`, `invoiceID`, `customFieldID`
  - **Response**: No Content

#### Notes

- [ ] `GET /api/v1.0/companies/{companyID}/invoices/{invoiceID}/notes/`
  - **Description**: List all invoice notes.
  - **Parameters**: `companyID`, `invoiceID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/invoices/{invoiceID}/notes/`
  - **Description**: Create a new invoice note.
  - **Parameters**: `companyID`, `invoiceID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/invoices/{invoiceID}/notes/{noteID}`
  - **Description**: Retrieve details for a specific invoice note.
  - **Parameters**: `companyID`, `invoiceID`, `noteID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/invoices/{invoiceID}/notes/{noteID}`
  - **Description**: Update a invoice note.
  - **Parameters**: `companyID`, `invoiceID`, `noteID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/invoices/{invoiceID}/notes/{noteID}`
  - **Description**: Delete a invoice note.
  - **Parameters**: `companyID`, `invoiceID`, `noteID`
  - **Response**: No Content

#### Retainages

- [ ] `GET /api/v1.0/companies/{companyID}/invoices/retainages/`
  - **Description**: List all retainages.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/invoices/retainages/`
  - **Description**: Create a new retainage.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/invoices/retainages/{retainageID}`
  - **Description**: Retrieve details for a specific retainage.
  - **Parameters**: `companyID`, `retainageID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/invoices/retainages/{retainageID}`
  - **Description**: Update a retainage.
  - **Parameters**: `companyID`, `retainageID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/invoices/retainages/{retainageID}`
  - **Description**: Delete a retainage.
  - **Parameters**: `companyID`, `retainageID`
  - **Response**: No Content

### Nested: JobCostCenters

- [ ] `GET /api/v1.0/companies/{companyID}/jobCostCenters/`
  - **Description**: List all cost centers.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

### Nested: JobWorkOrders

- [ ] `GET /api/v1.0/companies/{companyID}/jobWorkOrders/`
  - **Description**: List all job work orders.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/jobWorkOrders/{jobWorkOrderID}`
  - **Description**: Retrieve details for a specific job work order.
  - **Parameters**: `companyID`, `jobWorkOrderID`, `columns`?
  - **Response**: object

### Nested: Jobs

- [x] `GET /api/v1.0/companies/{companyID}/jobs/`
  - **Description**: List all jobs.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [x] `POST /api/v1.0/companies/{companyID}/jobs/`
  - **Description**: Create a new job.
  - **Parameters**: `companyID`
  - **Response**: Created

- [x] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}`
  - **Description**: Retrieve details for a specific job.
  - **Parameters**: `companyID`, `jobID`, `columns`?
  - **Response**: object

- [x] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}`
  - **Description**: Update a job.
  - **Parameters**: `companyID`, `jobID`
  - **Response**: No Content

- [x] `DELETE /api/v1.0/companies/{companyID}/jobs/{jobID}`
  - **Description**: Delete a job.
  - **Parameters**: `companyID`, `jobID`
  - **Response**: No Content

#### Attachments > files

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/attachments/files/`
  - **Description**: List all job attachments.
  - **Parameters**: `companyID`, `jobID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/jobs/{jobID}/attachments/files/`
  - **Description**: Create a new job attachment.
  - **Parameters**: `companyID`, `jobID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific job attachment.
  - **Parameters**: `companyID`, `jobID`, `fileID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}/attachments/files/{fileID}`
  - **Description**: Update a job attachment.
  - **Parameters**: `companyID`, `jobID`, `fileID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/jobs/{jobID}/attachments/files/{fileID}`
  - **Description**: Delete a job attachment.
  - **Parameters**: `companyID`, `jobID`, `fileID`
  - **Response**: No Content

#### Attachments > folders

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/attachments/folders/`
  - **Description**: List all job attachment folders.
  - **Parameters**: `companyID`, `jobID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/jobs/{jobID}/attachments/folders/`
  - **Description**: Create a new job attachment folder.
  - **Parameters**: `companyID`, `jobID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/attachments/folders/{folderID}`
  - **Description**: Retrieve details for a specific job attachment folder.
  - **Parameters**: `companyID`, `jobID`, `folderID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}/attachments/folders/{folderID}`
  - **Description**: Update a job attachment folder.
  - **Parameters**: `companyID`, `jobID`, `folderID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/jobs/{jobID}/attachments/folders/{folderID}`
  - **Description**: Delete a job attachment folder.
  - **Parameters**: `companyID`, `jobID`, `folderID`
  - **Response**: No Content

#### CustomFields

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/customFields/`
  - **Description**: List all job custom fields.
  - **Parameters**: `companyID`, `jobID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific job custom field.
  - **Parameters**: `companyID`, `jobID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}/customFields/{customFieldID}`
  - **Description**: Update a job custom field.
  - **Parameters**: `companyID`, `jobID`, `customFieldID`
  - **Response**: No Content

#### Invoices

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/invoices/`
  - **Description**: List all customer invoices.
  - **Parameters**: `companyID`, `jobID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/jobs/{jobID}/invoices/`
  - **Description**: Create a new customer invoice.
  - **Parameters**: `companyID`, `jobID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/invoices/{invoiceID}`
  - **Description**: Retrieve details for a specific customer invoice.
  - **Parameters**: `companyID`, `jobID`, `invoiceID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}/invoices/{invoiceID}`
  - **Description**: Update a customer invoice.
  - **Parameters**: `companyID`, `jobID`, `invoiceID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/jobs/{jobID}/invoices/{invoiceID}`
  - **Description**: Delete a customer invoice.
  - **Parameters**: `companyID`, `jobID`, `invoiceID`
  - **Response**: No Content

#### Invoices > customFields

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/invoices/{invoiceID}/customFields/`
  - **Description**: List all job invoice custom fields.
  - **Parameters**: `companyID`, `jobID`, `invoiceID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/invoices/{invoiceID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific job invoice custom field.
  - **Parameters**: `companyID`, `jobID`, `invoiceID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}/invoices/{invoiceID}/customFields/{customFieldID}`
  - **Description**: Update a job invoice custom field.
  - **Parameters**: `companyID`, `jobID`, `invoiceID`, `customFieldID`
  - **Response**: No Content

#### Lock

- [ ] `POST /api/v1.0/companies/{companyID}/jobs/{jobID}/lock/`
  - **Description**: Create a new job lock.
  - **Parameters**: `companyID`, `jobID`
  - **Response**: Unknown

- [ ] `DELETE /api/v1.0/companies/{companyID}/jobs/{jobID}/lock/`
  - **Description**: Delete a job lock.
  - **Response**: No Content

#### Notes

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/notes/`
  - **Description**: List all job notes.
  - **Parameters**: `companyID`, `jobID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/jobs/{jobID}/notes/`
  - **Description**: Create a new job note.
  - **Parameters**: `companyID`, `jobID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/notes/{noteID}`
  - **Description**: Retrieve details for a specific job note.
  - **Parameters**: `companyID`, `jobID`, `noteID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}/notes/{noteID}`
  - **Description**: Update a job note.
  - **Parameters**: `companyID`, `jobID`, `noteID`
  - **Response**: No Content

#### Sections

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/`
  - **Description**: List all job sections.
  - **Parameters**: `companyID`, `jobID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/`
  - **Description**: Create a new job section.
  - **Parameters**: `companyID`, `jobID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}`
  - **Description**: Retrieve details for a specific job section.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}`
  - **Description**: Update a job section.
  - **Parameters**: `companyID`, `jobID`, `sectionID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}`
  - **Description**: Delete a job section.
  - **Parameters**: `companyID`, `jobID`, `sectionID`
  - **Response**: No Content

#### Sections > costCenters

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/`
  - **Description**: List all job cost centers.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/`
  - **Description**: Create a new job cost center.
  - **Parameters**: `companyID`, `jobID`, `sectionID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}`
  - **Description**: Retrieve details for a specific job cost center.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}`
  - **Description**: Update a job cost center.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}`
  - **Description**: Delete a job cost center.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`
  - **Response**: No Content

#### Sections > costCenters > assets

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/assets/`
  - **Description**: List all job cost center assets.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/assets/`
  - **Description**: Create a new job cost center asset.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `PUT /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/assets/`
  - **Description**: Replace job cost center assets.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/assets/{assetID}`
  - **Description**: Retrieve details for a specific job cost center asset.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `assetID`, `columns`?
  - **Response**: object

- [ ] `DELETE /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/assets/{assetID}`
  - **Description**: Delete a job cost center asset.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `assetID`
  - **Response**: No Content

#### Sections > costCenters > catalogs

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/catalogs/`
  - **Description**: List all job cost center catalog items.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/catalogs/`
  - **Description**: Create a new job cost center catalog item.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `PUT /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/catalogs/`
  - **Description**: Replace job cost center catalog items.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/catalogs/{catalogID}`
  - **Description**: Retrieve details for a specific job cost center catalog item.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `catalogID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/catalogs/{catalogID}`
  - **Description**: Update a job cost center catalog item.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `catalogID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/catalogs/{catalogID}`
  - **Description**: Delete a job cost center catalog item.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `catalogID`
  - **Response**: No Content

#### Sections > costCenters > contractorJobs

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/`
  - **Description**: List all job contractor jobs.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/`
  - **Description**: Create a new job contractor job.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}`
  - **Description**: Retrieve details for a specific job contractor job.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `contractorJobID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}`
  - **Description**: Update a job contractor job.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `contractorJobID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}`
  - **Description**: Delete a job contractor job.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `contractorJobID`
  - **Response**: No Content

#### Sections > costCenters > contractorJobs > attachments > files

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/attachments/files/`
  - **Description**: List all contractor job attachments.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `contractorJobID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/attachments/files/`
  - **Description**: Create a new contractor job attachment.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `contractorJobID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific contractor job attachment.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `contractorJobID`, `fileID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/attachments/files/{fileID}`
  - **Description**: Update a contractor job attachment.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `contractorJobID`, `fileID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/attachments/files/{fileID}`
  - **Description**: Delete a contractor job attachment.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `contractorJobID`, `fileID`
  - **Response**: No Content

#### Sections > costCenters > contractorJobs > attachments > folders

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/attachments/folders/`
  - **Description**: List all contractor job attachment folders.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `contractorJobID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/attachments/folders/`
  - **Description**: Create a new contractor job attachment folder.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `contractorJobID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/attachments/folders/{folderID}`
  - **Description**: Retrieve details for a specific contractor job attachment folder.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `contractorJobID`, `folderID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/attachments/folders/{folderID}`
  - **Description**: Update a contractor job attachment folder.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `contractorJobID`, `folderID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/attachments/folders/{folderID}`
  - **Description**: Delete a contractor job attachment folder.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `contractorJobID`, `folderID`
  - **Response**: No Content

#### Sections > costCenters > contractorJobs > customFields

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/customFields/`
  - **Description**: List all job contractor job custom fields.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `contractorJobID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific job contractor job custom field.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `contractorJobID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/customFields/{customFieldID}`
  - **Description**: Update a job contractor job custom field.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `contractorJobID`, `customFieldID`
  - **Response**: No Content

#### Sections > costCenters > labor

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/labor/`
  - **Description**: List all job cost center labor items.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/labor/`
  - **Description**: Create a new job cost center labor item.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `PUT /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/labor/`
  - **Description**: Replace job cost center labor items.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/labor/{laborID}`
  - **Description**: Retrieve details for a specific job cost center labor item.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `laborID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/labor/{laborID}`
  - **Description**: Update a job cost center labor item.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `laborID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/labor/{laborID}`
  - **Description**: Delete a job cost center labor item.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `laborID`
  - **Response**: No Content

#### Sections > costCenters > lock

- [ ] `POST /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/lock/`
  - **Description**: Create a new job cost center lock.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`
  - **Response**: Unknown

- [ ] `DELETE /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/lock/`
  - **Description**: Delete a job cost center lock.
  - **Response**: No Content

#### Sections > costCenters > oneOffs

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/oneOffs/`
  - **Description**: List all job cost center one-off items.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/oneOffs/`
  - **Description**: Create a new job cost center one-off item.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `PUT /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/oneOffs/`
  - **Description**: Replace job cost center one-off items.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/oneOffs/{oneOffID}`
  - **Description**: Retrieve details for a specific job cost center one-off item.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `oneOffID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/oneOffs/{oneOffID}`
  - **Description**: Update a job cost center one-off item.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `oneOffID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/oneOffs/{oneOffID}`
  - **Description**: Delete a job cost center one-off item.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `oneOffID`
  - **Response**: No Content

#### Sections > costCenters > prebuilds

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/prebuilds/`
  - **Description**: List all job cost center prebuild items.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/prebuilds/`
  - **Description**: Create a new job cost center prebuild item.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `PUT /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/prebuilds/`
  - **Description**: Replace job cost center prebuild items.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/prebuilds/{prebuildID}`
  - **Description**: Retrieve details for a specific job cost center prebuild item.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `prebuildID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/prebuilds/{prebuildID}`
  - **Description**: Update a job cost center prebuild item.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `prebuildID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/prebuilds/{prebuildID}`
  - **Description**: Delete a job cost center prebuild item.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `prebuildID`
  - **Response**: No Content

#### Sections > costCenters > schedules

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/schedules/`
  - **Description**: List all job cost center schedules.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/schedules/`
  - **Description**: Create a new job cost center schedule.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/schedules/{scheduleID}`
  - **Description**: Retrieve details for a specific job cost center schedule.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `scheduleID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/schedules/{scheduleID}`
  - **Description**: Update a job cost center schedule.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `scheduleID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/schedules/{scheduleID}`
  - **Description**: Delete a job cost center schedule.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `scheduleID`
  - **Response**: No Content

#### Sections > costCenters > serviceFees

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/serviceFees/`
  - **Description**: List all job cost center service fees.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/serviceFees/`
  - **Description**: Create a new job cost center service fee.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `PUT /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/serviceFees/`
  - **Description**: Replace job cost center service fees.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/serviceFees/{serviceFeeID}`
  - **Description**: Retrieve details for a specific job cost center service fee.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `serviceFeeID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/serviceFees/{serviceFeeID}`
  - **Description**: Update a job cost center service fee.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `serviceFeeID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/serviceFees/{serviceFeeID}`
  - **Description**: Delete a job cost center service fee.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `serviceFeeID`
  - **Response**: No Content

#### Sections > costCenters > stock

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/stock/`
  - **Description**: List all job cost center stock items.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/stock/`
  - **Description**: Create a new job cost center stock item.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/stock/{stockID}`
  - **Description**: Retrieve details for a specific job cost center stock item.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `stockID`
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/stock/{stockID}`
  - **Description**: Update a job cost center stock item.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `stockID`
  - **Response**: No Content

#### Sections > costCenters > tasks

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/tasks/`
  - **Description**: List all job cost center tasks.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/tasks/{taskID}`
  - **Description**: Retrieve details for a specific job cost center task.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `taskID`, `columns`?
  - **Response**: object

#### Sections > costCenters > workOrders

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/`
  - **Description**: List all job cost center work orders.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/`
  - **Description**: Create a new job cost center work order.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}`
  - **Description**: Retrieve details for a specific job cost center work order.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `workOrderID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}`
  - **Description**: Update a job cost center work order.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `workOrderID`
  - **Response**: No Content

#### Sections > costCenters > workOrders > assets

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/assets/`
  - **Description**: List all job cost center work order assets.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `workOrderID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/assets/{assetID}`
  - **Description**: Retrieve details for a specific job cost center work order asset.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `workOrderID`, `assetID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/assets/{assetID}`
  - **Description**: Update a job cost center work order asset.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `workOrderID`, `assetID`
  - **Response**: No Content

#### Sections > costCenters > workOrders > assets > testResults > attachments > files

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/assets/{assetID}/testResults/attachments/files/`
  - **Description**: List all job cost center work order asset test result attachments.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `workOrderID`, `assetID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/assets/{assetID}/testResults/attachments/files/`
  - **Description**: Create a new job cost center work order asset test result attachment.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `workOrderID`, `assetID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/assets/{assetID}/testResults/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific job cost center work order asset test result attachment.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `workOrderID`, `assetID`, `fileID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/assets/{assetID}/testResults/attachments/files/{fileID}`
  - **Description**: Update a job cost center work order asset test result attachment.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `workOrderID`, `assetID`, `fileID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/assets/{assetID}/testResults/attachments/files/{fileID}`
  - **Description**: Delete a job cost center work order asset test result attachment.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `workOrderID`, `assetID`, `fileID`
  - **Response**: No Content

#### Sections > costCenters > workOrders > attachments > files

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/attachments/files/`
  - **Description**: List all work order attachments.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `workOrderID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/attachments/files/`
  - **Description**: Create a new work order attachment.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `workOrderID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific work order attachment.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `workOrderID`, `fileID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/attachments/files/{fileID}`
  - **Description**: Update a work order attachment.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `workOrderID`, `fileID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/attachments/files/{fileID}`
  - **Description**: Delete a work order attachment.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `workOrderID`, `fileID`
  - **Response**: No Content

#### Sections > costCenters > workOrders > customFields

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/customFields/`
  - **Description**: List all job cost center work order custom fields.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `workOrderID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific job cost center work order custom field.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `workOrderID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/customFields/{customFieldID}`
  - **Description**: Update a job cost center work order custom field.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `workOrderID`, `customFieldID`
  - **Response**: No Content

#### Sections > costCenters > workOrders > mobileSignatures

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/mobileSignatures/`
  - **Description**: List all job cost center work order mobile signatures.
  - **Parameters**: `companyID`, `jobID`, `sectionID`, `costCenterID`, `workOrderID`
  - **Response**: Array of object

#### Tasks

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/tasks/`
  - **Description**: List all job tasks.
  - **Parameters**: `companyID`, `jobID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/tasks/{taskID}`
  - **Description**: Retrieve details for a specific job task.
  - **Parameters**: `companyID`, `jobID`, `taskID`, `columns`?
  - **Response**: object

#### Timelines

- [ ] `GET /api/v1.0/companies/{companyID}/jobs/{jobID}/timelines/`
  - **Description**: List all job timelines.
  - **Parameters**: `companyID`, `jobID`, `search`?, `columns`?
  - **Response**: Array of object

### Nested: Leads

- [ ] `GET /api/v1.0/companies/{companyID}/leads/`
  - **Description**: List all leads.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/leads/`
  - **Description**: Create a new lead.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/leads/{leadID}`
  - **Description**: Retrieve details for a specific lead.
  - **Parameters**: `companyID`, `leadID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/leads/{leadID}`
  - **Description**: Update a lead.
  - **Parameters**: `companyID`, `leadID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/leads/{leadID}`
  - **Description**: Delete a lead.
  - **Parameters**: `companyID`, `leadID`
  - **Response**: No Content

#### Attachments > files

- [ ] `GET /api/v1.0/companies/{companyID}/leads/{leadID}/attachments/files/`
  - **Description**: List all lead attachments.
  - **Parameters**: `companyID`, `leadID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/leads/{leadID}/attachments/files/`
  - **Description**: Create a new lead attachment.
  - **Parameters**: `companyID`, `leadID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/leads/{leadID}/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific lead attachment.
  - **Parameters**: `companyID`, `leadID`, `fileID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/leads/{leadID}/attachments/files/{fileID}`
  - **Description**: Update a lead attachment.
  - **Parameters**: `companyID`, `leadID`, `fileID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/leads/{leadID}/attachments/files/{fileID}`
  - **Description**: Delete a lead attachment.
  - **Parameters**: `companyID`, `leadID`, `fileID`
  - **Response**: No Content

#### Attachments > folders

- [ ] `GET /api/v1.0/companies/{companyID}/leads/{leadID}/attachments/folders/`
  - **Description**: List all lead attachment folders.
  - **Parameters**: `companyID`, `leadID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/leads/{leadID}/attachments/folders/`
  - **Description**: Create a new lead attachment folder.
  - **Parameters**: `companyID`, `leadID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/leads/{leadID}/attachments/folders/{folderID}`
  - **Description**: Retrieve details for a specific lead attachment folder.
  - **Parameters**: `companyID`, `leadID`, `folderID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/leads/{leadID}/attachments/folders/{folderID}`
  - **Description**: Update a lead attachment folder.
  - **Parameters**: `companyID`, `leadID`, `folderID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/leads/{leadID}/attachments/folders/{folderID}`
  - **Description**: Delete a lead attachment folder.
  - **Parameters**: `companyID`, `leadID`, `folderID`
  - **Response**: No Content

#### CustomFields

- [ ] `GET /api/v1.0/companies/{companyID}/leads/{leadID}/customFields/`
  - **Description**: List all lead custom fields.
  - **Parameters**: `companyID`, `leadID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/leads/{leadID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific lead custom field.
  - **Parameters**: `companyID`, `leadID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/leads/{leadID}/customFields/{customFieldID}`
  - **Description**: Update a lead custom field.
  - **Parameters**: `companyID`, `leadID`, `customFieldID`
  - **Response**: No Content

#### Schedules

- [ ] `GET /api/v1.0/companies/{companyID}/leads/{leadID}/schedules/`
  - **Description**: List all lead schedules.
  - **Parameters**: `companyID`, `leadID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/leads/{leadID}/schedules/`
  - **Description**: Create a new lead schedule.
  - **Parameters**: `companyID`, `leadID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/leads/{leadID}/schedules/{scheduleID}`
  - **Description**: Retrieve details for a specific lead schedule.
  - **Parameters**: `companyID`, `leadID`, `scheduleID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/leads/{leadID}/schedules/{scheduleID}`
  - **Description**: Update a lead schedule.
  - **Parameters**: `companyID`, `leadID`, `scheduleID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/leads/{leadID}/schedules/{scheduleID}`
  - **Description**: Delete a lead schedule.
  - **Parameters**: `companyID`, `leadID`, `scheduleID`
  - **Response**: No Content

### Nested: Logs

#### Contacts

- [ ] `GET /api/v1.0/companies/{companyID}/logs/contacts/`
  - **Description**: List all contact logs.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/logs/contacts/{logID}`
  - **Description**: Retrieve details for a specific contact log.
  - **Parameters**: `companyID`, `logID`, `columns`?
  - **Response**: object

#### ContractorJobs

- [ ] `GET /api/v1.0/companies/{companyID}/logs/contractorJobs/`
  - **Description**: List all contractor job logs.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/logs/contractorJobs/{logID}`
  - **Description**: Retrieve details for a specific contractor job log.
  - **Parameters**: `companyID`, `logID`, `columns`?
  - **Response**: object

#### CustomerInvoices

- [ ] `GET /api/v1.0/companies/{companyID}/logs/customerInvoices/`
  - **Description**: List all customer invoice logs.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/logs/customerInvoices/{logID}`
  - **Description**: Retrieve details for a specific customer invoice log.
  - **Parameters**: `companyID`, `logID`, `columns`?
  - **Response**: object

#### Customers

- [ ] `GET /api/v1.0/companies/{companyID}/logs/customers/`
  - **Description**: List all customer logs.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/logs/customers/{logID}`
  - **Description**: Retrieve details for a specific customer log.
  - **Parameters**: `companyID`, `logID`, `columns`?
  - **Response**: object

#### Jobs

- [ ] `GET /api/v1.0/companies/{companyID}/logs/jobs/`
  - **Description**: List all job logs.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/logs/jobs/{logID}`
  - **Description**: Retrieve details for a specific job log.
  - **Parameters**: `companyID`, `logID`, `columns`?
  - **Response**: object

#### MobileStatus

- [ ] `GET /api/v1.0/companies/{companyID}/logs/mobileStatus/`
  - **Description**: List all mobile status logs.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/logs/mobileStatus/{logID}`
  - **Description**: Retrieve details for a specific mobile status log.
  - **Parameters**: `companyID`, `logID`, `columns`?
  - **Response**: object

#### Quotes

- [ ] `GET /api/v1.0/companies/{companyID}/logs/quotes/`
  - **Description**: List all quote logs.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/logs/quotes/{logID}`
  - **Description**: Retrieve details for a specific quote log.
  - **Parameters**: `companyID`, `logID`, `columns`?
  - **Response**: object

#### RecurringInvoices

- [ ] `GET /api/v1.0/companies/{companyID}/logs/recurringInvoices/`
  - **Description**: List all recurring invoice logs.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/logs/recurringInvoices/{logID}`
  - **Description**: Retrieve details for a specific recurring invoice log.
  - **Parameters**: `companyID`, `logID`, `columns`?
  - **Response**: object

#### Schedules

- [ ] `GET /api/v1.0/companies/{companyID}/logs/schedules/`
  - **Description**: List all schedule logs.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/logs/schedules/{logID}`
  - **Description**: Retrieve details for a specific schedule log.
  - **Parameters**: `companyID`, `logID`, `columns`?
  - **Response**: object

#### VendorOrders

- [ ] `GET /api/v1.0/companies/{companyID}/logs/vendorOrders/`
  - **Description**: List all vendor order logs.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/logs/vendorOrders/{logID}`
  - **Description**: Retrieve details for a specific vendor order log.
  - **Parameters**: `companyID`, `logID`, `columns`?
  - **Response**: object

### Nested: Notes

#### Customers

- [ ] `GET /api/v1.0/companies/{companyID}/notes/customers/`
  - **Description**: List all notes.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

#### Jobs

- [ ] `GET /api/v1.0/companies/{companyID}/notes/jobs/`
  - **Description**: List all notes.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

### Nested: PlantTypes

- [ ] `GET /api/v1.0/companies/{companyID}/plantTypes/`
  - **Description**: List all plant types.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/plantTypes/`
  - **Description**: Create a new plant type.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}`
  - **Description**: Retrieve details for a specific plant type.
  - **Parameters**: `companyID`, `plantTypeID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}`
  - **Description**: Update a plant type.
  - **Parameters**: `companyID`, `plantTypeID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}`
  - **Description**: Delete a plant type.
  - **Parameters**: `companyID`, `plantTypeID`
  - **Response**: No Content

#### CustomFields

- [ ] `GET /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/customFields/`
  - **Description**: List all plant type custom fields.
  - **Parameters**: `companyID`, `plantTypeID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/customFields/`
  - **Description**: Create a new plant type custom field.
  - **Parameters**: `companyID`, `plantTypeID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/customFields/{plantTypeCustomFieldID}`
  - **Description**: Retrieve details for a specific plant type custom field.
  - **Parameters**: `companyID`, `plantTypeID`, `plantTypeCustomFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/customFields/{plantTypeCustomFieldID}`
  - **Description**: Update a plant type custom field.
  - **Parameters**: `companyID`, `plantTypeID`, `plantTypeCustomFieldID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/customFields/{plantTypeCustomFieldID}`
  - **Description**: Delete a plant type custom field.
  - **Parameters**: `companyID`, `plantTypeID`, `plantTypeCustomFieldID`
  - **Response**: No Content

#### Plants

- [ ] `GET /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/plants/`
  - **Description**: List all plant and equipment.
  - **Parameters**: `companyID`, `plantTypeID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/plants/`
  - **Description**: Create a new plant and equipment.
  - **Parameters**: `companyID`, `plantTypeID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/plants/{plantID}`
  - **Description**: Retrieve details for a specific plant and equipment.
  - **Parameters**: `companyID`, `plantTypeID`, `plantID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/plants/{plantID}`
  - **Description**: Update a plant and equipment.
  - **Parameters**: `companyID`, `plantTypeID`, `plantID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/plants/{plantID}`
  - **Description**: Delete a plant and equipment.
  - **Parameters**: `companyID`, `plantTypeID`, `plantID`
  - **Response**: No Content

#### Plants > attachments > files

- [ ] `GET /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/plants/{plantID}/attachments/files/`
  - **Description**: List all plant and equipment attachments.
  - **Parameters**: `companyID`, `plantTypeID`, `plantID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/plants/{plantID}/attachments/files/`
  - **Description**: Create a new plant and equipment attachment.
  - **Parameters**: `companyID`, `plantTypeID`, `plantID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/plants/{plantID}/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific plant and equipment attachment.
  - **Parameters**: `companyID`, `plantTypeID`, `plantID`, `fileID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/plants/{plantID}/attachments/files/{fileID}`
  - **Description**: Update a plant and equipment attachment.
  - **Parameters**: `companyID`, `plantTypeID`, `plantID`, `fileID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/plants/{plantID}/attachments/files/{fileID}`
  - **Description**: Delete a plant and equipment attachment.
  - **Parameters**: `companyID`, `plantTypeID`, `plantID`, `fileID`
  - **Response**: No Content

#### Plants > attachments > folders

- [ ] `GET /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/plants/{plantID}/attachments/folders/`
  - **Description**: List all plant and equipment attachment folders.
  - **Parameters**: `companyID`, `plantTypeID`, `plantID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/plants/{plantID}/attachments/folders/`
  - **Description**: Create a new plant and equipment attachment folder.
  - **Parameters**: `companyID`, `plantTypeID`, `plantID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/plants/{plantID}/attachments/folders/{folderID}`
  - **Description**: Retrieve details for a specific plant and equipment attachment folder.
  - **Parameters**: `companyID`, `plantTypeID`, `plantID`, `folderID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/plants/{plantID}/attachments/folders/{folderID}`
  - **Description**: Update a plant and equipment attachment folder.
  - **Parameters**: `companyID`, `plantTypeID`, `plantID`, `folderID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/plants/{plantID}/attachments/folders/{folderID}`
  - **Description**: Delete a plant and equipment attachment folder.
  - **Parameters**: `companyID`, `plantTypeID`, `plantID`, `folderID`
  - **Response**: No Content

#### Plants > customFields

- [ ] `GET /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/plants/{plantID}/customFields/`
  - **Description**: List all plant and equipment custom fields.
  - **Parameters**: `companyID`, `plantTypeID`, `plantID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/plants/{plantID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific plant and equipment custom field.
  - **Parameters**: `companyID`, `plantTypeID`, `plantID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/plants/{plantID}/customFields/{customFieldID}`
  - **Description**: Update a plant and equipment custom field.
  - **Parameters**: `companyID`, `plantTypeID`, `plantID`, `customFieldID`
  - **Response**: No Content

#### Plants > services

- [ ] `GET /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/plants/{plantID}/services/`
  - **Description**: List all plant and equipment services.
  - **Parameters**: `companyID`, `plantTypeID`, `plantID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/plants/{plantID}/services/`
  - **Description**: Create a new plant and equipment service.
  - **Parameters**: `companyID`, `plantTypeID`, `plantID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/plants/{plantID}/services/{serviceID}`
  - **Description**: Retrieve details for a specific plant and equipment service.
  - **Parameters**: `companyID`, `plantTypeID`, `plantID`, `serviceID`, `columns`?
  - **Response**: object

- [ ] `DELETE /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/plants/{plantID}/services/{serviceID}`
  - **Description**: Delete a plant and equipment service.
  - **Parameters**: `companyID`, `plantTypeID`, `plantID`, `serviceID`
  - **Response**: No Content

#### Plants > timesheets

- [ ] `GET /api/v1.0/companies/{companyID}/plantTypes/{plantTypeID}/plants/{plantID}/timesheets/`
  - **Description**: List all plant timesheets.
  - **Parameters**: `companyID`, `plantTypeID`, `plantID`, `search`?, `columns`?, `UID`?, `StartDate`?, `EndDate`?, `Includes`?, `ScheduleType`?
  - **Response**: Array of object

### Nested: PrebuildGroups

- [ ] `GET /api/v1.0/companies/{companyID}/prebuildGroups/`
  - **Description**: List all prebuild groups.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/prebuildGroups/`
  - **Description**: Create a new prebuild group.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/prebuildGroups/{groupID}`
  - **Description**: Retrieve details for a specific prebuild group.
  - **Parameters**: `companyID`, `groupID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/prebuildGroups/{groupID}`
  - **Description**: Update a prebuild group.
  - **Parameters**: `companyID`, `groupID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/prebuildGroups/{groupID}`
  - **Description**: Delete a prebuild group.
  - **Parameters**: `companyID`, `groupID`
  - **Response**: No Content

### Nested: Prebuilds

- [ ] `GET /api/v1.0/companies/{companyID}/prebuilds/`
  - **Description**: List all prebuilds.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

#### Attachments > files

- [ ] `GET /api/v1.0/companies/{companyID}/prebuilds/{prebuildID}/attachments/files/`
  - **Description**: List all prebuild attachments.
  - **Parameters**: `companyID`, `prebuildID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/prebuilds/{prebuildID}/attachments/files/`
  - **Description**: Create a new prebuild attachment.
  - **Parameters**: `companyID`, `prebuildID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/prebuilds/{prebuildID}/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific prebuild attachment.
  - **Parameters**: `companyID`, `prebuildID`, `fileID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/prebuilds/{prebuildID}/attachments/files/{fileID}`
  - **Description**: Update a prebuild attachment.
  - **Parameters**: `companyID`, `prebuildID`, `fileID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/prebuilds/{prebuildID}/attachments/files/{fileID}`
  - **Description**: Delete a prebuild attachment.
  - **Parameters**: `companyID`, `prebuildID`, `fileID`
  - **Response**: No Content

#### Attachments > folders

- [ ] `GET /api/v1.0/companies/{companyID}/prebuilds/{prebuildID}/attachments/folders/`
  - **Description**: List all prebuild attachment folders.
  - **Parameters**: `companyID`, `prebuildID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/prebuilds/{prebuildID}/attachments/folders/`
  - **Description**: Create a new prebuild attachment folder.
  - **Parameters**: `companyID`, `prebuildID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/prebuilds/{prebuildID}/attachments/folders/{folderID}`
  - **Description**: Retrieve details for a specific prebuild attachment folder.
  - **Parameters**: `companyID`, `prebuildID`, `folderID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/prebuilds/{prebuildID}/attachments/folders/{folderID}`
  - **Description**: Update a prebuild attachment folder.
  - **Parameters**: `companyID`, `prebuildID`, `folderID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/prebuilds/{prebuildID}/attachments/folders/{folderID}`
  - **Description**: Delete a prebuild attachment folder.
  - **Parameters**: `companyID`, `prebuildID`, `folderID`
  - **Response**: No Content

#### Catalogs

- [ ] `GET /api/v1.0/companies/{companyID}/prebuilds/{prebuildID}/catalogs/`
  - **Description**: List all prebuild catalogs.
  - **Parameters**: `companyID`, `prebuildID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/prebuilds/{prebuildID}/catalogs/`
  - **Description**: Create a new prebuild catalog.
  - **Parameters**: `companyID`, `prebuildID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/prebuilds/{prebuildID}/catalogs/{catalogID}`
  - **Description**: Retrieve details for a specific prebuild catalog.
  - **Parameters**: `companyID`, `prebuildID`, `catalogID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/prebuilds/{prebuildID}/catalogs/{catalogID}`
  - **Description**: Update a prebuild catalog.
  - **Parameters**: `companyID`, `prebuildID`, `catalogID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/prebuilds/{prebuildID}/catalogs/{catalogID}`
  - **Description**: Delete a prebuild catalog.
  - **Parameters**: `companyID`, `prebuildID`, `catalogID`
  - **Response**: No Content

#### CustomFields

- [ ] `GET /api/v1.0/companies/{companyID}/prebuilds/{prebuildID}/customFields/`
  - **Description**: List all prebuild custom fields.
  - **Parameters**: `companyID`, `prebuildID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/prebuilds/{prebuildID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific prebuild custom field.
  - **Parameters**: `companyID`, `prebuildID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/prebuilds/{prebuildID}/customFields/{customFieldID}`
  - **Description**: Update a prebuild custom field.
  - **Parameters**: `companyID`, `prebuildID`, `customFieldID`
  - **Response**: No Content

#### SetPrice

- [ ] `GET /api/v1.0/companies/{companyID}/prebuilds/setPrice/`
  - **Description**: List all set-price prebuilds.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/prebuilds/setPrice/`
  - **Description**: Create a new set-price prebuild.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/prebuilds/setPrice/{setPricePrebuildID}`
  - **Description**: Retrieve details for a specific set-price prebuild.
  - **Parameters**: `companyID`, `setPricePrebuildID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/prebuilds/setPrice/{setPricePrebuildID}`
  - **Description**: Update a set-price prebuild.
  - **Parameters**: `companyID`, `setPricePrebuildID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/prebuilds/setPrice/{setPricePrebuildID}`
  - **Description**: Delete a set-price prebuild.
  - **Parameters**: `companyID`, `setPricePrebuildID`
  - **Response**: No Content

#### StandardPrice

- [ ] `GET /api/v1.0/companies/{companyID}/prebuilds/standardPrice/`
  - **Description**: List all standard-price prebuilds.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/prebuilds/standardPrice/`
  - **Description**: Create a new standard-price prebuild.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/prebuilds/standardPrice/{standardPricePrebuildID}`
  - **Description**: Retrieve details for a specific standard-price prebuild.
  - **Parameters**: `companyID`, `standardPricePrebuildID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/prebuilds/standardPrice/{standardPricePrebuildID}`
  - **Description**: Update a standard-price prebuild.
  - **Parameters**: `companyID`, `standardPricePrebuildID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/prebuilds/standardPrice/{standardPricePrebuildID}`
  - **Description**: Delete a standard-price prebuild.
  - **Parameters**: `companyID`, `standardPricePrebuildID`
  - **Response**: No Content

### Nested: QuoteCostCenters

- [ ] `GET /api/v1.0/companies/{companyID}/quoteCostCenters/`
  - **Description**: List all cost centers.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

### Nested: QuoteWorkOrders

- [ ] `GET /api/v1.0/companies/{companyID}/quoteWorkOrders/`
  - **Description**: List all quote work orders.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/quoteWorkOrders/{quoteWorkOrderID}`
  - **Description**: Retrieve details for a specific quote work order.
  - **Parameters**: `companyID`, `quoteWorkOrderID`, `columns`?
  - **Response**: object

### Nested: Quotes

- [x] `GET /api/v1.0/companies/{companyID}/quotes/`
  - **Description**: List all quotes.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [x] `POST /api/v1.0/companies/{companyID}/quotes/`
  - **Description**: Create a new quote.
  - **Parameters**: `companyID`
  - **Response**: Created

- [x] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}`
  - **Description**: Retrieve details for a specific quote.
  - **Parameters**: `companyID`, `quoteID`, `columns`?
  - **Response**: object

- [x] `PATCH /api/v1.0/companies/{companyID}/quotes/{quoteID}`
  - **Description**: Update a quote.
  - **Parameters**: `companyID`, `quoteID`
  - **Response**: No Content

- [x] `DELETE /api/v1.0/companies/{companyID}/quotes/{quoteID}`
  - **Description**: Delete a quote.
  - **Parameters**: `companyID`, `quoteID`
  - **Response**: No Content

#### Attachments > files

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/attachments/files/`
  - **Description**: List all quote attachments.
  - **Parameters**: `companyID`, `quoteID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/quotes/{quoteID}/attachments/files/`
  - **Description**: Create a new quote attachment.
  - **Parameters**: `companyID`, `quoteID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific quote attachment.
  - **Parameters**: `companyID`, `quoteID`, `fileID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/quotes/{quoteID}/attachments/files/{fileID}`
  - **Description**: Update a quote attachment.
  - **Parameters**: `companyID`, `quoteID`, `fileID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/quotes/{quoteID}/attachments/files/{fileID}`
  - **Description**: Delete a quote attachment.
  - **Parameters**: `companyID`, `quoteID`, `fileID`
  - **Response**: No Content

#### Attachments > folders

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/attachments/folders/`
  - **Description**: List all quote attachment folders.
  - **Parameters**: `companyID`, `quoteID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/quotes/{quoteID}/attachments/folders/`
  - **Description**: Create a new quote attachment folder.
  - **Parameters**: `companyID`, `quoteID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/attachments/folders/{folderID}`
  - **Description**: Retrieve details for a specific quote attachment folder.
  - **Parameters**: `companyID`, `quoteID`, `folderID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/quotes/{quoteID}/attachments/folders/{folderID}`
  - **Description**: Update a quote attachment folder.
  - **Parameters**: `companyID`, `quoteID`, `folderID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/quotes/{quoteID}/attachments/folders/{folderID}`
  - **Description**: Delete a quote attachment folder.
  - **Parameters**: `companyID`, `quoteID`, `folderID`
  - **Response**: No Content

#### CustomFields

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/customFields/`
  - **Description**: List all quote custom fields.
  - **Parameters**: `companyID`, `quoteID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific quote custom field.
  - **Parameters**: `companyID`, `quoteID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/quotes/{quoteID}/customFields/{customFieldID}`
  - **Description**: Update a quote custom field.
  - **Parameters**: `companyID`, `quoteID`, `customFieldID`
  - **Response**: No Content

#### Lock

- [ ] `POST /api/v1.0/companies/{companyID}/quotes/{quoteID}/lock/`
  - **Description**: Create a new quote lock.
  - **Parameters**: `companyID`, `quoteID`
  - **Response**: Unknown

- [ ] `DELETE /api/v1.0/companies/{companyID}/quotes/{quoteID}/lock/`
  - **Description**: Delete a quote lock.
  - **Response**: No Content

#### Notes

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/notes/`
  - **Description**: List all quote notes.
  - **Parameters**: `companyID`, `quoteID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/quotes/{quoteID}/notes/`
  - **Description**: Create a new quote note.
  - **Parameters**: `companyID`, `quoteID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/notes/{noteID}`
  - **Description**: Retrieve details for a specific quote note.
  - **Parameters**: `companyID`, `quoteID`, `noteID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/quotes/{quoteID}/notes/{noteID}`
  - **Description**: Update a quote note.
  - **Parameters**: `companyID`, `quoteID`, `noteID`
  - **Response**: No Content

#### Sections

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/`
  - **Description**: List all quote sections.
  - **Parameters**: `companyID`, `quoteID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/`
  - **Description**: Create a new quote section.
  - **Parameters**: `companyID`, `quoteID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}`
  - **Description**: Retrieve details for a specific quote section.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}`
  - **Description**: Update a quote section.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}`
  - **Description**: Delete a quote section.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`
  - **Response**: No Content

#### Sections > costCenters

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/`
  - **Description**: List all quote cost centers.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/`
  - **Description**: Create a new quote cost center.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}`
  - **Description**: Retrieve details for a specific quote cost center.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}`
  - **Description**: Update a quote cost center.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}`
  - **Description**: Delete a quote cost center.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`
  - **Response**: No Content

#### Sections > costCenters > assets

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/assets/`
  - **Description**: List all quote cost center assets.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/assets/`
  - **Description**: Create a new quote cost center asset.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `PUT /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/assets/`
  - **Description**: Replace quote cost center assets.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/assets/{assetID}`
  - **Description**: Retrieve details for a specific quote cost center asset.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `assetID`, `columns`?
  - **Response**: object

- [ ] `DELETE /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/assets/{assetID}`
  - **Description**: Delete a quote cost center asset.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `assetID`
  - **Response**: No Content

#### Sections > costCenters > catalogs

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/catalogs/`
  - **Description**: List all quote cost center catalog items.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/catalogs/`
  - **Description**: Create a new quote cost center catalog item.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `PUT /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/catalogs/`
  - **Description**: Replace quote cost center catalog items.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/catalogs/{catalogID}`
  - **Description**: Retrieve details for a specific quote cost center catalog item.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `catalogID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/catalogs/{catalogID}`
  - **Description**: Update a quote cost center catalog item.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `catalogID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/catalogs/{catalogID}`
  - **Description**: Delete a quote cost center catalog item.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `catalogID`
  - **Response**: No Content

#### Sections > costCenters > contractorJobs

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/`
  - **Description**: List all quote contractor jobs.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/`
  - **Description**: Create a new quote contractor job.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}`
  - **Description**: Retrieve details for a specific quote contractor job.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `contractorJobID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}`
  - **Description**: Update a quote contractor job.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `contractorJobID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}`
  - **Description**: Delete a quote contractor job.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `contractorJobID`
  - **Response**: No Content

#### Sections > costCenters > contractorJobs > attachments > files

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/attachments/files/`
  - **Description**: List all quote contractor job attachments.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `contractorJobID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/attachments/files/`
  - **Description**: Create a new quote contractor job attachment.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `contractorJobID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific quote contractor job attachment.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `contractorJobID`, `fileID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/attachments/files/{fileID}`
  - **Description**: Update a quote contractor job attachment.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `contractorJobID`, `fileID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/attachments/files/{fileID}`
  - **Description**: Delete a quote contractor job attachment.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `contractorJobID`, `fileID`
  - **Response**: No Content

#### Sections > costCenters > contractorJobs > attachments > folders

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/attachments/folders/`
  - **Description**: List all quote contractor job attachment folders.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `contractorJobID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/attachments/folders/`
  - **Description**: Create a new quote contractor job attachment folder.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `contractorJobID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/attachments/folders/{folderID}`
  - **Description**: Retrieve details for a specific quote contractor job attachment folder.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `contractorJobID`, `folderID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/attachments/folders/{folderID}`
  - **Description**: Update a quote contractor job attachment folder.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `contractorJobID`, `folderID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/attachments/folders/{folderID}`
  - **Description**: Delete a quote contractor job attachment folder.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `contractorJobID`, `folderID`
  - **Response**: No Content

#### Sections > costCenters > contractorJobs > customFields

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/customFields/`
  - **Description**: List all quote contractor job custom fields.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `contractorJobID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific quote contractor job custom field.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `contractorJobID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/contractorJobs/{contractorJobID}/customFields/{customFieldID}`
  - **Description**: Update a quote contractor job custom field.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `contractorJobID`, `customFieldID`
  - **Response**: No Content

#### Sections > costCenters > labor

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/labor/`
  - **Description**: List all quote cost center labor items.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/labor/`
  - **Description**: Create a new quote cost center labor item.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `PUT /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/labor/`
  - **Description**: Replace quote cost center labor items.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/labor/{laborID}`
  - **Description**: Retrieve details for a specific quote cost center labor item.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `laborID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/labor/{laborID}`
  - **Description**: Update a quote cost center labor item.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `laborID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/labor/{laborID}`
  - **Description**: Delete a quote cost center labor item.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `laborID`
  - **Response**: No Content

#### Sections > costCenters > oneOffs

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/oneOffs/`
  - **Description**: List all quote cost center one-off items.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/oneOffs/`
  - **Description**: Create a new quote cost center one-off item.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `PUT /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/oneOffs/`
  - **Description**: Replace quote cost center one-off items.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/oneOffs/{oneOffID}`
  - **Description**: Retrieve details for a specific quote cost center one-off item.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `oneOffID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/oneOffs/{oneOffID}`
  - **Description**: Update a quote cost center one-off item.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `oneOffID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/oneOffs/{oneOffID}`
  - **Description**: Delete a quote cost center one-off item.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `oneOffID`
  - **Response**: No Content

#### Sections > costCenters > prebuilds

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/prebuilds/`
  - **Description**: List all quote cost center prebuild items.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/prebuilds/`
  - **Description**: Create a new quote cost center prebuild item.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `PUT /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/prebuilds/`
  - **Description**: Replace quote cost center prebuild items.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/prebuilds/{prebuildID}`
  - **Description**: Retrieve details for a specific quote cost center prebuild item.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `prebuildID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/prebuilds/{prebuildID}`
  - **Description**: Update a quote cost center prebuild item.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `prebuildID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/prebuilds/{prebuildID}`
  - **Description**: Delete a quote cost center prebuild item.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `prebuildID`
  - **Response**: No Content

#### Sections > costCenters > schedules

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/schedules/`
  - **Description**: List all quote cost center schedules.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/schedules/`
  - **Description**: Create a new quote cost center schedule.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/schedules/{scheduleID}`
  - **Description**: Retrieve details for a specific quote cost center schedule.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `scheduleID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/schedules/{scheduleID}`
  - **Description**: Update a quote cost center schedule.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `scheduleID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/schedules/{scheduleID}`
  - **Description**: Delete a quote cost center schedule.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `scheduleID`
  - **Response**: No Content

#### Sections > costCenters > serviceFees

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/serviceFees/`
  - **Description**: List all quote cost center service fees.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/serviceFees/`
  - **Description**: Create a new quote cost center service fee.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `PUT /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/serviceFees/`
  - **Description**: Replace quote cost center service fees.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/serviceFees/{serviceFeeID}`
  - **Description**: Retrieve details for a specific quote cost center service fee.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `serviceFeeID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/serviceFees/{serviceFeeID}`
  - **Description**: Update a quote cost center service fee.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `serviceFeeID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/serviceFees/{serviceFeeID}`
  - **Description**: Delete a quote cost center service fee.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `serviceFeeID`
  - **Response**: No Content

#### Sections > costCenters > tasks

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/tasks/`
  - **Description**: List all quote cost center tasks.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/tasks/{taskID}`
  - **Description**: Retrieve details for a specific quote cost center task.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `taskID`, `columns`?
  - **Response**: object

#### Sections > costCenters > workOrders

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/`
  - **Description**: List all quote cost center work orders.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/`
  - **Description**: Create a new quote cost center work order.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}`
  - **Description**: Retrieve details for a specific quote cost center work order.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `workOrderID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}`
  - **Description**: Update a quote cost center work order.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `workOrderID`
  - **Response**: No Content

#### Sections > costCenters > workOrders > assets

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/assets/`
  - **Description**: List all quote cost center work orders assets.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `workOrderID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/assets/{assetID}`
  - **Description**: Retrieve details for a specific quote cost center work orders asset.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `workOrderID`, `assetID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/assets/{assetID}`
  - **Description**: Update a quote cost center work orders asset.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `workOrderID`, `assetID`
  - **Response**: No Content

#### Sections > costCenters > workOrders > attachments > files

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/attachments/files/`
  - **Description**: List all work order attachments.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `workOrderID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/attachments/files/`
  - **Description**: Create a new work order attachment.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `workOrderID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific work order attachment.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `workOrderID`, `fileID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/attachments/files/{fileID}`
  - **Description**: Update a work order attachment.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `workOrderID`, `fileID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/attachments/files/{fileID}`
  - **Description**: Delete a work order attachment.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `workOrderID`, `fileID`
  - **Response**: No Content

#### Sections > costCenters > workOrders > customFields

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/customFields/`
  - **Description**: List all quote cost center work orders custom fields.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `workOrderID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific quote cost center work orders custom field.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `workOrderID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/quotes/{quoteID}/sections/{sectionID}/costCenters/{costCenterID}/workOrders/{workOrderID}/customFields/{customFieldID}`
  - **Description**: Update a quote cost center work orders custom field.
  - **Parameters**: `companyID`, `quoteID`, `sectionID`, `costCenterID`, `workOrderID`, `customFieldID`
  - **Response**: No Content

#### Tasks

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/tasks/`
  - **Description**: List all quote tasks.
  - **Parameters**: `companyID`, `quoteID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/tasks/{taskID}`
  - **Description**: Retrieve details for a specific quote task.
  - **Parameters**: `companyID`, `quoteID`, `taskID`, `columns`?
  - **Response**: object

#### Timelines

- [ ] `GET /api/v1.0/companies/{companyID}/quotes/{quoteID}/timelines/`
  - **Description**: List all quote timelines.
  - **Parameters**: `companyID`, `quoteID`, `search`?, `columns`?
  - **Response**: Array of object

### Nested: RecurringInvoiceCostCenters

- [ ] `GET /api/v1.0/companies/{companyID}/recurringInvoiceCostCenters/`
  - **Description**: List all cost centers.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

### Nested: RecurringInvoices

- [ ] `GET /api/v1.0/companies/{companyID}/recurringInvoices/`
  - **Description**: List all recurring invoices.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/recurringInvoices/`
  - **Description**: Create a new recurring invoice.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}`
  - **Description**: Retrieve details for a specific recurring invoice.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}`
  - **Description**: Update a recurring invoice.
  - **Parameters**: `companyID`, `recurringInvoiceID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}`
  - **Description**: Delete a recurring invoice.
  - **Parameters**: `companyID`, `recurringInvoiceID`
  - **Response**: No Content

#### Attachments > files

- [ ] `GET /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/attachments/files/`
  - **Description**: List all recurring invoice attachments.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/attachments/files/`
  - **Description**: Create a new recurring invoice attachment.
  - **Parameters**: `companyID`, `recurringInvoiceID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific recurring invoice attachment.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `fileID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/attachments/files/{fileID}`
  - **Description**: Update a recurring invoice attachment.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `fileID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/attachments/files/{fileID}`
  - **Description**: Delete a recurring invoice attachment.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `fileID`
  - **Response**: No Content

#### Attachments > folders

- [ ] `GET /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/attachments/folders/`
  - **Description**: List all recurring invoice attachment folders.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/attachments/folders/`
  - **Description**: Create a new recurring invoice attachment folder.
  - **Parameters**: `companyID`, `recurringInvoiceID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/attachments/folders/{folderID}`
  - **Description**: Retrieve details for a specific recurring invoice attachment folder.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `folderID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/attachments/folders/{folderID}`
  - **Description**: Update a recurring invoice attachment folder.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `folderID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/attachments/folders/{folderID}`
  - **Description**: Delete a recurring invoice attachment folder.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `folderID`
  - **Response**: No Content

#### CustomFields

- [ ] `GET /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/customFields/`
  - **Description**: List all recurring invoice custom fields.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific recurring invoice custom field.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/customFields/{customFieldID}`
  - **Description**: Update a recurring invoice custom field.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `customFieldID`
  - **Response**: No Content

#### Sections

- [ ] `GET /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/`
  - **Description**: List all recurring invoice sections.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/`
  - **Description**: Create a new recurring invoice section.
  - **Parameters**: `companyID`, `recurringInvoiceID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}`
  - **Description**: Retrieve details for a specific recurring invoice section.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}`
  - **Description**: Update a recurring invoice section.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}`
  - **Description**: Delete a recurring invoice section.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`
  - **Response**: No Content

#### Sections > costCenters

- [ ] `GET /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/`
  - **Description**: List all recurring invoice cost centers.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/`
  - **Description**: Create a new recurring invoice cost center.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}`
  - **Description**: Retrieve details for a specific recurring invoice cost center.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}`
  - **Description**: Update a recurring invoice cost center.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}`
  - **Description**: Delete a recurring invoice cost center.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`
  - **Response**: No Content

#### Sections > costCenters > catalogs

- [ ] `GET /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/catalogs/`
  - **Description**: List all recurring invoice cost center catalog items.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/catalogs/`
  - **Description**: Create a new recurring invoice cost center catalog item.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/catalogs/{catalogID}`
  - **Description**: Retrieve details for a specific recurring invoice cost center catalog item.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`, `catalogID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/catalogs/{catalogID}`
  - **Description**: Update a recurring invoice cost center catalog item.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`, `catalogID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/catalogs/{catalogID}`
  - **Description**: Delete a recurring invoice cost center catalog item.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`, `catalogID`
  - **Response**: No Content

#### Sections > costCenters > labor

- [ ] `GET /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/labor/`
  - **Description**: List all recurring invoice cost center labor items.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/labor/`
  - **Description**: Create a new recurring invoice cost center labor item.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/labor/{laborID}`
  - **Description**: Retrieve details for a specific recurring invoice cost center labor item.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`, `laborID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/labor/{laborID}`
  - **Description**: Update a recurring invoice cost center labor item.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`, `laborID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/labor/{laborID}`
  - **Description**: Delete a recurring invoice cost center labor item.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`, `laborID`
  - **Response**: No Content

#### Sections > costCenters > oneOffs

- [ ] `GET /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/oneOffs/`
  - **Description**: List all recurring invoice cost center one-off items.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/oneOffs/`
  - **Description**: Create a new recurring invoice cost center one-off item.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/oneOffs/{oneOffID}`
  - **Description**: Retrieve details for a specific recurring invoice cost center one-off item.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`, `oneOffID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/oneOffs/{oneOffID}`
  - **Description**: Update a recurring invoice cost center one-off item.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`, `oneOffID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/oneOffs/{oneOffID}`
  - **Description**: Delete a recurring invoice cost center one-off item.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`, `oneOffID`
  - **Response**: No Content

#### Sections > costCenters > prebuilds

- [ ] `GET /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/prebuilds/`
  - **Description**: List all recurring invoice cost center prebuild items.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/prebuilds/`
  - **Description**: Create a new recurring invoice cost center prebuild item.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/prebuilds/{prebuildID}`
  - **Description**: Retrieve details for a specific recurring invoice cost center prebuild item.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`, `prebuildID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/prebuilds/{prebuildID}`
  - **Description**: Update a recurring invoice cost center prebuild item.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`, `prebuildID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/prebuilds/{prebuildID}`
  - **Description**: Delete a recurring invoice cost center prebuild item.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`, `prebuildID`
  - **Response**: No Content

#### Sections > costCenters > serviceFees

- [ ] `GET /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/serviceFees/`
  - **Description**: List all recurring invoice cost center service fees.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/serviceFees/`
  - **Description**: Create a new recurring invoice cost center service fee.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/serviceFees/{serviceFeeID}`
  - **Description**: Retrieve details for a specific recurring invoice cost center service fee.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`, `serviceFeeID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/serviceFees/{serviceFeeID}`
  - **Description**: Update a recurring invoice cost center service fee.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`, `serviceFeeID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/sections/{sectionID}/costCenters/{costCenterID}/serviceFees/{serviceFeeID}`
  - **Description**: Delete a recurring invoice cost center service fee.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `sectionID`, `costCenterID`, `serviceFeeID`
  - **Response**: No Content

#### Timelines

- [ ] `GET /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}/timelines/`
  - **Description**: List all recurring invoice timeline.
  - **Parameters**: `companyID`, `recurringInvoiceID`, `search`?, `columns`?
  - **Response**: Array of object

### Nested: RecurringJobCostCenters

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobCostCenters/`
  - **Description**: List all cost centers.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

### Nested: RecurringJobs

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/`
  - **Description**: List all recurring jobs.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/recurringJobs/`
  - **Description**: Create a new recurring job.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}`
  - **Description**: Retrieve details for a specific recurring job.
  - **Parameters**: `companyID`, `recurringJobID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}`
  - **Description**: Update a recurring job.
  - **Parameters**: `companyID`, `recurringJobID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}`
  - **Description**: Delete a recurring job.
  - **Parameters**: `companyID`, `recurringJobID`
  - **Response**: No Content

#### Attachments > files

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/attachments/files/`
  - **Description**: List all recurring job attachments.
  - **Parameters**: `companyID`, `recurringJobID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/attachments/files/`
  - **Description**: Create a new recurring job attachment.
  - **Parameters**: `companyID`, `recurringJobID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific recurring job attachment.
  - **Parameters**: `companyID`, `recurringJobID`, `fileID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/attachments/files/{fileID}`
  - **Description**: Update a recurring job attachment.
  - **Parameters**: `companyID`, `recurringJobID`, `fileID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/attachments/files/{fileID}`
  - **Description**: Delete a recurring job attachment.
  - **Parameters**: `companyID`, `recurringJobID`, `fileID`
  - **Response**: No Content

#### Attachments > folders

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/attachments/folders/`
  - **Description**: List all recurring job attachment folders.
  - **Parameters**: `companyID`, `recurringJobID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/attachments/folders/`
  - **Description**: Create a new recurring job attachment folder.
  - **Parameters**: `companyID`, `recurringJobID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/attachments/folders/{folderID}`
  - **Description**: Retrieve details for a specific recurring job attachment folder.
  - **Parameters**: `companyID`, `recurringJobID`, `folderID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/attachments/folders/{folderID}`
  - **Description**: Update a recurring job attachment folder.
  - **Parameters**: `companyID`, `recurringJobID`, `folderID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/attachments/folders/{folderID}`
  - **Description**: Delete a recurring job attachment folder.
  - **Parameters**: `companyID`, `recurringJobID`, `folderID`
  - **Response**: No Content

#### CustomFields

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/customFields/`
  - **Description**: List all recurring job custom fields.
  - **Parameters**: `companyID`, `recurringJobID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific recurring job custom field.
  - **Parameters**: `companyID`, `recurringJobID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/customFields/{customFieldID}`
  - **Description**: Update a recurring job custom field.
  - **Parameters**: `companyID`, `recurringJobID`, `customFieldID`
  - **Response**: No Content

#### Notes

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/notes/`
  - **Description**: List all recurring job notes.
  - **Parameters**: `companyID`, `recurringJobID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/notes/`
  - **Description**: Create a new recurring job note.
  - **Parameters**: `companyID`, `recurringJobID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/notes/{noteID}`
  - **Description**: Retrieve details for a specific recurring job note.
  - **Parameters**: `companyID`, `recurringJobID`, `noteID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/notes/{noteID}`
  - **Description**: Update a recurring job note.
  - **Parameters**: `companyID`, `recurringJobID`, `noteID`
  - **Response**: No Content

#### Sections

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/`
  - **Description**: List all recurring job sections.
  - **Parameters**: `companyID`, `recurringJobID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/`
  - **Description**: Create a new recurring job section.
  - **Parameters**: `companyID`, `recurringJobID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}`
  - **Description**: Retrieve details for a specific recurring job section.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}`
  - **Description**: Update a recurring job section.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}`
  - **Description**: Delete a recurring job section.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`
  - **Response**: No Content

#### Sections > costCenters

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/`
  - **Description**: List all recurring job cost centers.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/`
  - **Description**: Create a new recurring job cost center.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}`
  - **Description**: Retrieve details for a specific recurring job cost center.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}`
  - **Description**: Update a recurring job cost center.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}`
  - **Description**: Delete a recurring job cost center.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`
  - **Response**: No Content

#### Sections > costCenters > assets

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/assets/`
  - **Description**: List all recurring job cost center assets.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/assets/`
  - **Description**: Create a new recurring job cost center asset.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `PUT /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/assets/`
  - **Description**: Replace recurring job cost center assets.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/assets/{assetID}`
  - **Description**: Retrieve details for a specific recurring job cost center asset.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`, `assetID`, `columns`?
  - **Response**: object

- [ ] `DELETE /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/assets/{assetID}`
  - **Description**: Delete a recurring job cost center asset.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`, `assetID`
  - **Response**: No Content

#### Sections > costCenters > catalogs

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/catalogs/`
  - **Description**: List all recurring job cost center catalogs.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/catalogs/`
  - **Description**: Create a new recurring job cost center catalog.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/catalogs/{catalogID}`
  - **Description**: Retrieve details for a specific recurring job cost center catalog.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`, `catalogID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/catalogs/{catalogID}`
  - **Description**: Update a recurring job cost center catalog.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`, `catalogID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/catalogs/{catalogID}`
  - **Description**: Delete a recurring job cost center catalog.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`, `catalogID`
  - **Response**: No Content

#### Sections > costCenters > labor

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/labor/`
  - **Description**: List all recurring job cost center labors.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/labor/`
  - **Description**: Create a new recurring job cost center labor.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/labor/{laborID}`
  - **Description**: Retrieve details for a specific recurring job cost center labor.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`, `laborID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/labor/{laborID}`
  - **Description**: Update a recurring job cost center labor.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`, `laborID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/labor/{laborID}`
  - **Description**: Delete a recurring job cost center labor.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`, `laborID`
  - **Response**: No Content

#### Sections > costCenters > oneOffs

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/oneOffs/`
  - **Description**: List all recurring job cost center one-offs.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/oneOffs/`
  - **Description**: Create a new recurring job cost center one-off.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/oneOffs/{oneOffID}`
  - **Description**: Retrieve details for a specific recurring job cost center one-off.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`, `oneOffID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/oneOffs/{oneOffID}`
  - **Description**: Update a recurring job cost center one-off.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`, `oneOffID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/oneOffs/{oneOffID}`
  - **Description**: Delete a recurring job cost center one-off.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`, `oneOffID`
  - **Response**: No Content

#### Sections > costCenters > prebuilds

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/prebuilds/`
  - **Description**: List all recurring job cost center prebuilds.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/prebuilds/`
  - **Description**: Create a new recurring job cost center prebuild.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/prebuilds/{prebuildID}`
  - **Description**: Retrieve details for a specific recurring job cost center prebuild.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`, `prebuildID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/prebuilds/{prebuildID}`
  - **Description**: Update a recurring job cost center prebuild.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`, `prebuildID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/prebuilds/{prebuildID}`
  - **Description**: Delete a recurring job cost center prebuild.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`, `prebuildID`
  - **Response**: No Content

#### Sections > costCenters > serviceFees

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/serviceFees/`
  - **Description**: List all recurring job cost center service fees.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/serviceFees/`
  - **Description**: Create a new recurring job cost center service fee.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/serviceFees/{serviceFeeID}`
  - **Description**: Retrieve details for a specific recurring job cost center service fee.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`, `serviceFeeID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/serviceFees/{serviceFeeID}`
  - **Description**: Update a recurring job cost center service fee.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`, `serviceFeeID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/sections/{sectionID}/costCenters/{costCenterID}/serviceFees/{serviceFeeID}`
  - **Description**: Delete a recurring job cost center service fee.
  - **Parameters**: `companyID`, `recurringJobID`, `sectionID`, `costCenterID`, `serviceFeeID`
  - **Response**: No Content

#### Timelines

- [ ] `GET /api/v1.0/companies/{companyID}/recurringJobs/{recurringJobID}/timelines/`
  - **Description**: List all recurring job timelines.
  - **Parameters**: `companyID`, `recurringJobID`, `search`?, `columns`?
  - **Response**: Array of object

### Nested: Reports

#### Jobs > costToComplete > financial

- [ ] `GET /api/v1.0/companies/{companyID}/reports/jobs/costToComplete/financial/`
  - **Description**: Report: cost to complete - financial view.
  - **Parameters**: `companyID`, `search`?, `date`?, `changeOrders`?, `includeCommitted`?, `includeOverheads`?, `businessGroup`?, `costCentre`?, `customerID`?, `siteID`?, `customerGroup`?, `customerProfile`?, `salesPerson`?, `projectManager`?, `projectStatus`?, `projectTags`?, `customerTags`?, `jobID`?
  - **Response**: Array of object

#### Jobs > costToComplete > operations

- [ ] `GET /api/v1.0/companies/{companyID}/reports/jobs/costToComplete/operations/`
  - **Description**: Report: cost to complete - operations view.
  - **Parameters**: `companyID`, `search`?, `date`?, `changeOrders`?, `includeCommitted`?, `includeOverheads`?, `businessGroup`?, `costCentre`?, `customerID`?, `siteID`?, `customerGroup`?, `customerProfile`?, `salesPerson`?, `projectManager`?, `projectStatus`?, `projectTags`?, `customerTags`?, `jobID`?
  - **Response**: Array of object

### Nested: Schedules

- [x] `GET /api/v1.0/companies/{companyID}/schedules/`
  - **Description**: List all schedules.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [x] `GET /api/v1.0/companies/{companyID}/schedules/{scheduleID}`
  - **Description**: Retrieve details for a specific schedule.
  - **Parameters**: `companyID`, `scheduleID`, `columns`?
  - **Response**: object

### Nested: Setup

#### Accounts > accCategories

- [ ] `GET /api/v1.0/companies/{companyID}/setup/accounts/accCategories/`
  - **Description**: List all accounting categories.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/accounts/accCategories/`
  - **Description**: Create a new accounting category.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/accounts/accCategories/{accCategoryID}`
  - **Description**: Retrieve details for a specific accounting category.
  - **Parameters**: `companyID`, `accCategoryID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/accounts/accCategories/{accCategoryID}`
  - **Description**: Update a accounting category.
  - **Parameters**: `companyID`, `accCategoryID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/accounts/accCategories/{accCategoryID}`
  - **Description**: Delete a accounting category.
  - **Parameters**: `companyID`, `accCategoryID`
  - **Response**: No Content

#### Accounts > businessGroups

- [ ] `GET /api/v1.0/companies/{companyID}/setup/accounts/businessGroups/`
  - **Description**: List all business groups.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/accounts/businessGroups/`
  - **Description**: Create a new business group.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/accounts/businessGroups/{businessGroupID}`
  - **Description**: Retrieve details for a specific business group.
  - **Parameters**: `companyID`, `businessGroupID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/accounts/businessGroups/{businessGroupID}`
  - **Description**: Update a business group.
  - **Parameters**: `companyID`, `businessGroupID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/accounts/businessGroups/{businessGroupID}`
  - **Description**: Delete a business group.
  - **Parameters**: `companyID`, `businessGroupID`
  - **Response**: No Content

#### Accounts > chartOfAccounts

- [ ] `GET /api/v1.0/companies/{companyID}/setup/accounts/chartOfAccounts/`
  - **Description**: List all chart of accounts.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/accounts/chartOfAccounts/`
  - **Description**: Create a new chart of account.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/accounts/chartOfAccounts/{accountID}`
  - **Description**: Retrieve details for a specific chart of account.
  - **Parameters**: `companyID`, `accountID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/accounts/chartOfAccounts/{accountID}`
  - **Description**: Update a chart of account.
  - **Parameters**: `companyID`, `accountID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/accounts/chartOfAccounts/{accountID}`
  - **Description**: Delete a chart of account.
  - **Parameters**: `companyID`, `accountID`
  - **Response**: No Content

#### Accounts > costCenters

- [ ] `GET /api/v1.0/companies/{companyID}/setup/accounts/costCenters/`
  - **Description**: List all cost centers.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/accounts/costCenters/`
  - **Description**: Create a new cost center.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/accounts/costCenters/{costCenterID}`
  - **Description**: Retrieve details for a specific cost center.
  - **Parameters**: `companyID`, `costCenterID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/accounts/costCenters/{costCenterID}`
  - **Description**: Update a cost center.
  - **Parameters**: `companyID`, `costCenterID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/accounts/costCenters/{costCenterID}`
  - **Description**: Delete a cost center.
  - **Parameters**: `companyID`, `costCenterID`
  - **Response**: No Content

#### Accounts > paymentMethods

- [ ] `GET /api/v1.0/companies/{companyID}/setup/accounts/paymentMethods/`
  - **Description**: List all payment methods.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/accounts/paymentMethods/`
  - **Description**: Create a new payment method.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/accounts/paymentMethods/{paymentMethodID}`
  - **Description**: Retrieve details for a specific payment method.
  - **Parameters**: `companyID`, `paymentMethodID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/accounts/paymentMethods/{paymentMethodID}`
  - **Description**: Update a payment method.
  - **Parameters**: `companyID`, `paymentMethodID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/accounts/paymentMethods/{paymentMethodID}`
  - **Description**: Delete a payment method.
  - **Parameters**: `companyID`, `paymentMethodID`
  - **Response**: No Content

#### Accounts > paymentTerms

- [ ] `GET /api/v1.0/companies/{companyID}/setup/accounts/paymentTerms/`
  - **Description**: List all payment terms.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/accounts/paymentTerms/`
  - **Description**: Create a new payment term.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/accounts/paymentTerms/{paymentTermID}`
  - **Description**: Retrieve details for a specific payment term.
  - **Parameters**: `companyID`, `paymentTermID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/accounts/paymentTerms/{paymentTermID}`
  - **Description**: Update a payment term.
  - **Parameters**: `companyID`, `paymentTermID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/accounts/paymentTerms/{paymentTermID}`
  - **Description**: Delete a payment term.
  - **Parameters**: `companyID`, `paymentTermID`
  - **Response**: No Content

#### Accounts > taxCodes

- [ ] `GET /api/v1.0/companies/{companyID}/setup/accounts/taxCodes/`
  - **Description**: List all tax codes.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

#### Accounts > taxCodes > combines

- [ ] `GET /api/v1.0/companies/{companyID}/setup/accounts/taxCodes/combines/`
  - **Description**: List all combine tax codes.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/accounts/taxCodes/combines/`
  - **Description**: Create a new combine tax code.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/accounts/taxCodes/combines/{combineTaxCodeID}`
  - **Description**: Retrieve details for a specific combine tax code.
  - **Parameters**: `companyID`, `combineTaxCodeID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/accounts/taxCodes/combines/{combineTaxCodeID}`
  - **Description**: Update a combine tax code.
  - **Parameters**: `companyID`, `combineTaxCodeID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/accounts/taxCodes/combines/{combineTaxCodeID}`
  - **Description**: Delete a combine tax code.
  - **Parameters**: `companyID`, `combineTaxCodeID`
  - **Response**: No Content

#### Accounts > taxCodes > components

- [ ] `GET /api/v1.0/companies/{companyID}/setup/accounts/taxCodes/components/`
  - **Description**: List all component tax codes.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/accounts/taxCodes/components/`
  - **Description**: Create a new component tax code.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/accounts/taxCodes/components/{componentTaxCodeID}`
  - **Description**: Retrieve details for a specific component tax code.
  - **Parameters**: `companyID`, `componentTaxCodeID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/accounts/taxCodes/components/{componentTaxCodeID}`
  - **Description**: Update a component tax code.
  - **Parameters**: `companyID`, `componentTaxCodeID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/accounts/taxCodes/components/{componentTaxCodeID}`
  - **Description**: Delete a component tax code.
  - **Parameters**: `companyID`, `componentTaxCodeID`
  - **Response**: No Content

#### Accounts > taxCodes > singles

- [ ] `GET /api/v1.0/companies/{companyID}/setup/accounts/taxCodes/singles/`
  - **Description**: List all single tax codes.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/accounts/taxCodes/singles/`
  - **Description**: Create a new single tax code.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/accounts/taxCodes/singles/{singleTaxCodeID}`
  - **Description**: Retrieve details for a specific single tax code.
  - **Parameters**: `companyID`, `singleTaxCodeID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/accounts/taxCodes/singles/{singleTaxCodeID}`
  - **Description**: Update a single tax code.
  - **Parameters**: `companyID`, `singleTaxCodeID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/accounts/taxCodes/singles/{singleTaxCodeID}`
  - **Description**: Delete a single tax code.
  - **Parameters**: `companyID`, `singleTaxCodeID`
  - **Response**: No Content

#### Activities

- [ ] `GET /api/v1.0/companies/{companyID}/setup/activities/`
  - **Description**: List all activities.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/activities/`
  - **Description**: Create a new activity.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/activities/{activityID}`
  - **Description**: Retrieve details for a specific activity.
  - **Parameters**: `companyID`, `activityID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/activities/{activityID}`
  - **Description**: Update a activity.
  - **Parameters**: `companyID`, `activityID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/activities/{activityID}`
  - **Description**: Delete a activity.
  - **Parameters**: `companyID`, `activityID`
  - **Response**: No Content

#### ArchiveReasons > quotes

- [ ] `GET /api/v1.0/companies/{companyID}/setup/archiveReasons/quotes/`
  - **Description**: List all quote archive reasons.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/archiveReasons/quotes/`
  - **Description**: Create a new quote archive reason.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/archiveReasons/quotes/{archiveReasonID}`
  - **Description**: Retrieve details for a specific quote archive reason.
  - **Parameters**: `companyID`, `archiveReasonID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/archiveReasons/quotes/{archiveReasonID}`
  - **Description**: Update a quote archive reason.
  - **Parameters**: `companyID`, `archiveReasonID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/archiveReasons/quotes/{archiveReasonID}`
  - **Description**: Delete a quote archive reason.
  - **Parameters**: `companyID`, `archiveReasonID`
  - **Response**: No Content

#### AssetTypes

- [ ] `GET /api/v1.0/companies/{companyID}/setup/assetTypes/`
  - **Description**: List all asset types.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/assetTypes/`
  - **Description**: Create a new asset type.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}`
  - **Description**: Retrieve details for a specific asset type.
  - **Parameters**: `companyID`, `assetTypeID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}`
  - **Description**: Update a asset type.
  - **Parameters**: `companyID`, `assetTypeID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}`
  - **Description**: Delete a asset type.
  - **Parameters**: `companyID`, `assetTypeID`
  - **Response**: No Content

#### AssetTypes > attachments > files

- [ ] `GET /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/attachments/files/`
  - **Description**: List all asset type attachments.
  - **Parameters**: `companyID`, `assetTypeID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/attachments/files/`
  - **Description**: Create a new asset type attachment.
  - **Parameters**: `companyID`, `assetTypeID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific asset type attachment.
  - **Parameters**: `companyID`, `assetTypeID`, `fileID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/attachments/files/{fileID}`
  - **Description**: Update a asset type attachment.
  - **Parameters**: `companyID`, `assetTypeID`, `fileID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/attachments/files/{fileID}`
  - **Description**: Delete a asset type attachment.
  - **Parameters**: `companyID`, `assetTypeID`, `fileID`
  - **Response**: No Content

#### AssetTypes > attachments > folders

- [ ] `GET /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/attachments/folders/`
  - **Description**: List all asset type attachment folders.
  - **Parameters**: `companyID`, `assetTypeID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/attachments/folders/`
  - **Description**: Create a new asset type attachment folder.
  - **Parameters**: `companyID`, `assetTypeID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/attachments/folders/{folderID}`
  - **Description**: Retrieve details for a specific asset type attachment folder.
  - **Parameters**: `companyID`, `assetTypeID`, `folderID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/attachments/folders/{folderID}`
  - **Description**: Update a asset type attachment folder.
  - **Parameters**: `companyID`, `assetTypeID`, `folderID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/attachments/folders/{folderID}`
  - **Description**: Delete a asset type attachment folder.
  - **Parameters**: `companyID`, `assetTypeID`, `folderID`
  - **Response**: No Content

#### AssetTypes > customFields

- [ ] `GET /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/customFields/`
  - **Description**: List all asset type custom fields.
  - **Parameters**: `companyID`, `assetTypeID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/customFields/`
  - **Description**: Create a new asset type custom field.
  - **Parameters**: `companyID`, `assetTypeID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/customFields/{assetTypeCustomFieldID}`
  - **Description**: Retrieve details for a specific asset type custom field.
  - **Parameters**: `companyID`, `assetTypeID`, `assetTypeCustomFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/customFields/{assetTypeCustomFieldID}`
  - **Description**: Update a asset type custom field.
  - **Parameters**: `companyID`, `assetTypeID`, `assetTypeCustomFieldID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/customFields/{assetTypeCustomFieldID}`
  - **Description**: Delete a asset type custom field.
  - **Parameters**: `companyID`, `assetTypeID`, `assetTypeCustomFieldID`
  - **Response**: No Content

#### AssetTypes > serviceLevels

- [ ] `GET /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/serviceLevels/`
  - **Description**: List all asset type service levels.
  - **Parameters**: `companyID`, `assetTypeID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/serviceLevels/`
  - **Description**: Create a new asset type service level.
  - **Parameters**: `companyID`, `assetTypeID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/serviceLevels/{assetTypeServiceLevelID}`
  - **Description**: Retrieve details for a specific asset type service level.
  - **Parameters**: `companyID`, `assetTypeID`, `assetTypeServiceLevelID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/serviceLevels/{assetTypeServiceLevelID}`
  - **Description**: Update a asset type service level.
  - **Parameters**: `companyID`, `assetTypeID`, `assetTypeServiceLevelID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/serviceLevels/{assetTypeServiceLevelID}`
  - **Description**: Delete a asset type service level.
  - **Parameters**: `companyID`, `assetTypeID`, `assetTypeServiceLevelID`
  - **Response**: No Content

#### AssetTypes > serviceLevels > failurePoints

- [ ] `GET /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/serviceLevels/{assetTypeServiceLevelID}/failurePoints/`
  - **Description**: List all failure points.
  - **Parameters**: `companyID`, `assetTypeID`, `assetTypeServiceLevelID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/serviceLevels/{assetTypeServiceLevelID}/failurePoints/`
  - **Description**: Create a new failure point.
  - **Parameters**: `companyID`, `assetTypeID`, `assetTypeServiceLevelID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/serviceLevels/{assetTypeServiceLevelID}/failurePoints/{failurePointID}`
  - **Description**: Retrieve details for a specific failure point.
  - **Parameters**: `companyID`, `assetTypeID`, `assetTypeServiceLevelID`, `failurePointID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/serviceLevels/{assetTypeServiceLevelID}/failurePoints/{failurePointID}`
  - **Description**: Update a failure point.
  - **Parameters**: `companyID`, `assetTypeID`, `assetTypeServiceLevelID`, `failurePointID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/serviceLevels/{assetTypeServiceLevelID}/failurePoints/{failurePointID}`
  - **Description**: Delete a failure point.
  - **Parameters**: `companyID`, `assetTypeID`, `assetTypeServiceLevelID`, `failurePointID`
  - **Response**: No Content

#### AssetTypes > serviceLevels > failurePoints > recommendations

- [ ] `GET /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/serviceLevels/{assetTypeServiceLevelID}/failurePoints/{failurePointID}/recommendations/`
  - **Description**: List all recommendations.
  - **Parameters**: `companyID`, `assetTypeID`, `assetTypeServiceLevelID`, `failurePointID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/serviceLevels/{assetTypeServiceLevelID}/failurePoints/{failurePointID}/recommendations/`
  - **Description**: Create a new recommendation.
  - **Parameters**: `companyID`, `assetTypeID`, `assetTypeServiceLevelID`, `failurePointID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/serviceLevels/{assetTypeServiceLevelID}/failurePoints/{failurePointID}/recommendations/{recommendationID}`
  - **Description**: Retrieve details for a specific recommendation.
  - **Parameters**: `companyID`, `assetTypeID`, `assetTypeServiceLevelID`, `failurePointID`, `recommendationID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/serviceLevels/{assetTypeServiceLevelID}/failurePoints/{failurePointID}/recommendations/{recommendationID}`
  - **Description**: Update a recommendation.
  - **Parameters**: `companyID`, `assetTypeID`, `assetTypeServiceLevelID`, `failurePointID`, `recommendationID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/serviceLevels/{assetTypeServiceLevelID}/failurePoints/{failurePointID}/recommendations/{recommendationID}`
  - **Description**: Delete a recommendation.
  - **Parameters**: `companyID`, `assetTypeID`, `assetTypeServiceLevelID`, `failurePointID`, `recommendationID`
  - **Response**: No Content

#### AssetTypes > testReadings

- [ ] `GET /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/testReadings/`
  - **Description**: List all test readings.
  - **Parameters**: `companyID`, `assetTypeID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/testReadings/`
  - **Description**: Create a new test readings.
  - **Parameters**: `companyID`, `assetTypeID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/testReadings/{assetTypeTestReadingID}`
  - **Description**: Retrieve details for a specific test readings.
  - **Parameters**: `companyID`, `assetTypeID`, `assetTypeTestReadingID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/testReadings/{assetTypeTestReadingID}`
  - **Description**: Update a test readings.
  - **Parameters**: `companyID`, `assetTypeID`, `assetTypeTestReadingID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/assetTypes/{assetTypeID}/testReadings/{assetTypeTestReadingID}`
  - **Description**: Delete a test readings.
  - **Parameters**: `companyID`, `assetTypeID`, `assetTypeTestReadingID`
  - **Response**: No Content

#### Assets > serviceLevels

- [ ] `GET /api/v1.0/companies/{companyID}/setup/assets/serviceLevels/`
  - **Description**: List all service levels.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/assets/serviceLevels/`
  - **Description**: Create a new service level.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/assets/serviceLevels/{serviceLevelID}`
  - **Description**: Retrieve details for a specific service level.
  - **Parameters**: `companyID`, `serviceLevelID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/assets/serviceLevels/{serviceLevelID}`
  - **Description**: Update a service level.
  - **Parameters**: `companyID`, `serviceLevelID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/assets/serviceLevels/{serviceLevelID}`
  - **Description**: Delete a service level.
  - **Parameters**: `companyID`, `serviceLevelID`
  - **Response**: No Content

#### Commissions

- [ ] `GET /api/v1.0/companies/{companyID}/setup/commissions/`
  - **Description**: List all commissions.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?
  - **Response**: Array of object

#### Commissions > advanced

- [ ] `GET /api/v1.0/companies/{companyID}/setup/commissions/advanced/`
  - **Description**: List all advanced commissions.
  - **Parameters**: `companyID`, `commissionType`, `search`?, `columns`?, `pageSize`?, `page`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/commissions/advanced/`
  - **Description**: Create a new advanced commission.
  - **Parameters**: `companyID`, `commissionType`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/commissions/advanced/{commissionID}`
  - **Description**: Retrieve details for a specific advanced commission.
  - **Parameters**: `companyID`, `commissionType`, `commissionID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/commissions/advanced/{commissionID}`
  - **Description**: Update a advanced commission.
  - **Parameters**: `companyID`, `commissionType`, `commissionID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/commissions/advanced/{commissionID}`
  - **Description**: Delete a advanced commission.
  - **Parameters**: `companyID`, `commissionType`, `commissionID`
  - **Response**: No Content

#### Commissions > basic

- [ ] `GET /api/v1.0/companies/{companyID}/setup/commissions/basic/`
  - **Description**: List all basic commissions.
  - **Parameters**: `companyID`, `commissionType`, `search`?, `columns`?, `pageSize`?, `page`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/commissions/basic/`
  - **Description**: Create a new basic commission.
  - **Parameters**: `companyID`, `commissionType`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/commissions/basic/{commissionID}`
  - **Description**: Retrieve details for a specific basic commission.
  - **Parameters**: `companyID`, `commissionType`, `commissionID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/commissions/basic/{commissionID}`
  - **Description**: Update a basic commission.
  - **Parameters**: `companyID`, `commissionType`, `commissionID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/commissions/basic/{commissionID}`
  - **Description**: Delete a basic commission.
  - **Parameters**: `companyID`, `commissionType`, `commissionID`
  - **Response**: No Content

#### Currencies

- [ ] `GET /api/v1.0/companies/{companyID}/setup/currencies/`
  - **Description**: List all currencies.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/setup/currencies/{currencyID}`
  - **Description**: Retrieve details for a specific currency.
  - **Parameters**: `companyID`, `currencyID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/currencies/{currencyID}`
  - **Description**: Update a currency.
  - **Parameters**: `companyID`, `currencyID`
  - **Response**: No Content

#### CustomFields > catalogs

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/catalogs/`
  - **Description**: List all catalog item custom fields (setup).
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/customFields/catalogs/`
  - **Description**: Create a new catalog item custom field (setup).
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/catalogs/{customFieldID}`
  - **Description**: Retrieve details for a specific catalog item custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/customFields/catalogs/{customFieldID}`
  - **Description**: Update a catalog item custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/customFields/catalogs/{customFieldID}`
  - **Description**: Delete a catalog item custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

#### CustomFields > contacts

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/contacts/`
  - **Description**: List all contact custom fields (setup).
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/customFields/contacts/`
  - **Description**: Create a new contact custom field (setup).
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/contacts/{customFieldID}`
  - **Description**: Retrieve details for a specific contact custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/customFields/contacts/{customFieldID}`
  - **Description**: Update a contact custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/customFields/contacts/{customFieldID}`
  - **Description**: Delete a contact custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

#### CustomFields > contractorInvoices

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/contractorInvoices/`
  - **Description**: List all contractor invoice custom fields (setup).
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/customFields/contractorInvoices/`
  - **Description**: Create a new contractor invoice custom field (setup).
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/contractorInvoices/{contractorInvoiceID}`
  - **Description**: Retrieve details for a specific contractor invoice custom field (setup).
  - **Parameters**: `companyID`, `contractorInvoiceID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/customFields/contractorInvoices/{contractorInvoiceID}`
  - **Description**: Update a contractor invoice custom field (setup).
  - **Parameters**: `companyID`, `contractorInvoiceID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/customFields/contractorInvoices/{contractorInvoiceID}`
  - **Description**: Delete a contractor invoice custom field (setup).
  - **Parameters**: `companyID`, `contractorInvoiceID`
  - **Response**: No Content

#### CustomFields > contractorJobs

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/contractorJobs/`
  - **Description**: List all contractor job custom fields (setup).
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/customFields/contractorJobs/`
  - **Description**: Create a new contractor job custom field (setup).
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/contractorJobs/{contractorJobID}`
  - **Description**: Retrieve details for a specific contractor job custom field (setup).
  - **Parameters**: `companyID`, `contractorJobID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/customFields/contractorJobs/{contractorJobID}`
  - **Description**: Update a contractor job custom field (setup).
  - **Parameters**: `companyID`, `contractorJobID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/customFields/contractorJobs/{contractorJobID}`
  - **Description**: Delete a contractor job custom field (setup).
  - **Parameters**: `companyID`, `contractorJobID`
  - **Response**: No Content

#### CustomFields > contractors

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/contractors/`
  - **Description**: List all contractor custom fields (setup).
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/customFields/contractors/`
  - **Description**: Create a new contractor custom field (setup).
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/contractors/{customFieldID}`
  - **Description**: Retrieve details for a specific contractor custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/customFields/contractors/{customFieldID}`
  - **Description**: Update a contractor custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/customFields/contractors/{customFieldID}`
  - **Description**: Delete a contractor custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

#### CustomFields > customerContacts

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/customerContacts/`
  - **Description**: List all customer contact custom fields (setup).
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/customFields/customerContacts/`
  - **Description**: Create a new customer contact custom field (setup).
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/customerContacts/{customFieldID}`
  - **Description**: Retrieve details for a specific customer contact custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/customFields/customerContacts/{customFieldID}`
  - **Description**: Update a customer contact custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/customFields/customerContacts/{customFieldID}`
  - **Description**: Delete a customer contact custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

#### CustomFields > customerContracts

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/customerContracts/`
  - **Description**: List all customer contract custom fields (setup).
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/customFields/customerContracts/`
  - **Description**: Create a new customer contract custom field (setup).
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/customerContracts/{customFieldID}`
  - **Description**: Retrieve details for a specific customer contract custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/customFields/customerContracts/{customFieldID}`
  - **Description**: Update a customer contract custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/customFields/customerContracts/{customFieldID}`
  - **Description**: Delete a customer contract custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

#### CustomFields > customers

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/customers/`
  - **Description**: List all customer custom fields (setup).
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/customFields/customers/`
  - **Description**: Create a new customer custom field (setup).
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/customers/{customFieldID}`
  - **Description**: Retrieve details for a specific customer custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/customFields/customers/{customFieldID}`
  - **Description**: Update a customer custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/customFields/customers/{customFieldID}`
  - **Description**: Delete a customer custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

#### CustomFields > employees

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/employees/`
  - **Description**: List all employee custom fields (setup).
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/customFields/employees/`
  - **Description**: Create a new employee custom field (setup).
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/employees/{customFieldID}`
  - **Description**: Retrieve details for a specific employee custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/customFields/employees/{customFieldID}`
  - **Description**: Update a employee custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/customFields/employees/{customFieldID}`
  - **Description**: Delete a employee custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

#### CustomFields > invoices

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/invoices/`
  - **Description**: List all invoice custom fields (setup).
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/customFields/invoices/`
  - **Description**: Create a new invoice custom field (setup).
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/invoices/{customFieldID}`
  - **Description**: Retrieve details for a specific invoice custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/customFields/invoices/{customFieldID}`
  - **Description**: Update a invoice custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/customFields/invoices/{customFieldID}`
  - **Description**: Delete a invoice custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

#### CustomFields > prebuilds

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/prebuilds/`
  - **Description**: List all prebuild item custom fields (setup).
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/customFields/prebuilds/`
  - **Description**: Create a new prebuild item custom field (setup).
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/prebuilds/{customFieldID}`
  - **Description**: Retrieve details for a specific prebuild item custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/customFields/prebuilds/{customFieldID}`
  - **Description**: Update a prebuild item custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/customFields/prebuilds/{customFieldID}`
  - **Description**: Delete a prebuild item custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

#### CustomFields > projects

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/projects/`
  - **Description**: List all project custom fields (setup).
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/customFields/projects/`
  - **Description**: Create a new project custom field (setup).
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/projects/{customFieldID}`
  - **Description**: Retrieve details for a specific project custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/customFields/projects/{customFieldID}`
  - **Description**: Update a project custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/customFields/projects/{customFieldID}`
  - **Description**: Delete a project custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

#### CustomFields > siteContacts

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/siteContacts/`
  - **Description**: List all site contact custom fields (setup).
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/customFields/siteContacts/`
  - **Description**: Create a new site contact custom field (setup).
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/siteContacts/{customFieldID}`
  - **Description**: Retrieve details for a specific site contact custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/customFields/siteContacts/{customFieldID}`
  - **Description**: Update a site contact custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/customFields/siteContacts/{customFieldID}`
  - **Description**: Delete a site contact custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

#### CustomFields > sites

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/sites/`
  - **Description**: List all site custom fields (setup).
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/customFields/sites/`
  - **Description**: Create a new site custom field (setup).
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/sites/{customFieldID}`
  - **Description**: Retrieve details for a specific site custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/customFields/sites/{customFieldID}`
  - **Description**: Update a site custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/customFields/sites/{customFieldID}`
  - **Description**: Delete a site custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

#### CustomFields > tasks

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/tasks/`
  - **Description**: List all task custom fields (setup).
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/customFields/tasks/`
  - **Description**: Create a new task custom field (setup).
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/tasks/{customFieldID}`
  - **Description**: Retrieve details for a specific task custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/customFields/tasks/{customFieldID}`
  - **Description**: Update a task custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/customFields/tasks/{customFieldID}`
  - **Description**: Delete a task custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

#### CustomFields > vendorContacts

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/vendorContacts/`
  - **Description**: List all vendor contact custom fields (setup).
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/customFields/vendorContacts/`
  - **Description**: Create a new vendor contact custom field (setup).
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/vendorContacts/{customFieldID}`
  - **Description**: Retrieve details for a specific vendor contact custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/customFields/vendorContacts/{customFieldID}`
  - **Description**: Update a vendor contact custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/customFields/vendorContacts/{customFieldID}`
  - **Description**: Delete a vendor contact custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

#### CustomFields > vendorOrders

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/vendorOrders/`
  - **Description**: List all vendor order custom fields (setup).
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/customFields/vendorOrders/`
  - **Description**: Create a new vendor order custom field (setup).
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/vendorOrders/{customFieldID}`
  - **Description**: Retrieve details for a specific vendor order custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/customFields/vendorOrders/{customFieldID}`
  - **Description**: Update a vendor order custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/customFields/vendorOrders/{customFieldID}`
  - **Description**: Delete a vendor order custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

#### CustomFields > vendors

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/vendors/`
  - **Description**: List all vendor custom fields (setup).
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/customFields/vendors/`
  - **Description**: Create a new vendor custom field (setup).
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/vendors/{customFieldID}`
  - **Description**: Retrieve details for a specific vendor custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/customFields/vendors/{customFieldID}`
  - **Description**: Update a vendor custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/customFields/vendors/{customFieldID}`
  - **Description**: Delete a vendor custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

#### CustomFields > workOrders

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/workOrders/`
  - **Description**: List all work order custom fields (setup).
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/customFields/workOrders/`
  - **Description**: Create a new work order custom field (setup).
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customFields/workOrders/{customFieldID}`
  - **Description**: Retrieve details for a specific work order custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/customFields/workOrders/{customFieldID}`
  - **Description**: Update a work order custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/customFields/workOrders/{customFieldID}`
  - **Description**: Delete a work order custom field (setup).
  - **Parameters**: `companyID`, `customFieldID`
  - **Response**: No Content

#### CustomerGroups

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customerGroups/`
  - **Description**: List all customer groups.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/customerGroups/`
  - **Description**: Create a new customer group.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customerGroups/{customerGroupID}`
  - **Description**: Retrieve details for a specific customer group.
  - **Parameters**: `companyID`, `customerGroupID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/customerGroups/{customerGroupID}`
  - **Description**: Update a customer group.
  - **Parameters**: `companyID`, `customerGroupID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/customerGroups/{customerGroupID}`
  - **Description**: Delete a customer group.
  - **Parameters**: `companyID`, `customerGroupID`
  - **Response**: No Content

#### CustomerProfiles

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customerProfiles/`
  - **Description**: List all customer profiles.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/customerProfiles/`
  - **Description**: Create a new customer profile.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/customerProfiles/{customerProfileID}`
  - **Description**: Retrieve details for a specific customer profile.
  - **Parameters**: `companyID`, `customerProfileID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/customerProfiles/{customerProfileID}`
  - **Description**: Update a customer profile.
  - **Parameters**: `companyID`, `customerProfileID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/customerProfiles/{customerProfileID}`
  - **Description**: Delete a customer profile.
  - **Parameters**: `companyID`, `customerProfileID`
  - **Response**: No Content

#### Defaults

- [ ] `GET /api/v1.0/companies/{companyID}/setup/defaults/`
  - **Description**: Retrieve details for a specific company default settings.
  - **Parameters**: `columns`?
  - **Response**: object

#### Labor > fitTimes

- [ ] `GET /api/v1.0/companies/{companyID}/setup/labor/fitTimes/`
  - **Description**: List all fit times.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/labor/fitTimes/`
  - **Description**: Create a new fit time.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/labor/fitTimes/{fitTimeID}`
  - **Description**: Retrieve details for a specific fit time.
  - **Parameters**: `companyID`, `fitTimeID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/labor/fitTimes/{fitTimeID}`
  - **Description**: Update a fit time.
  - **Parameters**: `companyID`, `fitTimeID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/labor/fitTimes/{fitTimeID}`
  - **Description**: Delete a fit time.
  - **Parameters**: `companyID`, `fitTimeID`
  - **Response**: No Content

#### Labor > laborRates

- [ ] `GET /api/v1.0/companies/{companyID}/setup/labor/laborRates/`
  - **Description**: List all labor rates.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/labor/laborRates/`
  - **Description**: Create a new labor rate.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/labor/laborRates/{laborRateID}`
  - **Description**: Retrieve details for a specific labor rate.
  - **Parameters**: `companyID`, `laborRateID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/labor/laborRates/{laborRateID}`
  - **Description**: Update a labor rate.
  - **Parameters**: `companyID`, `laborRateID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/labor/laborRates/{laborRateID}`
  - **Description**: Delete a labor rate.
  - **Parameters**: `companyID`, `laborRateID`
  - **Response**: No Content

#### Labor > laborRates > overhead

- [ ] `GET /api/v1.0/companies/{companyID}/setup/labor/laborRates/overhead/`
  - **Description**: Retrieve details for a specific labor rate overhead.
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/labor/laborRates/overhead/`
  - **Description**: Update a labor rate overhead.
  - **Response**: No Content

#### Labor > plantRates

- [ ] `GET /api/v1.0/companies/{companyID}/setup/labor/plantRates/`
  - **Description**: List all plant rates.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/setup/labor/plantRates/{plantRateID}`
  - **Description**: Retrieve details for a specific plant rate.
  - **Parameters**: `companyID`, `plantRateID`, `columns`?
  - **Response**: object

#### Labor > scheduleRates

- [ ] `GET /api/v1.0/companies/{companyID}/setup/labor/scheduleRates/`
  - **Description**: List all schedule rates.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/labor/scheduleRates/`
  - **Description**: Create a new schedule rate.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/labor/scheduleRates/{scheduleRateID}`
  - **Description**: Retrieve details for a specific schedule rate.
  - **Parameters**: `companyID`, `scheduleRateID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/labor/scheduleRates/{scheduleRateID}`
  - **Description**: Update a schedule rate.
  - **Parameters**: `companyID`, `scheduleRateID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/labor/scheduleRates/{scheduleRateID}`
  - **Description**: Delete a schedule rate.
  - **Parameters**: `companyID`, `scheduleRateID`
  - **Response**: No Content

#### Labor > serviceFees

- [ ] `GET /api/v1.0/companies/{companyID}/setup/labor/serviceFees/`
  - **Description**: List all service fees.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/setup/labor/serviceFees/{serviceFeeID}`
  - **Description**: Retrieve details for a specific service fee.
  - **Parameters**: `companyID`, `serviceFeeID`, `columns`?
  - **Response**: object

#### Materials > pricingTiers

- [ ] `GET /api/v1.0/companies/{companyID}/setup/materials/pricingTiers/`
  - **Description**: List all pricing tiers.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/materials/pricingTiers/`
  - **Description**: Create a new pricing tier.
  - **Parameters**: `companyID`, `makeDefault`?, `updateCustomers`?
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/materials/pricingTiers/{pricingTierID}`
  - **Description**: Retrieve details for a specific pricing tier.
  - **Parameters**: `companyID`, `pricingTierID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/materials/pricingTiers/{pricingTierID}`
  - **Description**: Update a pricing tier.
  - **Parameters**: `companyID`, `pricingTierID`, `makeDefault`?, `updateCustomers`?
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/materials/pricingTiers/{pricingTierID}`
  - **Description**: Delete a pricing tier.
  - **Parameters**: `companyID`, `pricingTierID`
  - **Response**: No Content

#### Materials > purchasingStages

- [ ] `GET /api/v1.0/companies/{companyID}/setup/materials/purchasingStages/`
  - **Description**: List all purchasing stages.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/materials/purchasingStages/`
  - **Description**: Create a new purchasing stage.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/materials/purchasingStages/{purchasingStageID}`
  - **Description**: Retrieve details for a specific purchasing stage.
  - **Parameters**: `companyID`, `purchasingStageID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/materials/purchasingStages/{purchasingStageID}`
  - **Description**: Update a purchasing stage.
  - **Parameters**: `companyID`, `purchasingStageID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/materials/purchasingStages/{purchasingStageID}`
  - **Description**: Delete a purchasing stage.
  - **Parameters**: `companyID`, `purchasingStageID`
  - **Response**: No Content

#### Materials > stockTakeReasons

- [ ] `GET /api/v1.0/companies/{companyID}/setup/materials/stockTakeReasons/`
  - **Description**: List all stock take reasons.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/materials/stockTakeReasons/`
  - **Description**: Create a new stock take reason.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/materials/stockTakeReasons/{optionID}`
  - **Description**: Retrieve details for a specific stock take reason.
  - **Parameters**: `companyID`, `optionID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/materials/stockTakeReasons/{optionID}`
  - **Description**: Update a stock take reason.
  - **Parameters**: `companyID`, `optionID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/materials/stockTakeReasons/{optionID}`
  - **Description**: Delete a stock take reason.
  - **Parameters**: `companyID`, `optionID`
  - **Response**: No Content

#### Materials > stockTransferReasons

- [ ] `GET /api/v1.0/companies/{companyID}/setup/materials/stockTransferReasons/`
  - **Description**: List all stock transfer reasons.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/materials/stockTransferReasons/`
  - **Description**: Create a new stock transfer reason.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/materials/stockTransferReasons/{optionID}`
  - **Description**: Retrieve details for a specific stock transfer reason.
  - **Parameters**: `companyID`, `optionID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/materials/stockTransferReasons/{optionID}`
  - **Description**: Update a stock transfer reason.
  - **Parameters**: `companyID`, `optionID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/materials/stockTransferReasons/{optionID}`
  - **Description**: Delete a stock transfer reason.
  - **Parameters**: `companyID`, `optionID`
  - **Response**: No Content

#### Materials > uoms

- [ ] `GET /api/v1.0/companies/{companyID}/setup/materials/uoms/`
  - **Description**: List all units of measurement.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/materials/uoms/`
  - **Description**: Create a new unit of measurement.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/materials/uoms/{uomID}`
  - **Description**: Retrieve details for a specific unit of measurement.
  - **Parameters**: `companyID`, `uomID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/materials/uoms/{uomID}`
  - **Description**: Update a unit of measurement.
  - **Parameters**: `companyID`, `uomID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/materials/uoms/{uomID}`
  - **Description**: Delete a unit of measurement.
  - **Parameters**: `companyID`, `uomID`
  - **Response**: No Content

#### ResponseTimes

- [ ] `GET /api/v1.0/companies/{companyID}/setup/responseTimes/`
  - **Description**: List all response times.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/responseTimes/`
  - **Description**: Create a new response time.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/responseTimes/{responseTimeID}`
  - **Description**: Retrieve details for a specific response time.
  - **Parameters**: `companyID`, `responseTimeID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/responseTimes/{responseTimeID}`
  - **Description**: Update a response time.
  - **Parameters**: `companyID`, `responseTimeID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/responseTimes/{responseTimeID}`
  - **Description**: Delete a response time.
  - **Parameters**: `companyID`, `responseTimeID`
  - **Response**: No Content

#### SecurityGroups

- [ ] `GET /api/v1.0/companies/{companyID}/setup/securityGroups/`
  - **Description**: List all security groups.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/setup/securityGroups/{securityGroupID}`
  - **Description**: Retrieve details for a specific security group.
  - **Parameters**: `companyID`, `securityGroupID`, `columns`?
  - **Response**: object

#### StatusCodes > customerInvoices

- [ ] `GET /api/v1.0/companies/{companyID}/setup/statusCodes/customerInvoices/`
  - **Description**: List all customer invoice status codes.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/statusCodes/customerInvoices/`
  - **Description**: Create a new customer invoice status code.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/statusCodes/customerInvoices/{statusCodeID}`
  - **Description**: Retrieve details for a specific customer invoice status code.
  - **Parameters**: `companyID`, `statusCodeID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/statusCodes/customerInvoices/{statusCodeID}`
  - **Description**: Update a customer invoice status code.
  - **Parameters**: `companyID`, `statusCodeID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/statusCodes/customerInvoices/{statusCodeID}`
  - **Description**: Delete a customer invoice status code.
  - **Parameters**: `companyID`, `statusCodeID`
  - **Response**: No Content

#### StatusCodes > projects

- [ ] `GET /api/v1.0/companies/{companyID}/setup/statusCodes/projects/`
  - **Description**: List all project status codes.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/statusCodes/projects/`
  - **Description**: Create a new project status code.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/statusCodes/projects/{statusCodeID}`
  - **Description**: Retrieve details for a specific project status code.
  - **Parameters**: `companyID`, `statusCodeID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/statusCodes/projects/{statusCodeID}`
  - **Description**: Update a project status code.
  - **Parameters**: `companyID`, `statusCodeID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/statusCodes/projects/{statusCodeID}`
  - **Description**: Delete a project status code.
  - **Parameters**: `companyID`, `statusCodeID`
  - **Response**: No Content

#### StatusCodes > vendorOrders

- [ ] `GET /api/v1.0/companies/{companyID}/setup/statusCodes/vendorOrders/`
  - **Description**: List all vendor order status codes.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/statusCodes/vendorOrders/`
  - **Description**: Create a new vendor order status code.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/statusCodes/vendorOrders/{statusCodeID}`
  - **Description**: Retrieve details for a specific vendor order status code.
  - **Parameters**: `companyID`, `statusCodeID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/statusCodes/vendorOrders/{statusCodeID}`
  - **Description**: Update a vendor order status code.
  - **Parameters**: `companyID`, `statusCodeID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/statusCodes/vendorOrders/{statusCodeID}`
  - **Description**: Delete a vendor order status code.
  - **Parameters**: `companyID`, `statusCodeID`
  - **Response**: No Content

#### Tags > customers

- [ ] `GET /api/v1.0/companies/{companyID}/setup/tags/customers/`
  - **Description**: List all customer tags.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/tags/customers/`
  - **Description**: Create a new customer tag.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/tags/customers/{customerTagID}`
  - **Description**: Retrieve details for a specific customer tag.
  - **Parameters**: `companyID`, `customerTagID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/tags/customers/{customerTagID}`
  - **Description**: Update a customer tag.
  - **Parameters**: `companyID`, `customerTagID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/tags/customers/{customerTagID}`
  - **Description**: Delete a customer tag.
  - **Parameters**: `companyID`, `customerTagID`
  - **Response**: No Content

#### Tags > projects

- [ ] `GET /api/v1.0/companies/{companyID}/setup/tags/projects/`
  - **Description**: List all project tags.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/tags/projects/`
  - **Description**: Create a new project tag.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/tags/projects/{projectTagID}`
  - **Description**: Retrieve details for a specific project tag.
  - **Parameters**: `companyID`, `projectTagID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/tags/projects/{projectTagID}`
  - **Description**: Update a project tag.
  - **Parameters**: `companyID`, `projectTagID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/tags/projects/{projectTagID}`
  - **Description**: Delete a project tag.
  - **Parameters**: `companyID`, `projectTagID`
  - **Response**: No Content

#### Tasks > categories

- [ ] `GET /api/v1.0/companies/{companyID}/setup/tasks/categories/`
  - **Description**: List all task categories.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/tasks/categories/`
  - **Description**: Create a new task category.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/tasks/categories/{taskCategoryID}`
  - **Description**: Retrieve details for a specific task category.
  - **Parameters**: `companyID`, `taskCategoryID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/tasks/categories/{taskCategoryID}`
  - **Description**: Update a task category.
  - **Parameters**: `companyID`, `taskCategoryID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/tasks/categories/{taskCategoryID}`
  - **Description**: Delete a task category.
  - **Parameters**: `companyID`, `taskCategoryID`
  - **Response**: No Content

#### Teams

- [ ] `GET /api/v1.0/companies/{companyID}/setup/teams/`
  - **Description**: List all teams.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/setup/teams/{teamID}`
  - **Description**: Retrieve details for a specific team.
  - **Parameters**: `companyID`, `teamID`, `columns`?
  - **Response**: object

#### Webhooks

- [ ] `GET /api/v1.0/companies/{companyID}/setup/webhooks/`
  - **Description**: List all webhook subscriptions.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/webhooks/`
  - **Description**: Create a new webhook subscription.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/webhooks/{webhookID}`
  - **Description**: Retrieve details for a specific webhook subscription.
  - **Parameters**: `companyID`, `webhookID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/webhooks/{webhookID}`
  - **Description**: Update a webhook subscription.
  - **Parameters**: `companyID`, `webhookID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/webhooks/{webhookID}`
  - **Description**: Delete a webhook subscription.
  - **Parameters**: `companyID`, `webhookID`
  - **Response**: No Content

#### Zones

- [ ] `GET /api/v1.0/companies/{companyID}/setup/zones/`
  - **Description**: List all zones.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/setup/zones/`
  - **Description**: Create a new zone.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/setup/zones/{zoneID}`
  - **Description**: Retrieve details for a specific zone.
  - **Parameters**: `companyID`, `zoneID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/setup/zones/{zoneID}`
  - **Description**: Update a zone.
  - **Parameters**: `companyID`, `zoneID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/setup/zones/{zoneID}`
  - **Description**: Delete a zone.
  - **Parameters**: `companyID`, `zoneID`
  - **Response**: No Content

### Nested: Sites

- [ ] `GET /api/v1.0/companies/{companyID}/sites/`
  - **Description**: List all sites.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/sites/`
  - **Description**: Create a new site.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}`
  - **Description**: Retrieve details for a specific site.
  - **Parameters**: `companyID`, `siteID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/sites/{siteID}`
  - **Description**: Update a site.
  - **Parameters**: `companyID`, `siteID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/sites/{siteID}`
  - **Description**: Delete a site.
  - **Parameters**: `companyID`, `siteID`
  - **Response**: No Content

#### Assets

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/assets/`
  - **Description**: List all customer assets.
  - **Parameters**: `companyID`, `siteID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/sites/{siteID}/assets/`
  - **Description**: Create a new customer asset.
  - **Parameters**: `companyID`, `siteID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/assets/{assetID}`
  - **Description**: Retrieve details for a specific customer asset.
  - **Parameters**: `companyID`, `siteID`, `assetID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/sites/{siteID}/assets/{assetID}`
  - **Description**: Update a customer asset.
  - **Parameters**: `companyID`, `siteID`, `assetID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/sites/{siteID}/assets/{assetID}`
  - **Description**: Delete a customer asset.
  - **Parameters**: `companyID`, `siteID`, `assetID`
  - **Response**: No Content

#### Assets > attachments > files

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/assets/{assetID}/attachments/files/`
  - **Description**: List all asset attachments.
  - **Parameters**: `companyID`, `siteID`, `assetID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/sites/{siteID}/assets/{assetID}/attachments/files/`
  - **Description**: Create a new asset attachment.
  - **Parameters**: `companyID`, `siteID`, `assetID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/assets/{assetID}/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific asset attachment.
  - **Parameters**: `companyID`, `siteID`, `assetID`, `fileID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/sites/{siteID}/assets/{assetID}/attachments/files/{fileID}`
  - **Description**: Update a asset attachment.
  - **Parameters**: `companyID`, `siteID`, `assetID`, `fileID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/sites/{siteID}/assets/{assetID}/attachments/files/{fileID}`
  - **Description**: Delete a asset attachment.
  - **Parameters**: `companyID`, `siteID`, `assetID`, `fileID`
  - **Response**: No Content

#### Assets > attachments > folders

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/assets/{assetID}/attachments/folders/`
  - **Description**: List all asset attachment folders.
  - **Parameters**: `companyID`, `siteID`, `assetID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/sites/{siteID}/assets/{assetID}/attachments/folders/`
  - **Description**: Create a new asset attachment folder.
  - **Parameters**: `companyID`, `siteID`, `assetID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/assets/{assetID}/attachments/folders/{folderID}`
  - **Description**: Retrieve details for a specific asset attachment folder.
  - **Parameters**: `companyID`, `siteID`, `assetID`, `folderID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/sites/{siteID}/assets/{assetID}/attachments/folders/{folderID}`
  - **Description**: Update a asset attachment folder.
  - **Parameters**: `companyID`, `siteID`, `assetID`, `folderID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/sites/{siteID}/assets/{assetID}/attachments/folders/{folderID}`
  - **Description**: Delete a asset attachment folder.
  - **Parameters**: `companyID`, `siteID`, `assetID`, `folderID`
  - **Response**: No Content

#### Assets > customFields

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/assets/{assetID}/customFields/`
  - **Description**: List all asset custom fields.
  - **Parameters**: `companyID`, `siteID`, `assetID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/assets/{assetID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific asset custom field.
  - **Parameters**: `companyID`, `siteID`, `assetID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/sites/{siteID}/assets/{assetID}/customFields/{customFieldID}`
  - **Description**: Update a asset custom field.
  - **Parameters**: `companyID`, `siteID`, `assetID`, `customFieldID`
  - **Response**: No Content

#### Assets > serviceLevels

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/assets/{assetID}/serviceLevels/`
  - **Description**: List all asset service levels.
  - **Parameters**: `companyID`, `siteID`, `assetID`, `search`?, `columns`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/sites/{siteID}/assets/{assetID}/serviceLevels/`
  - **Description**: Create a new asset service level.
  - **Parameters**: `companyID`, `siteID`, `assetID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/assets/{assetID}/serviceLevels/{serviceLevelID}`
  - **Description**: Retrieve details for a specific asset service level.
  - **Parameters**: `companyID`, `siteID`, `assetID`, `serviceLevelID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/sites/{siteID}/assets/{assetID}/serviceLevels/{serviceLevelID}`
  - **Description**: Update a asset service level.
  - **Parameters**: `companyID`, `siteID`, `assetID`, `serviceLevelID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/sites/{siteID}/assets/{assetID}/serviceLevels/{serviceLevelID}`
  - **Description**: Delete a asset service level.
  - **Parameters**: `companyID`, `siteID`, `assetID`, `serviceLevelID`
  - **Response**: No Content

#### Assets > testHistory

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/assets/{assetID}/testHistory/`
  - **Description**: List all asset test histories.
  - **Parameters**: `companyID`, `siteID`, `assetID`, `pageSize`?, `page`?, `limit`?
  - **Response**: Array of object

#### Assets > transfer

- [ ] `POST /api/v1.0/companies/{companyID}/sites/{siteID}/assets/{assetID}/transfer/`
  - **Description**: Create a new asset transfer.
  - **Parameters**: `companyID`, `siteID`, `assetID`
  - **Response**: Unknown

#### Attachments > files

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/attachments/files/`
  - **Description**: List all site attachments.
  - **Parameters**: `companyID`, `siteID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/sites/{siteID}/attachments/files/`
  - **Description**: Create a new site attachment.
  - **Parameters**: `companyID`, `siteID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific site attachment.
  - **Parameters**: `companyID`, `siteID`, `fileID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/sites/{siteID}/attachments/files/{fileID}`
  - **Description**: Update a site attachment.
  - **Parameters**: `companyID`, `siteID`, `fileID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/sites/{siteID}/attachments/files/{fileID}`
  - **Description**: Delete a site attachment.
  - **Parameters**: `companyID`, `siteID`, `fileID`
  - **Response**: No Content

#### Attachments > folders

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/attachments/folders/`
  - **Description**: List all site attachment folders.
  - **Parameters**: `companyID`, `siteID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/sites/{siteID}/attachments/folders/`
  - **Description**: Create a new site attachment folder.
  - **Parameters**: `companyID`, `siteID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/attachments/folders/{folderID}`
  - **Description**: Retrieve details for a specific site attachment folder.
  - **Parameters**: `companyID`, `siteID`, `folderID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/sites/{siteID}/attachments/folders/{folderID}`
  - **Description**: Update a site attachment folder.
  - **Parameters**: `companyID`, `siteID`, `folderID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/sites/{siteID}/attachments/folders/{folderID}`
  - **Description**: Delete a site attachment folder.
  - **Parameters**: `companyID`, `siteID`, `folderID`
  - **Response**: No Content

#### Contacts

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/contacts/`
  - **Description**: List all site contacts.
  - **Parameters**: `companyID`, `siteID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/sites/{siteID}/contacts/`
  - **Description**: Create a new site contact.
  - **Parameters**: `companyID`, `siteID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/contacts/{contactID}`
  - **Description**: Retrieve details for a specific site contact.
  - **Parameters**: `companyID`, `siteID`, `contactID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/sites/{siteID}/contacts/{contactID}`
  - **Description**: Update a site contact.
  - **Parameters**: `companyID`, `siteID`, `contactID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/sites/{siteID}/contacts/{contactID}`
  - **Description**: Delete a site contact.
  - **Parameters**: `companyID`, `siteID`, `contactID`
  - **Response**: No Content

#### Contacts > customFields

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/contacts/{contactID}/customFields/`
  - **Description**: List all site contact custom fields.
  - **Parameters**: `companyID`, `siteID`, `contactID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/contacts/{contactID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific site contact custom field.
  - **Parameters**: `companyID`, `siteID`, `contactID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/sites/{siteID}/contacts/{contactID}/customFields/{customFieldID}`
  - **Description**: Update a site contact custom field.
  - **Parameters**: `companyID`, `siteID`, `contactID`, `customFieldID`
  - **Response**: No Content

#### CustomFields

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/customFields/`
  - **Description**: List all site custom fields.
  - **Parameters**: `companyID`, `siteID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific site custom field.
  - **Parameters**: `companyID`, `siteID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/sites/{siteID}/customFields/{customFieldID}`
  - **Description**: Update a site custom field.
  - **Parameters**: `companyID`, `siteID`, `customFieldID`
  - **Response**: No Content

#### LaborRates

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/laborRates/`
  - **Description**: List all site labor rates.
  - **Parameters**: `companyID`, `siteID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/sites/{siteID}/laborRates/`
  - **Description**: Create a new site labor rate.
  - **Parameters**: `companyID`, `siteID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/laborRates/{laborRateID}`
  - **Description**: Retrieve details for a specific site labor rate.
  - **Parameters**: `companyID`, `siteID`, `laborRateID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/sites/{siteID}/laborRates/{laborRateID}`
  - **Description**: Update a site labor rate.
  - **Parameters**: `companyID`, `siteID`, `laborRateID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/sites/{siteID}/laborRates/{laborRateID}`
  - **Description**: Delete a site labor rate.
  - **Parameters**: `companyID`, `siteID`, `laborRateID`
  - **Response**: No Content

#### PreferredTechnicians

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/preferredTechnicians/`
  - **Description**: List all site preferred technicians.
  - **Parameters**: `companyID`, `siteID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/sites/{siteID}/preferredTechnicians/`
  - **Description**: Create a new site preferred technician.
  - **Parameters**: `companyID`, `siteID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/sites/{siteID}/preferredTechnicians/{preferredTechnicianID}`
  - **Description**: Retrieve details for a specific site preferred technician.
  - **Parameters**: `companyID`, `siteID`, `preferredTechnicianID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/sites/{siteID}/preferredTechnicians/{preferredTechnicianID}`
  - **Description**: Update a site preferred technician.
  - **Parameters**: `companyID`, `siteID`, `preferredTechnicianID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/sites/{siteID}/preferredTechnicians/{preferredTechnicianID}`
  - **Description**: Delete a site preferred technician.
  - **Parameters**: `companyID`, `siteID`, `preferredTechnicianID`
  - **Response**: No Content

### Nested: Staff

- [ ] `GET /api/v1.0/companies/{companyID}/staff/`
  - **Description**: List all staff.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/staff/{staffID}`
  - **Description**: Retrieve details for a specific staff.
  - **Parameters**: `companyID`, `staffID`, `columns`?
  - **Response**: object

### Nested: StockAllocations

- [ ] `GET /api/v1.0/companies/{companyID}/stockAllocations/`
  - **Description**: List all stock allocations.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/stockAllocations/{stockAllocationID}`
  - **Description**: Retrieve details for a specific stock allocation.
  - **Parameters**: `companyID`, `stockAllocationID`, `columns`?
  - **Response**: object

### Nested: StockTakes

- [ ] `GET /api/v1.0/companies/{companyID}/stockTakes/`
  - **Description**: List all stock takes.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/stockTakes/`
  - **Description**: Create a new stock take.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/stockTakes/{stockTakeID}`
  - **Description**: Retrieve details for a specific stock take.
  - **Parameters**: `companyID`, `stockTakeID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/stockTakes/{stockTakeID}`
  - **Description**: Update a stock take.
  - **Parameters**: `companyID`, `stockTakeID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/stockTakes/{stockTakeID}`
  - **Description**: Delete a stock take.
  - **Parameters**: `companyID`, `stockTakeID`
  - **Response**: No Content

### Nested: StockTransfer

- [ ] `POST /api/v1.0/companies/{companyID}/stockTransfer/`
  - **Description**: Create a new stock transfer.
  - **Parameters**: `companyID`
  - **Response**: Unknown

### Nested: StorageDevices

- [ ] `GET /api/v1.0/companies/{companyID}/storageDevices/`
  - **Description**: List all storage devices.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/storageDevices/`
  - **Description**: Create a new storage device.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/storageDevices/{storageDeviceID}`
  - **Description**: Retrieve details for a specific storage device.
  - **Parameters**: `companyID`, `storageDeviceID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/storageDevices/{storageDeviceID}`
  - **Description**: Update a storage device.
  - **Parameters**: `companyID`, `storageDeviceID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/storageDevices/{storageDeviceID}`
  - **Description**: Delete a storage device.
  - **Parameters**: `companyID`, `storageDeviceID`
  - **Response**: No Content

#### Stock

- [ ] `GET /api/v1.0/companies/{companyID}/storageDevices/{storageDeviceID}/stock/`
  - **Description**: List all stock items.
  - **Parameters**: `companyID`, `storageDeviceID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/storageDevices/{storageDeviceID}/stock/{catalogID}`
  - **Description**: Retrieve details for a specific stock item.
  - **Parameters**: `companyID`, `storageDeviceID`, `catalogID`, `columns`?
  - **Response**: object

### Nested: TakeOffTemplateGroups

- [ ] `GET /api/v1.0/companies/{companyID}/takeOffTemplateGroups/`
  - **Description**: List all take off template groups.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/takeOffTemplateGroups/`
  - **Description**: Create a new take off template group.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/takeOffTemplateGroups/{groupID}`
  - **Description**: Retrieve details for a specific take off template group.
  - **Parameters**: `companyID`, `groupID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/takeOffTemplateGroups/{groupID}`
  - **Description**: Update a take off template group.
  - **Parameters**: `companyID`, `groupID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/takeOffTemplateGroups/{groupID}`
  - **Description**: Delete a take off template group.
  - **Parameters**: `companyID`, `groupID`
  - **Response**: No Content

#### SubGroups

- [ ] `GET /api/v1.0/companies/{companyID}/takeOffTemplateGroups/{groupID}/subGroups/`
  - **Description**: List all take off template sub groups.
  - **Parameters**: `companyID`, `groupID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/takeOffTemplateGroups/{groupID}/subGroups/`
  - **Description**: Create a new take off template sub group.
  - **Parameters**: `companyID`, `groupID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/takeOffTemplateGroups/{groupID}/subGroups/{subGroupID}`
  - **Description**: Retrieve details for a specific take off template sub group.
  - **Parameters**: `companyID`, `groupID`, `subGroupID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/takeOffTemplateGroups/{groupID}/subGroups/{subGroupID}`
  - **Description**: Update a take off template sub group.
  - **Parameters**: `companyID`, `groupID`, `subGroupID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/takeOffTemplateGroups/{groupID}/subGroups/{subGroupID}`
  - **Description**: Delete a take off template sub group.
  - **Parameters**: `companyID`, `groupID`, `subGroupID`
  - **Response**: No Content

### Nested: TakeOffTemplates

- [ ] `GET /api/v1.0/companies/{companyID}/takeOffTemplates/`
  - **Description**: List all take off templates.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/takeOffTemplates/`
  - **Description**: Create a new take off template.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}`
  - **Description**: Retrieve details for a specific take off template.
  - **Parameters**: `companyID`, `takeOffTemplateID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}`
  - **Description**: Update a take off template.
  - **Parameters**: `companyID`, `takeOffTemplateID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}`
  - **Description**: Delete a take off template.
  - **Parameters**: `companyID`, `takeOffTemplateID`
  - **Response**: No Content

#### Catalogs

- [ ] `GET /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/catalogs/`
  - **Description**: List all take off template catalogs.
  - **Parameters**: `companyID`, `takeOffTemplateID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/catalogs/`
  - **Description**: Create a new take off template catalog.
  - **Parameters**: `companyID`, `takeOffTemplateID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/catalogs/{catalogID}`
  - **Description**: Retrieve details for a specific take off template catalog.
  - **Parameters**: `companyID`, `takeOffTemplateID`, `catalogID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/catalogs/{catalogID}`
  - **Description**: Update a take off template catalog.
  - **Parameters**: `companyID`, `takeOffTemplateID`, `catalogID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/catalogs/{catalogID}`
  - **Description**: Delete a take off template catalog.
  - **Parameters**: `companyID`, `takeOffTemplateID`, `catalogID`
  - **Response**: No Content

#### Labor

- [ ] `GET /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/labor/`
  - **Description**: List all take off template labors.
  - **Parameters**: `companyID`, `takeOffTemplateID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/labor/`
  - **Description**: Create a new take off template labor.
  - **Parameters**: `companyID`, `takeOffTemplateID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/labor/{laborID}`
  - **Description**: Retrieve details for a specific take off template labor.
  - **Parameters**: `companyID`, `takeOffTemplateID`, `laborID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/labor/{laborID}`
  - **Description**: Update a take off template labor.
  - **Parameters**: `companyID`, `takeOffTemplateID`, `laborID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/labor/{laborID}`
  - **Description**: Delete a take off template labor.
  - **Parameters**: `companyID`, `takeOffTemplateID`, `laborID`
  - **Response**: No Content

#### Lock

- [ ] `POST /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/lock/`
  - **Description**: Create a new take off template lock.
  - **Parameters**: `companyID`, `takeOffTemplateID`
  - **Response**: Unknown

- [ ] `DELETE /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/lock/`
  - **Description**: Delete a take off template lock.
  - **Response**: No Content

#### OneOffs

- [ ] `GET /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/oneOffs/`
  - **Description**: List all take off template one-offs.
  - **Parameters**: `companyID`, `takeOffTemplateID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/oneOffs/`
  - **Description**: Create a new take off template one-off.
  - **Parameters**: `companyID`, `takeOffTemplateID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/oneOffs/{oneOffsID}`
  - **Description**: Retrieve details for a specific take off template one-off.
  - **Parameters**: `companyID`, `takeOffTemplateID`, `oneOffsID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/oneOffs/{oneOffsID}`
  - **Description**: Update a take off template one-off.
  - **Parameters**: `companyID`, `takeOffTemplateID`, `oneOffsID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/oneOffs/{oneOffsID}`
  - **Description**: Delete a take off template one-off.
  - **Parameters**: `companyID`, `takeOffTemplateID`, `oneOffsID`
  - **Response**: No Content

#### Prebuilds

- [ ] `GET /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/prebuilds/`
  - **Description**: List all take off template prebuilds.
  - **Parameters**: `companyID`, `takeOffTemplateID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/prebuilds/`
  - **Description**: Create a new take off template prebuild.
  - **Parameters**: `companyID`, `takeOffTemplateID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/prebuilds/{prebuildsID}`
  - **Description**: Retrieve details for a specific take off template prebuild.
  - **Parameters**: `companyID`, `takeOffTemplateID`, `prebuildsID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/prebuilds/{prebuildsID}`
  - **Description**: Update a take off template prebuild.
  - **Parameters**: `companyID`, `takeOffTemplateID`, `prebuildsID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/prebuilds/{prebuildsID}`
  - **Description**: Delete a take off template prebuild.
  - **Parameters**: `companyID`, `takeOffTemplateID`, `prebuildsID`
  - **Response**: No Content

#### ServiceFees

- [ ] `GET /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/serviceFees/`
  - **Description**: List all take off template service fees.
  - **Parameters**: `companyID`, `takeOffTemplateID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/serviceFees/`
  - **Description**: Create a new take off template service fee.
  - **Parameters**: `companyID`, `takeOffTemplateID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/serviceFees/{serviceFeeID}`
  - **Description**: Retrieve details for a specific take off template service fee.
  - **Parameters**: `companyID`, `takeOffTemplateID`, `serviceFeeID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/serviceFees/{serviceFeeID}`
  - **Description**: Update a take off template service fee.
  - **Parameters**: `companyID`, `takeOffTemplateID`, `serviceFeeID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/serviceFees/{serviceFeeID}`
  - **Description**: Delete a take off template service fee.
  - **Parameters**: `companyID`, `takeOffTemplateID`, `serviceFeeID`
  - **Response**: No Content

#### TakeOffTemplates

- [ ] `POST /api/v1.0/companies/{companyID}/takeOffTemplates/{takeOffTemplateID}/takeOffTemplates/`
  - **Description**: Create a new take off template from take off template.
  - **Parameters**: `companyID`, `takeOffTemplateID`
  - **Response**: Unknown

### Nested: Tasks

- [ ] `GET /api/v1.0/companies/{companyID}/tasks/`
  - **Description**: List all tasks.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/tasks/`
  - **Description**: Create a new task.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/tasks/{taskID}`
  - **Description**: Retrieve details for a specific task.
  - **Parameters**: `companyID`, `taskID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/tasks/{taskID}`
  - **Description**: Update a task.
  - **Parameters**: `companyID`, `taskID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/tasks/{taskID}`
  - **Description**: Delete a task.
  - **Parameters**: `companyID`, `taskID`
  - **Response**: No Content

#### Attachments > files

- [ ] `GET /api/v1.0/companies/{companyID}/tasks/{taskID}/attachments/files/`
  - **Description**: List all task attachments.
  - **Parameters**: `companyID`, `taskID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/tasks/{taskID}/attachments/files/`
  - **Description**: Create a new task attachment.
  - **Parameters**: `companyID`, `taskID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/tasks/{taskID}/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific task attachment.
  - **Parameters**: `companyID`, `taskID`, `fileID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/tasks/{taskID}/attachments/files/{fileID}`
  - **Description**: Update a task attachment.
  - **Parameters**: `companyID`, `taskID`, `fileID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/tasks/{taskID}/attachments/files/{fileID}`
  - **Description**: Delete a task attachment.
  - **Parameters**: `companyID`, `taskID`, `fileID`
  - **Response**: No Content

#### Attachments > folders

- [ ] `GET /api/v1.0/companies/{companyID}/tasks/{taskID}/attachments/folders/`
  - **Description**: List all task attachment folders.
  - **Parameters**: `companyID`, `taskID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/tasks/{taskID}/attachments/folders/`
  - **Description**: Create a new task attachment folder.
  - **Parameters**: `companyID`, `taskID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/tasks/{taskID}/attachments/folders/{folderID}`
  - **Description**: Retrieve details for a specific task attachment folder.
  - **Parameters**: `companyID`, `taskID`, `folderID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/tasks/{taskID}/attachments/folders/{folderID}`
  - **Description**: Update a task attachment folder.
  - **Parameters**: `companyID`, `taskID`, `folderID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/tasks/{taskID}/attachments/folders/{folderID}`
  - **Description**: Delete a task attachment folder.
  - **Parameters**: `companyID`, `taskID`, `folderID`
  - **Response**: No Content

#### CustomFields

- [ ] `GET /api/v1.0/companies/{companyID}/tasks/{taskID}/customFields/`
  - **Description**: List all task custom fields.
  - **Parameters**: `companyID`, `taskID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/tasks/{taskID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific task custom field.
  - **Parameters**: `companyID`, `taskID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/tasks/{taskID}/customFields/{customFieldID}`
  - **Description**: Update a task custom field.
  - **Parameters**: `companyID`, `taskID`, `customFieldID`
  - **Response**: No Content

### Nested: VendorCredits

- [ ] `GET /api/v1.0/companies/{companyID}/vendorCredits/`
  - **Description**: List all vendor credits.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/vendorCredits/{vendorCreditID}`
  - **Description**: Retrieve details for a specific vendor credit.
  - **Parameters**: `companyID`, `vendorCreditID`, `columns`?
  - **Response**: object

### Nested: VendorOrders

- [ ] `GET /api/v1.0/companies/{companyID}/vendorOrders/`
  - **Description**: List all vendor orders.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/vendorOrders/`
  - **Description**: Create a new vendor order.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}`
  - **Description**: Retrieve details for a specific vendor order.
  - **Parameters**: `companyID`, `vendorOrderID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}`
  - **Description**: Update a vendor order.
  - **Parameters**: `companyID`, `vendorOrderID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}`
  - **Description**: Delete a vendor order.
  - **Parameters**: `companyID`, `vendorOrderID`
  - **Response**: No Content

#### Attachments > files

- [ ] `GET /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/attachments/files/`
  - **Description**: List all vendor order attachments.
  - **Parameters**: `companyID`, `vendorOrderID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/attachments/files/`
  - **Description**: Create a new vendor order attachment.
  - **Parameters**: `companyID`, `vendorOrderID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific vendor order attachment.
  - **Parameters**: `companyID`, `vendorOrderID`, `fileID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/attachments/files/{fileID}`
  - **Description**: Update a vendor order attachment.
  - **Parameters**: `companyID`, `vendorOrderID`, `fileID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/attachments/files/{fileID}`
  - **Description**: Delete a vendor order attachment.
  - **Parameters**: `companyID`, `vendorOrderID`, `fileID`
  - **Response**: No Content

#### Attachments > folders

- [ ] `GET /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/attachments/folders/`
  - **Description**: List all vendor order attachment folders.
  - **Parameters**: `companyID`, `vendorOrderID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/attachments/folders/`
  - **Description**: Create a new vendor order attachment folder.
  - **Parameters**: `companyID`, `vendorOrderID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/attachments/folders/{folderID}`
  - **Description**: Retrieve details for a specific vendor order attachment folder.
  - **Parameters**: `companyID`, `vendorOrderID`, `folderID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/attachments/folders/{folderID}`
  - **Description**: Update a vendor order attachment folder.
  - **Parameters**: `companyID`, `vendorOrderID`, `folderID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/attachments/folders/{folderID}`
  - **Description**: Delete a vendor order attachment folder.
  - **Parameters**: `companyID`, `vendorOrderID`, `folderID`
  - **Response**: No Content

#### Catalogs

- [ ] `GET /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/catalogs/`
  - **Description**: List all vendor order items.
  - **Parameters**: `companyID`, `vendorOrderID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/catalogs/`
  - **Description**: Create a new vendor order item.
  - **Parameters**: `companyID`, `vendorOrderID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/catalogs/{vendorOrderItemID}`
  - **Description**: Retrieve details for a specific vendor order item.
  - **Parameters**: `companyID`, `vendorOrderID`, `vendorOrderItemID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/catalogs/{vendorOrderItemID}`
  - **Description**: Update a vendor order item.
  - **Parameters**: `companyID`, `vendorOrderID`, `vendorOrderItemID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/catalogs/{vendorOrderItemID}`
  - **Description**: Delete a vendor order item.
  - **Parameters**: `companyID`, `vendorOrderID`, `vendorOrderItemID`
  - **Response**: No Content

#### Catalogs > allocations

- [ ] `GET /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/catalogs/{vendorOrderItemID}/allocations/`
  - **Description**: List all vendor order item allocations.
  - **Parameters**: `companyID`, `vendorOrderID`, `vendorOrderItemID`, `search`?, `columns`?
  - **Response**: Array of object

- [ ] `PATCH /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/catalogs/{vendorOrderItemID}/allocations/`
  - **Description**: Update multiple vendor order item allocations.
  - **Parameters**: `companyID`, `vendorOrderID`, `vendorOrderItemID`
  - **Response**: Unknown

- [ ] `POST /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/catalogs/{vendorOrderItemID}/allocations/`
  - **Description**: Create multiple new vendor order item allocations.
  - **Parameters**: `companyID`, `vendorOrderID`, `vendorOrderItemID`
  - **Response**: Unknown

- [ ] `PUT /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/catalogs/{vendorOrderItemID}/allocations/`
  - **Description**: Replace vendor order item allocations.
  - **Parameters**: `companyID`, `vendorOrderID`, `vendorOrderItemID`
  - **Response**: Created

#### CustomFields

- [ ] `GET /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/customFields/`
  - **Description**: List all vendor order custom fields.
  - **Parameters**: `companyID`, `vendorOrderID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific vendor order custom field.
  - **Parameters**: `companyID`, `vendorOrderID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/customFields/{customFieldID}`
  - **Description**: Update a vendor order custom field.
  - **Parameters**: `companyID`, `vendorOrderID`, `customFieldID`
  - **Response**: No Content

#### Receipts

- [ ] `GET /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/receipts/`
  - **Description**: List all vendor receipts.
  - **Parameters**: `companyID`, `vendorOrderID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/receipts/`
  - **Description**: Create a new vendor receipt.
  - **Parameters**: `companyID`, `vendorOrderID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/receipts/{vendorReceiptID}`
  - **Description**: Retrieve details for a specific vendor receipt.
  - **Parameters**: `companyID`, `vendorOrderID`, `vendorReceiptID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/receipts/{vendorReceiptID}`
  - **Description**: Update a vendor receipt.
  - **Parameters**: `companyID`, `vendorOrderID`, `vendorReceiptID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/receipts/{vendorReceiptID}`
  - **Description**: Delete a vendor receipt.
  - **Parameters**: `companyID`, `vendorOrderID`, `vendorReceiptID`
  - **Response**: No Content

#### Receipts > catalogs

- [ ] `GET /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/receipts/{vendorReceiptID}/catalogs/`
  - **Description**: List all vendor receipt items.
  - **Parameters**: `companyID`, `vendorOrderID`, `vendorReceiptID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/receipts/{vendorReceiptID}/catalogs/{vendorReceiptItemID}`
  - **Description**: Retrieve details for a specific vendor receipt item.
  - **Parameters**: `companyID`, `vendorOrderID`, `vendorReceiptID`, `vendorReceiptItemID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/receipts/{vendorReceiptID}/catalogs/{vendorReceiptItemID}`
  - **Description**: Update a vendor receipt item.
  - **Parameters**: `companyID`, `vendorOrderID`, `vendorReceiptID`, `vendorReceiptItemID`
  - **Response**: No Content

#### Receipts > catalogs > allocations

- [ ] `GET /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/receipts/{vendorReceiptID}/catalogs/{vendorReceiptItemID}/allocations/`
  - **Description**: List all vendor receipt item allocations.
  - **Parameters**: `companyID`, `vendorOrderID`, `vendorReceiptID`, `vendorReceiptItemID`, `search`?, `columns`?
  - **Response**: Array of object

#### Receipts > credits

- [ ] `GET /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/receipts/{vendorReceiptID}/credits/`
  - **Description**: List all vendor receipt credits.
  - **Parameters**: `companyID`, `vendorOrderID`, `vendorReceiptID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/receipts/{vendorReceiptID}/credits/`
  - **Description**: Create a new vendor receipt credit.
  - **Parameters**: `companyID`, `vendorOrderID`, `vendorReceiptID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/receipts/{vendorReceiptID}/credits/{vendorCreditID}`
  - **Description**: Retrieve details for a specific vendor receipt credit.
  - **Parameters**: `companyID`, `vendorOrderID`, `vendorReceiptID`, `vendorCreditID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/receipts/{vendorReceiptID}/credits/{vendorCreditID}`
  - **Description**: Update a vendor receipt credit.
  - **Parameters**: `companyID`, `vendorOrderID`, `vendorReceiptID`, `vendorCreditID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/receipts/{vendorReceiptID}/credits/{vendorCreditID}`
  - **Description**: Delete a vendor receipt credit.
  - **Parameters**: `companyID`, `vendorOrderID`, `vendorReceiptID`, `vendorCreditID`
  - **Response**: No Content

#### Receipts > credits > catalogs

- [ ] `GET /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/receipts/{vendorReceiptID}/credits/{vendorCreditID}/catalogs/`
  - **Description**: List all vendor credit items.
  - **Parameters**: `companyID`, `vendorOrderID`, `vendorReceiptID`, `vendorCreditID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/vendorOrders/{vendorOrderID}/receipts/{vendorReceiptID}/credits/{vendorCreditID}/catalogs/{vendorCreditItemID}`
  - **Description**: Retrieve details for a specific vendor credit item.
  - **Parameters**: `companyID`, `vendorOrderID`, `vendorReceiptID`, `vendorCreditID`, `vendorCreditItemID`, `columns`?
  - **Response**: object

### Nested: VendorQuotes

- [ ] `GET /api/v1.0/companies/{companyID}/vendorQuotes/`
  - **Description**: List all vendor quotes.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/vendorQuotes/`
  - **Description**: Create a new vendor quote.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/vendorQuotes/{vendorQuoteID}`
  - **Description**: Retrieve details for a specific vendor quote.
  - **Parameters**: `companyID`, `vendorQuoteID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/vendorQuotes/{vendorQuoteID}`
  - **Description**: Update a vendor quote.
  - **Parameters**: `companyID`, `vendorQuoteID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/vendorQuotes/{vendorQuoteID}`
  - **Description**: Delete a vendor quote.
  - **Parameters**: `companyID`, `vendorQuoteID`
  - **Response**: No Content

#### Catalogs

- [ ] `GET /api/v1.0/companies/{companyID}/vendorQuotes/{vendorQuoteID}/catalogs/`
  - **Description**: List all vendor quote catalogs.
  - **Parameters**: `companyID`, `vendorQuoteID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/vendorQuotes/{vendorQuoteID}/catalogs/`
  - **Description**: Create a new vendor quote catalog.
  - **Parameters**: `companyID`, `vendorQuoteID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/vendorQuotes/{vendorQuoteID}/catalogs/{catalogID}`
  - **Description**: Retrieve details for a specific vendor quote catalog.
  - **Parameters**: `companyID`, `vendorQuoteID`, `catalogID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/vendorQuotes/{vendorQuoteID}/catalogs/{catalogID}`
  - **Description**: Update a vendor quote catalog.
  - **Parameters**: `companyID`, `vendorQuoteID`, `catalogID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/vendorQuotes/{vendorQuoteID}/catalogs/{catalogID}`
  - **Description**: Delete a vendor quote catalog.
  - **Parameters**: `companyID`, `vendorQuoteID`, `catalogID`
  - **Response**: No Content

#### Catalogs > vendorPrices

- [ ] `GET /api/v1.0/companies/{companyID}/vendorQuotes/{vendorQuoteID}/catalogs/{catalogID}/vendorPrices/`
  - **Description**: List all vendor catalog prices.
  - **Parameters**: `companyID`, `vendorQuoteID`, `catalogID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/vendorQuotes/{vendorQuoteID}/catalogs/{catalogID}/vendorPrices/{vendorID}`
  - **Description**: Retrieve details for a specific vendor catalog price.
  - **Parameters**: `companyID`, `vendorQuoteID`, `catalogID`, `vendorID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/vendorQuotes/{vendorQuoteID}/catalogs/{catalogID}/vendorPrices/{vendorID}`
  - **Description**: Update a vendor catalog price.
  - **Parameters**: `companyID`, `vendorQuoteID`, `catalogID`, `vendorID`
  - **Response**: No Content

#### Vendors

- [ ] `GET /api/v1.0/companies/{companyID}/vendorQuotes/{vendorQuoteID}/vendors/`
  - **Description**: List all vendor quote vendors.
  - **Parameters**: `companyID`, `vendorQuoteID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/vendorQuotes/{vendorQuoteID}/vendors/`
  - **Description**: Create a new vendor quote vendor.
  - **Parameters**: `companyID`, `vendorQuoteID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/vendorQuotes/{vendorQuoteID}/vendors/{vendorID}`
  - **Description**: Retrieve details for a specific vendor quote vendor.
  - **Parameters**: `companyID`, `vendorQuoteID`, `vendorID`, `columns`?
  - **Response**: object

### Nested: VendorReceipts

- [ ] `GET /api/v1.0/companies/{companyID}/vendorReceipts/`
  - **Description**: Search vendor receipt.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

### Nested: Vendors

- [ ] `GET /api/v1.0/companies/{companyID}/vendors/`
  - **Description**: List all vendors.
  - **Parameters**: `companyID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/vendors/`
  - **Description**: Create a new vendor.
  - **Parameters**: `companyID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/vendors/{vendorID}`
  - **Description**: Retrieve details for a specific vendor.
  - **Parameters**: `companyID`, `vendorID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/vendors/{vendorID}`
  - **Description**: Update a vendor.
  - **Parameters**: `companyID`, `vendorID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/vendors/{vendorID}`
  - **Description**: Delete a vendor.
  - **Parameters**: `companyID`, `vendorID`
  - **Response**: No Content

#### Attachments > files

- [ ] `GET /api/v1.0/companies/{companyID}/vendors/{vendorID}/attachments/files/`
  - **Description**: List all vendor attachments.
  - **Parameters**: `companyID`, `vendorID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/vendors/{vendorID}/attachments/files/`
  - **Description**: Create a new vendor attachment.
  - **Parameters**: `companyID`, `vendorID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/vendors/{vendorID}/attachments/files/{fileID}`
  - **Description**: Retrieve details for a specific vendor attachment.
  - **Parameters**: `companyID`, `vendorID`, `fileID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/vendors/{vendorID}/attachments/files/{fileID}`
  - **Description**: Update a vendor attachment.
  - **Parameters**: `companyID`, `vendorID`, `fileID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/vendors/{vendorID}/attachments/files/{fileID}`
  - **Description**: Delete a vendor attachment.
  - **Parameters**: `companyID`, `vendorID`, `fileID`
  - **Response**: No Content

#### Attachments > folders

- [ ] `GET /api/v1.0/companies/{companyID}/vendors/{vendorID}/attachments/folders/`
  - **Description**: List all vendor attachment folders.
  - **Parameters**: `companyID`, `vendorID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/vendors/{vendorID}/attachments/folders/`
  - **Description**: Create a new vendor attachment folder.
  - **Parameters**: `companyID`, `vendorID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/vendors/{vendorID}/attachments/folders/{folderID}`
  - **Description**: Retrieve details for a specific vendor attachment folder.
  - **Parameters**: `companyID`, `vendorID`, `folderID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/vendors/{vendorID}/attachments/folders/{folderID}`
  - **Description**: Update a vendor attachment folder.
  - **Parameters**: `companyID`, `vendorID`, `folderID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/vendors/{vendorID}/attachments/folders/{folderID}`
  - **Description**: Delete a vendor attachment folder.
  - **Parameters**: `companyID`, `vendorID`, `folderID`
  - **Response**: No Content

#### Branches

- [ ] `GET /api/v1.0/companies/{companyID}/vendors/{vendorID}/branches/`
  - **Description**: List all vendor branches.
  - **Parameters**: `companyID`, `vendorID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/vendors/{vendorID}/branches/`
  - **Description**: Create a new vendor branch.
  - **Parameters**: `companyID`, `vendorID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/vendors/{vendorID}/branches/{branchID}`
  - **Description**: Retrieve details for a specific vendor branch.
  - **Parameters**: `companyID`, `vendorID`, `branchID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/vendors/{vendorID}/branches/{branchID}`
  - **Description**: Update a vendor branch.
  - **Parameters**: `companyID`, `vendorID`, `branchID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/vendors/{vendorID}/branches/{branchID}`
  - **Description**: Delete a vendor branch.
  - **Parameters**: `companyID`, `vendorID`, `branchID`
  - **Response**: No Content

#### Contacts

- [ ] `GET /api/v1.0/companies/{companyID}/vendors/{vendorID}/contacts/`
  - **Description**: List all vendor contacts.
  - **Parameters**: `companyID`, `vendorID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `POST /api/v1.0/companies/{companyID}/vendors/{vendorID}/contacts/`
  - **Description**: Create a new vendor contact.
  - **Parameters**: `companyID`, `vendorID`
  - **Response**: Created

- [ ] `GET /api/v1.0/companies/{companyID}/vendors/{vendorID}/contacts/{contactID}`
  - **Description**: Retrieve details for a specific vendor contact.
  - **Parameters**: `companyID`, `vendorID`, `contactID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/vendors/{vendorID}/contacts/{contactID}`
  - **Description**: Update a vendor contact.
  - **Parameters**: `companyID`, `vendorID`, `contactID`
  - **Response**: No Content

- [ ] `DELETE /api/v1.0/companies/{companyID}/vendors/{vendorID}/contacts/{contactID}`
  - **Description**: Delete a vendor contact.
  - **Parameters**: `companyID`, `vendorID`, `contactID`
  - **Response**: No Content

#### Contacts > customFields

- [ ] `GET /api/v1.0/companies/{companyID}/vendors/{vendorID}/contacts/{contactID}/customFields/`
  - **Description**: List all vendor contact custom fields.
  - **Parameters**: `companyID`, `vendorID`, `contactID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/vendors/{vendorID}/contacts/{contactID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific vendor contact custom field.
  - **Parameters**: `companyID`, `vendorID`, `contactID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/vendors/{vendorID}/contacts/{contactID}/customFields/{customFieldID}`
  - **Description**: Update a vendor contact custom field.
  - **Parameters**: `companyID`, `vendorID`, `contactID`, `customFieldID`
  - **Response**: No Content

#### CustomFields

- [ ] `GET /api/v1.0/companies/{companyID}/vendors/{vendorID}/customFields/`
  - **Description**: List all vendor custom fields.
  - **Parameters**: `companyID`, `vendorID`, `search`?, `columns`?, `pageSize`?, `page`?, `orderby`?, `limit`?
  - **Response**: Array of object

- [ ] `GET /api/v1.0/companies/{companyID}/vendors/{vendorID}/customFields/{customFieldID}`
  - **Description**: Retrieve details for a specific vendor custom field.
  - **Parameters**: `companyID`, `vendorID`, `customFieldID`, `columns`?
  - **Response**: object

- [ ] `PATCH /api/v1.0/companies/{companyID}/vendors/{vendorID}/customFields/{customFieldID}`
  - **Description**: Update a vendor custom field.
  - **Parameters**: `companyID`, `vendorID`, `customFieldID`
  - **Response**: No Content

---

## CurrentUser

- [x] `GET /api/v1.0/currentUser/`
  - **Description**: List all current user.
  - **Parameters**: `search`?, `columns`?
  - **Response**: Array of object

---

## Info

- [x] `GET /api/v1.0/info/`
  - **Description**: Retrieve details for a specific info.
  - **Parameters**: `columns`?
  - **Response**: object

---

