# Swagger Verification Task List

> **Purpose:** Verify each SDK resource against the `swagger 2.json` specification to ensure DTOs and Requests match the actual API schema.

---

## How to Verify Each Resource

For each resource, perform these checks:

1. **Requests:** Check endpoint paths, HTTP methods, and query parameters match swagger
2. **Response DTOs:** Verify all fields match the swagger `responses.200.schema.properties`
3. **Request Body DTOs:** Verify POST/PATCH body fields match swagger `parameters[].schema.properties`
4. **Field Types:** Ensure PHP types match swagger types (string, integer, boolean, array, object)
5. **Nullable Fields:** Check which fields are optional vs required
6. **Detailed List Support:** Check if the list endpoint supports returning full DTOs via columns parameter

### Detailed List Support (listDetailed)

Many Simpro API list endpoints support returning the full detailed DTO instead of a summary by using the `columns` query parameter. When verifying a resource:

1. **Check swagger for columns parameter** - Look for `columns` in the GET list endpoint parameters
2. **Compare list vs detail schemas** - If the list endpoint can return the same fields as the detail endpoint, implement `listDetailed()`
3. **Implementation pattern:**
   ```php
   // Standard list - returns ListItem DTO with minimal fields
   $connector->resource()->list();

   // Detailed list - returns full DTO with all fields
   $connector->resource()->listDetailed();
   ```
4. **When to implement:**
   - The list endpoint accepts a `columns` parameter
   - The detail GET endpoint returns additional fields not in the default list response
   - Users would benefit from fetching full records in bulk without N+1 GET requests

### Verification Checklist Template

- [ ] Endpoints match swagger paths
- [ ] HTTP methods correct (GET, POST, PATCH, DELETE)
- [ ] Query parameters match swagger
- [ ] Response DTO fields match swagger schema
- [ ] Request body fields match swagger schema (for POST/PATCH)
- [ ] Nested objects hydrated correctly
- [ ] Tests use accurate fixture data
- [ ] Detailed list support checked (listDetailed if applicable)

---

## Top-Level Resources

### 1. Info Resource ✅ VERIFIED
**Files:** `src/Resources/InfoResource.php`, `src/Data/Info/Info.php`

| Check | Status | Notes |
|-------|--------|-------|
| Endpoints | [x] | GET /api/v1.0/info/ |
| Response DTO | [x] | |
| Tests | [x] | |

---

### 2. Companies Resource ✅ VERIFIED
**Files:** `src/Resources/CompanyResource.php`, `src/Data/Companies/`

| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | GET /api/v1.0/companies/ |
| Get endpoint | [x] | GET /api/v1.0/companies/{companyID} |
| Response DTO (List) | [x] | |
| Response DTO (Detail) | [x] | |
| Detailed list support | [x] | listDetailed() available |
| Tests | [x] | |

---

### 3. Current User Resource ✅ VERIFIED
**Files:** `src/Resources/CurrentUserResource.php`, `src/Data/CurrentUser/`

| Check | Status | Notes |
|-------|--------|-------|
| Endpoint | [x] | GET /api/v1.0/currentUser/ |
| Response DTO | [x] | Fixed to match actual API response |
| Tests | [x] | Updated fixture and assertions |

**Major fixes applied:**
- Removed incorrect fields: `username`, `email`, `givenName`, `familyName`, `displayName`, `companies`
- Removed `fullName()` helper method
- Added correct fields: `name` (?string), `type` (?string), `typeId` (?int), `preferredLanguage` (?string)
- Renamed `companies` to `accessibleCompanies` (from `AccessibleCompanies` API field)
- Updated test fixture with real API response data
- Updated documentation to reflect corrected DTO

---

### 4. Activity Schedules Resource ✅ VERIFIED
**Files:** `src/Resources/ActivityScheduleResource.php`, `src/Data/ActivitySchedules/`

| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | GET /api/v1.0/companies/{companyID}/activitySchedules/ |
| Get endpoint | [x] | GET /api/v1.0/companies/{companyID}/activitySchedules/{scheduleID} |
| Create endpoint | [x] | POST /api/v1.0/companies/{companyID}/activitySchedules/ |
| Update endpoint | [x] | PATCH /api/v1.0/companies/{companyID}/activitySchedules/{scheduleID} |
| Delete endpoint | [x] | DELETE /api/v1.0/companies/{companyID}/activitySchedules/{scheduleID} |
| Response DTO (List) | [x] | ActivityScheduleListItem |
| Response DTO (Detail) | [x] | ActivitySchedule |
| Detailed list support | [x] | listDetailed() added |
| Request body DTO | [x] | Uses array |
| Tests | [x] | Updated fixtures to match real API |
| Documentation | [x] | docs/activity-schedules-resource.md |

---

### 5. Schedules Resource ✅ VERIFIED
**Files:** `src/Resources/ScheduleResource.php`, `src/Data/Schedules/`

| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | GET /api/v1.0/companies/{companyID}/schedules/ |
| Get endpoint | [x] | GET /api/v1.0/companies/{companyID}/schedules/{scheduleID} |
| Response DTO (List) | [x] | ScheduleListItem - completely rewritten to match swagger |
| Response DTO (Detail) | [x] | Schedule - completely rewritten to match swagger |
| Detailed list support | [x] | listDetailed() added |
| ScheduleBlock | [x] | Added scheduleRate field, uses DateTimeImmutable |
| Tests | [x] | Updated fixtures and tests to match swagger |
| Documentation | [x] | docs/schedules-resource.md updated |

**Major fixes applied:**
- Removed incorrect fields: `subject`, `job`, `startTime`, `endTime` at top level
- Added correct fields: `reference`, `totalHours`, `blocks`, `href`
- Now uses `StaffReference` (with type/typeId) instead of custom ScheduleStaff
- ScheduleBlock now includes `scheduleRate` (Reference object)
- Removed obsolete DTOs: ScheduleStaff.php, ScheduleListStaff.php, ScheduleJob.php

---

### 6. Jobs Resource ✅ VERIFIED
**Files:** `src/Resources/JobResource.php`, `src/Resources/Jobs/`, `src/Data/Jobs/`

| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | GET /api/v1.0/companies/{companyID}/jobs/ |
| Get endpoint | [x] | GET /api/v1.0/companies/{companyID}/jobs/{jobID} |
| Create endpoint | [x] | POST /api/v1.0/companies/{companyID}/jobs/ |
| Update endpoint | [x] | PATCH /api/v1.0/companies/{companyID}/jobs/{jobID} |
| Delete endpoint | [x] | DELETE /api/v1.0/companies/{companyID}/jobs/{jobID} |
| Response DTO (List) | [x] | |
| Response DTO (Detail) | [x] | |
| Request body DTO | [x] | |
| Tests | [x] | |

**Major fixes applied:**
- `JobStaff` replaced with `StaffReference` (supports Type/TypeId)
- Created `JobVariationReference` for linkedVariations and convertedFromQuote
- Created `JobConvertedFrom` for convertedFrom (ID, Type, Date)
- Created `JobResponseTime` DTO for response time object
- Added `revized` field to `JobCostBreakdown`
- Updated test fixtures to match swagger schema

#### 6.1 Job Sections ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | |
| Get endpoint | [x] | |
| Create endpoint | [x] | |
| Update endpoint | [x] | |
| Delete endpoint | [x] | |
| DTOs | [x] | Added isVariation, isVariationRetention, dateModified |

#### 6.2 Job Cost Centers ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | |
| Get endpoint | [x] | |
| Create endpoint | [x] | |
| Update endpoint | [x] | |
| Delete endpoint | [x] | |
| DTOs | [x] | Complete rewrite with all swagger fields |

**Major fixes applied:**
- `CostCenter` now includes: jobId, header, site, stage, description, notes, orderNo, startDate, endDate, autoAdjustDates, variation, variationApprovalDate, itemsLocked, lockedInfo, vendorOrders, totals, dateModified, percentComplete
- Created `CostCenterLockedInfo`, `CostCenterVendorOrder`, `CostCenterVendorOrderTotals` DTOs
- `CostCenterListItem` now uses `Reference` for costCenter

#### 6.3 Job Cost Center Sub-resources ✅ VERIFIED
- [x] Assets
- [x] Labor
- [x] Service Fees
- [x] Contractor Jobs
- [x] Prebuilds
- [x] Schedules
- [x] Stock
- [x] One-Offs
- [x] Work Orders
- [x] Tasks
- [x] Lock
- [x] Catalogs

#### 6.4 Job Attachments ✅ VERIFIED
- [x] Files (List, Get, Create, Update, Delete)
- [x] Folders (List, Get, Create, Update, Delete)

#### 6.5 Job Custom Fields ✅ VERIFIED
- [x] List, Get, Update

#### 6.6 Job Notes ✅ VERIFIED
- [x] List, Get, Create, Update, Delete

**Major fixes applied:**
- Added `followUpDate`, `visibility`, `assignTo`, `attachments` fields
- Created `JobNoteVisibility` and `JobNoteAttachment` DTOs

#### 6.7 Job Lock ✅ VERIFIED
- [x] Create (POST), Delete

#### 6.8 Job Tasks ✅ VERIFIED
- [x] List, Get

**Major fixes applied:**
- Complete rewrite of `JobTask` DTO to match swagger
- Created supporting DTOs: `JobTaskStatus`, `JobTaskPriority`, `JobTaskTime`, `JobTaskEmailNotifications`, `JobTaskAssociated`, `JobTaskAssociatedJob`, `JobTaskSubTask`, `JobTaskCustomField`
- Uses `StaffReference` for createdBy, assignedTo, completedBy, assignees

#### 6.9 Job Timelines ✅ VERIFIED
- [x] List

**Major fixes applied:**
- Updated field names: `type`, `message`, `staff`, `date`
- Uses `StaffReference` instead of flat fields

---

### 7. Customers Resource ✅ VERIFIED
**Files:** `src/Resources/CustomerResource.php`, `src/Resources/Customers/`, `src/Data/Customers/`

| Check | Status | Notes |
|-------|--------|-------|
| General List | [x] | GET /api/v1.0/companies/{companyID}/customers/ |
| Tests | [x] | Updated fixtures and assertions |

#### 7.1 Customer Companies ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | GET /api/v1.0/companies/{companyID}/customers/companies/ |
| Get endpoint | [x] | GET /api/v1.0/companies/{companyID}/customers/companies/{customerID} |
| Create endpoint | [x] | POST - added createSite query parameter |
| Update endpoint | [x] | PATCH - added createSite query parameter |
| Delete endpoint | [x] | DELETE /api/v1.0/companies/{companyID}/customers/companies/{customerID} |
| Response DTO (List) | [x] | Created CustomerCompanyListItem with {id, companyName} |
| Response DTO (Detail) | [x] | Customer - all 26 fields match swagger |
| Detailed list support | [x] | Added 8 missing columns: Rates, PreferredTechs, DoNotCall, AltPhone, EIN, Website, Fax, CompanyNumber |
| Request body DTO | [x] | Uses array |

**Major fixes applied:**
- Added `_href` field to `CustomerListItem`
- Created dedicated `CustomerCompanyListItem` for company list (was reusing `CustomerListItem`)
- Added `createSite` boolean query parameter to Create/Update requests
- Added 8 missing columns to `ListCompanyCustomersDetailedRequest`
- Added 8 missing fields to `CustomerCompanyListDetailedItem` (rates, preferredTechs, doNotCall, altPhone, ein, website, fax, companyNumber)
- Deleted orphaned DTOs: `CustomerType`, `CustomerProfile`, `CustomerBanking`

#### 7.2 Customer Individuals ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | |
| Get endpoint | [x] | |
| Create endpoint | [x] | Added createSite query parameter |
| Update endpoint | [x] | Added createSite query parameter |
| Delete endpoint | [x] | |
| DTOs | [x] | CustomerIndividual, CustomerIndividualListItem match swagger |

#### 7.3 Customer Contacts ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | |
| Get endpoint | [x] | |
| Create endpoint | [x] | |
| Update endpoint | [x] | |
| Delete endpoint | [x] | |
| Custom Fields | [x] | |
| DTOs | [x] | |

**Major fixes applied:**
- Removed extra fields from `ContactListItem` (email, phone, position not in swagger list response)
- Added `Contact` (nullable ContactReference object) to `Contact` DTO
- Created `ContactReference` DTO with {id, givenName, familyName, email}
- Added `Contact` column to `ListContactsDetailedRequest`

#### 7.4 Customer Contracts ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | |
| Get endpoint | [x] | |
| Create endpoint | [x] | |
| Update endpoint | [x] | |
| Delete endpoint | [x] | |
| Inflation | [x] | ContractInflationListItem matches swagger |
| Labor Rates | [x] | ContractLaborRateListItem matches swagger |
| Service Levels | [x] | Contract service level list matches swagger |
| Custom Fields | [x] | |
| DTOs | [x] | Contract, ContractListItem match swagger |

---

### 8. Quotes Resource ✅ VERIFIED
**Files:** `src/Resources/QuoteResource.php`, `src/Resources/Quotes/`, `src/Data/Quotes/`, `src/Scopes/Quotes/`

| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | GET /api/v1.0/companies/{companyID}/quotes/ |
| Get endpoint | [x] | GET /api/v1.0/companies/{companyID}/quotes/{quoteID} |
| Create endpoint | [x] | POST /api/v1.0/companies/{companyID}/quotes/ |
| Update endpoint | [x] | PATCH /api/v1.0/companies/{companyID}/quotes/{quoteID} |
| Delete endpoint | [x] | DELETE /api/v1.0/companies/{companyID}/quotes/{quoteID} |
| Response DTO (List) | [x] | QuoteListItem |
| Response DTO (Detail) | [x] | Quote - ~25 new fields added |
| Detailed list support | [x] | listDetailed() added |
| Request body DTO | [x] | Uses array |
| Tests | [x] | Updated fixtures and assertions |

**Major fixes applied:**
- `QuoteCustomer`: Removed `type`, added `givenName`, `familyName`
- `QuoteTotals`: Rewritten with nested `JobCostBreakdown`, `JobResourcesCost`, `JobResourcesMarkup`
- `Quote`: Added ~25 fields: `type`, `additionalCustomers`, `customerContact`, `additionalContacts`, `siteContact`, `convertedFromLead`, `salesperson`, `projectManager`, `technicians`, `technician`, `dateApproved`, `validityDays`, `requestNo`, `isClosed`, `archiveReason`, `customerStage`, `jobNo`, `isVariation`, `linkedJobId`, `forecast`, `tags`, `autoAdjustStatus`, `customFields`, `stc`
- Status is now polymorphic (`QuoteStatus|string|null`)
- Total is now polymorphic (`QuoteTotal|float|null`)
- Created 6 new supporting DTOs: `QuoteContact`, `QuoteConvertedFromLead`, `QuoteArchiveReasonRef`, `QuoteForecast`, `QuoteStatus`, `QuoteStc`

#### 8.1 Quote Sections ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | |
| Get endpoint | [x] | |
| Create endpoint | [x] | |
| Update endpoint | [x] | |
| Delete endpoint | [x] | |
| DTOs | [x] | QuoteSectionListItem, QuoteSection |

#### 8.2 Quote Cost Centers ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | |
| Get endpoint | [x] | |
| Create endpoint | [x] | |
| Update endpoint | [x] | |
| Delete endpoint | [x] | |
| DTOs | [x] | Reuses Job CostCenter/CostCenterListItem DTOs |

#### 8.3 Quote Cost Center Sub-resources ✅ VERIFIED
- [x] Assets (List, Get, Create, Update)
- [x] Catalogs (List, Get, Create, Update, Delete)
- [x] ContractorJobs (List, Get, Create, Update, Delete)
- [x] Labor (List, Get, Create, Update, Delete)
- [x] OneOffs (List, Get, Create, Update, Delete)
- [x] Prebuilds (List, Get, Create, Delete)
- [x] Schedules (List, Get, Create, Update, Delete)
- [x] ServiceFees (List, Get, Create, Update, Delete)
- [x] Tasks (List, Get)
- [x] WorkOrders (List, Get, Create, Update)

#### 8.4 Quote Attachments ✅ VERIFIED
- [x] Files (List, Get, Create)
- [x] Folders (List, Get, Create)

#### 8.5 Quote Custom Fields ✅ VERIFIED
- [x] List, Get (reuses JobCustomFieldValue DTO)

#### 8.6 Quote Notes ✅ VERIFIED
- [x] List, Get, Create, Update, Delete
- [x] QuoteNote DTO with subject, note, dates, assignTo, createdBy

#### 8.7 Quote Lock ✅ VERIFIED
- [x] Create (POST), Delete

#### 8.8 Quote Tasks ✅ VERIFIED
- [x] List, Get
- [x] QuoteTask DTO with subject, description, notes, assignees, isBillable, percentComplete

#### 8.9 Quote Timelines ✅ VERIFIED
- [x] List
- [x] QuoteTimeline DTO with type, message, staff, date

---

### 9. Invoices Resource ✅ VERIFIED
**Files:** `src/Resources/InvoiceResource.php`, `src/Data/Invoices/`

| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | GET /api/v1.0/companies/{companyID}/invoices/ |
| Get endpoint | [x] | GET /api/v1.0/companies/{companyID}/invoices/{invoiceID} |
| Create endpoint | [x] | POST |
| Update endpoint | [x] | PATCH |
| Delete endpoint | [x] | DELETE |
| Detailed list support | [x] | listDetailed() available |
| Response DTO (List) | [x] | Added RecurringInvoice, updated Total with full fields |
| Response DTO (Detail) | [x] | Fully rewritten to match modern /invoices/ endpoint schema |
| Request body DTO | [x] | |
| Tests | [x] | |

**Major fixes applied:**
- Rewrote `Invoice` DTO — removed legacy fields (InvoiceNo, Site, Totals with TotalExTax/AmountDue/AmountPaid), replaced with swagger-accurate fields (InternalID, Stage, Status as {ID,Name}, PaymentTerms, CostCenters, etc.)
- Deleted `InvoiceTotals` (legacy endpoint), `InvoiceSite` (not in modern endpoint), `InvoiceListCustomer` (merged into `InvoiceCustomer`)
- Updated `InvoiceCustomer` — removed `type`, added `givenName`/`familyName` to unify list and detail
- Updated `InvoiceTotal` — added `reverseChargeTax`, `amountApplied`, `balanceDue`
- Updated `InvoiceListItem` — added `recurringInvoice`, changed customer type
- Created 11 new supporting DTOs: InvoiceRecurringInvoice, InvoicePeriod, InvoicePaymentTerms, InvoiceStatus, InvoiceRetainage, InvoiceCostCenter, InvoiceCostCenterTotal, InvoiceCostCenterClaim, InvoiceCostCenterItem, InvoiceCostCenterItemDetail, InvoiceCostCenterItemQuantity
- Added `ListDetailedInvoicesRequest` with columns parameter

#### 9.1 Invoice Sub-resources ✅ VERIFIED

| Sub-resource | Status | Operations |
|-------------|--------|------------|
| Notes | [x] | List, Get, Create, Update, Delete |
| Custom Fields | [x] | List, Get, Update (reuses JobCustomFieldValue DTO) |
| Credit Notes | [x] | List, Get, Create, Update |
| Credit Note Notes | [x] | List, Get, Create, Update, Delete |
| Credit Note Custom Fields | [x] | List, Get, Update |
| Cost Centers | [x] | Skipped (private/internal) — data included in detail response |

**Scopes added:** `InvoiceScope`, `InvoiceCreditNoteScope`

---

### 10. Employees Resource
**Files:** `src/Resources/EmployeeResource.php`, `src/Data/Employees/`

| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [ ] | GET /api/v1.0/companies/{companyID}/employees/ |
| Get endpoint | [ ] | GET /api/v1.0/companies/{companyID}/employees/{employeeID} |
| Create endpoint | [ ] | POST |
| Update endpoint | [ ] | PATCH |
| Delete endpoint | [ ] | DELETE |
| Response DTO (List) | [ ] | |
| Response DTO (Detail) | [ ] | |
| Request body DTO | [ ] | |
| Tests | [ ] | |

#### 10.1 Employee Sub-resources
- [ ] Timesheets
- [ ] Attachments (Files & Folders)
- [ ] Custom Fields
- [ ] Licences
- [ ] Licence Attachments

---

### 11. Reports Resource
**Files:** `src/Resources/ReportResource.php`, `src/Data/Reports/`

| Check | Status | Notes |
|-------|--------|-------|
| Job Cost to Complete Financial | [ ] | |
| Job Cost to Complete Operations | [ ] | |
| Response DTOs | [ ] | |
| Tests | [ ] | |

---

### 12. Setup Resource ✅ VERIFIED (All Sub-Resources Complete)
**Files:** `src/Resources/SetupResource.php`, `src/Resources/Setup/`, `src/Data/Setup/`

**Summary:** All 27 Setup sub-resources have been verified against swagger specification. This includes:
- 10 Account-related resources (Tax Codes, Payment Methods/Terms, Customer Groups, Zones, Accounting Categories, Business Groups, Chart of Accounts, Cost Centers)
- 19 Custom Field types (all sharing the same schema)
- 8 Asset Type resources (with 7 nested sub-resources)
- 5 Labor resources
- 5 Materials resources
- 3 Status Code types
- 2 Tag types
- 2 Commission types
- Plus: Activities, Defaults, Response Times, Security Groups, Teams, Webhooks, Quote Archive Reasons, Asset Service Levels, Customer Profiles, Task Categories

#### 12.1 Webhooks ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/webhooks/ |
| Get endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/webhooks/{webhookID} |
| Create endpoint | [x] | POST /api/v1.0/companies/{companyID}/setup/webhooks/ |
| Update endpoint | [x] | PATCH /api/v1.0/companies/{companyID}/setup/webhooks/{webhookID} |
| Delete endpoint | [x] | DELETE /api/v1.0/companies/{companyID}/setup/webhooks/{webhookID} |
| Response DTO (List) | [x] | WebhookListItem - ID, Name |
| Response DTO (Detail) | [x] | Webhook - ID, Name, CallbackURL, Secret, Email, Description, Events, Status, DateCreated |
| Detailed list support | [x] | listDetailed() added |
| Tests | [x] | Existing tests validated |

#### 12.2 Tax Codes ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/accounts/taxCodes/ |
| Response DTO | [x] | TaxCode - ID, _href, Code, Type, Rate |
| Detailed list support | [x] | Not needed - list returns all fields |
| Tests | [x] | Existing tests validated |

**Note:** Tax Codes is read-only at the main level. Sub-endpoints for combines, components, and singles have their own CRUD operations.

#### 12.3 Payment Methods ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/accounts/paymentMethods/ |
| Get endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/accounts/paymentMethods/{paymentMethodID} |
| Create endpoint | [x] | POST /api/v1.0/companies/{companyID}/setup/accounts/paymentMethods/ |
| Update endpoint | [x] | PATCH /api/v1.0/companies/{companyID}/setup/accounts/paymentMethods/{paymentMethodID} |
| Delete endpoint | [x] | DELETE /api/v1.0/companies/{companyID}/setup/accounts/paymentMethods/{paymentMethodID} |
| Response DTO (List) | [x] | PaymentMethodListItem - ID, Name |
| Response DTO (Detail) | [x] | PaymentMethod - ID, Name, AccountNo, Type, FinanceCharge |
| Detailed list support | [x] | listDetailed() added |
| Tests | [x] | Existing tests validated |

#### 12.4 Payment Terms ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/accounts/paymentTerms/ |
| Get endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/accounts/paymentTerms/{paymentTermID} |
| Create endpoint | [x] | POST /api/v1.0/companies/{companyID}/setup/accounts/paymentTerms/ |
| Update endpoint | [x] | PATCH /api/v1.0/companies/{companyID}/setup/accounts/paymentTerms/{paymentTermID} |
| Delete endpoint | [x] | DELETE /api/v1.0/companies/{companyID}/setup/accounts/paymentTerms/{paymentTermID} |
| Response DTO (List) | [x] | PaymentTerm - PaymentTermID, PaymentTermName, Days, Type, IsDefault |
| Response DTO (Detail) | [x] | PaymentTerm (same as list - all fields returned in list) |
| Detailed list support | [x] | Not needed - list and detail responses have same fields |
| Tests | [x] | Existing tests validated |

#### 12.5 Customer Groups ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/customerGroups/ |
| Get endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/customerGroups/{customerGroupID} |
| Create endpoint | [x] | POST /api/v1.0/companies/{companyID}/setup/customerGroups/ |
| Update endpoint | [x] | PATCH /api/v1.0/companies/{companyID}/setup/customerGroups/{customerGroupID} |
| Delete endpoint | [x] | DELETE /api/v1.0/companies/{companyID}/setup/customerGroups/{customerGroupID} |
| Response DTO (List) | [x] | CustomerGroupListItem - ID, Name |
| Response DTO (Detail) | [x] | CustomerGroup - ID, Name, Archived |
| Detailed list support | [x] | listDetailed() added |
| Tests | [x] | Existing tests validated |

#### 12.6 Zones ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| CRUD endpoints | [x] | |
| DTOs | [x] | |
| Detailed list support | [x] | Checked |

#### 12.7 Accounting Categories ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/accounts/accCategories/ |
| Get endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/accounts/accCategories/{accCategoryID} |
| Create endpoint | [x] | POST /api/v1.0/companies/{companyID}/setup/accounts/accCategories/ |
| Update endpoint | [x] | PATCH /api/v1.0/companies/{companyID}/setup/accounts/accCategories/{accCategoryID} |
| Delete endpoint | [x] | DELETE /api/v1.0/companies/{companyID}/setup/accounts/accCategories/{accCategoryID} |
| Response DTO (List) | [x] | AccountingCategoryListItem - ID, Name |
| Response DTO (Detail) | [x] | AccountingCategory - ID, Name, Ref, Archived |
| Detailed list support | [x] | listDetailed() available via columns parameter |
| Tests | [x] | 16 tests covering all endpoints and DTOs |

#### 12.8 Business Groups ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/accounts/businessGroups/ |
| Get endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/accounts/businessGroups/{businessGroupID} |
| Create endpoint | [x] | POST /api/v1.0/companies/{companyID}/setup/accounts/businessGroups/ |
| Update endpoint | [x] | PATCH /api/v1.0/companies/{companyID}/setup/accounts/businessGroups/{businessGroupID} |
| Delete endpoint | [x] | DELETE /api/v1.0/companies/{companyID}/setup/accounts/businessGroups/{businessGroupID} |
| Response DTO (List) | [x] | BusinessGroupListItem - ID, Name |
| Response DTO (Detail) | [x] | BusinessGroup - ID, Name, CostCenters (array of Reference) |
| Detailed list support | [x] | listDetailed() available via columns parameter |
| Tests | [x] | 15 tests covering all endpoints and DTOs |

#### 12.9 Chart of Accounts ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/accounts/chartOfAccounts/ |
| Get endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/accounts/chartOfAccounts/{accountID} |
| Create endpoint | [x] | POST /api/v1.0/companies/{companyID}/setup/accounts/chartOfAccounts/ |
| Update endpoint | [x] | PATCH /api/v1.0/companies/{companyID}/setup/accounts/chartOfAccounts/{accountID} |
| Delete endpoint | [x] | DELETE /api/v1.0/companies/{companyID}/setup/accounts/chartOfAccounts/{accountID} |
| Response DTO (List) | [x] | ChartOfAccountListItem - ID, Name |
| Response DTO (Detail) | [x] | ChartOfAccount - ID, Name, Number, Type, Archived |
| Detailed list support | [x] | listDetailed() added |
| Tests | [x] | Existing tests validated |

#### 12.10 Cost Centers ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/accounts/costCenters/ |
| Get endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/accounts/costCenters/{costCenterID} |
| Create endpoint | [x] | POST /api/v1.0/companies/{companyID}/setup/accounts/costCenters/ |
| Update endpoint | [x] | PATCH /api/v1.0/companies/{companyID}/setup/accounts/costCenters/{costCenterID} |
| Delete endpoint | [x] | DELETE /api/v1.0/companies/{companyID}/setup/accounts/costCenters/{costCenterID} |
| Response DTO (List) | [x] | SetupCostCenterListItem - ID, Name |
| Response DTO (Detail) | [x] | SetupCostCenter - ID, Name, IncomeAccountNo, ExpenseAccountNo, AccrualRevAccountNo, DeferralRevAccountNo, MonthlySalesBudget, MonthlyExpenditureBudget, Archived, IsMembershipCostCenter, Rates |
| Nested Rates DTO | [x] | CostCenterRates - ServiceFee (Reference), LaborRate (Reference) |
| Detailed list support | [x] | listDetailed() added |
| Tests | [x] | Existing tests validated |

#### 12.11 Custom Fields (19 types) ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| Base DTOs | [x] | CustomField (detail), CustomFieldListItem (list) |
| Abstract classes | [x] | AbstractCustomFieldResource, AbstractListDetailedCustomFieldsRequest |
| List schema | [x] | ID, Name, Type, Order, Locked |
| Detail schema | [x] | ID, Name, Type, ListItems, IsMandatory, Order, Archived, Locked |
| Detailed list support | [x] | listDetailed() added to all 19 resource types |
| Tests | [x] | Updated test and fixture to match swagger |

**All 19 Custom Field types share the same schema:**
- [x] Catalog
- [x] Contact
- [x] Contractor
- [x] Contractor Invoice
- [x] Contractor Job
- [x] Customer
- [x] Customer Contact
- [x] Customer Contract
- [x] Employee
- [x] Invoice
- [x] Prebuild
- [x] Project
- [x] Site
- [x] Site Contact
- [x] Task
- [x] Vendor
- [x] Vendor Contact
- [x] Vendor Order
- [x] Work Order

**Major fixes applied:**
- Updated CustomField DTO: removed `customFieldType`, `required`, `options`
- Added correct fields: `listItems` (nullable array), `isMandatory` (bool), `order` (int), `locked` (bool)
- Updated CustomFieldListItem DTO: added `order`, `locked` fields
- Created AbstractListDetailedCustomFieldsRequest with DETAILED_COLUMNS constant
- Added listDetailed() method to AbstractCustomFieldResource
- Created 19 ListDetailed*CustomFieldsRequest classes

#### 12.12 Asset Types (with deep nesting) ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| CRUD endpoints | [x] | GET list/single, POST, PATCH, DELETE |
| Main DTO | [x] | AssetType with Reference, RegType, JobCostCenter, QuoteCostCenter, DefaultTechnician, Description, Archived |
| Detailed list support | [x] | listDetailed() added |
| Folders | [x] | AssetTypeFolder with ParentID, Parent (Reference) |
| Files | [x] | AssetTypeFile with Filename, Folder, MimeType, FileSizeBytes, DateAdded, AddedBy |
| Custom Fields | [x] | Same schema as setup custom fields (ID, Name, Type, ListItems, IsMandatory, Order, Archived, Locked) |
| Service Levels | [x] | ServiceLevel (Reference), DisplayOrder, IsDefault, Prebuild (nested) |
| Failure Points | [x] | DisplayOrder, Standard, Prebuild (nested), IsCritical, Severity |
| Recommendations | [x] | ChargeRate, Prebuild (nested) |
| Test Readings | [x] | ListItems, IsMandatory, ServiceLevels (array), Order, Archived |
| DTOs | [x] | All 14 DTOs updated to match swagger |

**New DTOs created:**
- `AssetTypeReference` - nested Section/Standard object
- `AssetTypePrebuild` - nested Prebuild object (ID, PartNo, Name, AddOnPrice, DisplayOrder, Archived)

**Major fixes applied:**
- AssetType: Added reference, regType, jobCostCenter, quoteCostCenter, defaultTechnician, description
- AssetTypeCustomField/ListItem: Updated to match custom field schema (isMandatory, order, locked, listItems)
- AssetTypeServiceLevel: Changed from flat id/name/archived to ServiceLevel (Reference) + DisplayOrder + IsDefault + Prebuild
- AssetTypeFailurePoint: Added displayOrder, standard, prebuild, isCritical, severity
- AssetTypeTestReading: Added listItems, isMandatory, serviceLevels (array), order
- AssetTypeRecommendation: Added chargeRate, prebuild
- AssetTypeFile: Changed id type to string, added filename, mimeType, fileSizeBytes, dateAdded, addedBy
- AssetTypeFolder: Added parentId, parent (Reference)

#### 12.13 Labor Resources ✅ VERIFIED
- [x] Labor Rates - LaborRate with nested TaxCode, Plant references, listDetailed() added
- [x] Fit Times - FitTime with multiplier, displayOrder, archived, listDetailed() added
- [x] Plant Rates - PlantRate with nested TaxCode, Plant references, listDetailed() added
- [x] Schedule Rates - ScheduleRate with 12 fields matching swagger, listDetailed() added
- [x] Service Fees - ServiceFee with nested SalesTaxCode, listDetailed() added

#### 12.14 Materials Resources ✅ VERIFIED
- [x] Pricing Tiers - PricingTier with ScaledTierPricing nested array, listDetailed() added
- [x] Purchasing Stages - ID, Name, Archived, listDetailed() added
- [x] Stock Take Reasons - List includes Archived, no listDetailed() needed
- [x] Stock Transfer Reasons - List includes Archived, no listDetailed() needed
- [x] UOMs (Units of Measure) - Uom with wholeNoOnly, listDetailed() added

#### 12.15 Other Setup Resources
- [x] Activities ✅ VERIFIED
- [x] Archive Reasons (Quote) ✅ VERIFIED
- [x] Asset Service Levels ✅ VERIFIED
- [x] Basic Commissions ✅ VERIFIED - Fixed fields to match swagger, listDetailed() added
- [x] Advanced Commissions ✅ VERIFIED - Created AdvancedCommissionComponents DTO, listDetailed() added
- [x] Currencies ✅ VERIFIED
- [x] Customer Profiles ✅ VERIFIED
- [x] Customer Tags ✅ VERIFIED
- [x] Project Tags ✅ VERIFIED
- [x] Defaults ✅ VERIFIED
- [x] Response Times ✅ VERIFIED
- [x] Security Groups ✅ VERIFIED
- [x] Status Codes (Project, Customer Invoice, Vendor Order) ✅ VERIFIED
- [x] Task Categories ✅ VERIFIED
- [x] Teams ✅ VERIFIED
- [x] Payment Terms ✅ VERIFIED
- [x] Payment Methods ✅ VERIFIED

#### 12.16 Activities ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/activities/ |
| Get endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/activities/{activityID} |
| Create endpoint | [x] | POST /api/v1.0/companies/{companyID}/setup/activities/ |
| Update endpoint | [x] | PATCH /api/v1.0/companies/{companyID}/setup/activities/{activityID} |
| Delete endpoint | [x] | DELETE /api/v1.0/companies/{companyID}/setup/activities/{activityID} |
| Response DTO (List) | [x] | ActivityListItem - removed incorrect `code` field |
| Response DTO (Detail) | [x] | Activity - replaced `code`/`type` with `billable`, `archived`, `scheduleRate` |
| Detailed list support | [x] | listDetailed() available via columns parameter |
| Tests | [x] | 16 tests covering all endpoints and DTOs |

**Major fixes applied:**
- Removed `code` field from ActivityListItem (not in API)
- Removed `code` and `type` fields from Activity (not in API)
- Added `billable` (bool), `archived` (bool), `scheduleRate` (Reference) to Activity
- Created ListDetailedActivitiesRequest for bulk fetching full Activity DTOs
- Added listDetailed() method to ActivityResource

---

#### 12.17 Response Times ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/responseTimes/ |
| Get endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/responseTimes/{responseTimeID} |
| Create endpoint | [x] | POST /api/v1.0/companies/{companyID}/setup/responseTimes/ |
| Update endpoint | [x] | PATCH /api/v1.0/companies/{companyID}/setup/responseTimes/{responseTimeID} |
| Delete endpoint | [x] | DELETE /api/v1.0/companies/{companyID}/setup/responseTimes/{responseTimeID} |
| Response DTO (List) | [x] | ResponseTimeListItem - added `archived` field |
| Response DTO (Detail) | [x] | ResponseTime - added `days`, `includeWeekends`, `archived`; fixed `hours`/`minutes` nullability |
| Detailed list support | [x] | listDetailed() available via columns parameter |
| Tests | [x] | 16 tests covering all endpoints and DTOs |

#### 12.18 Defaults ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| Get endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/defaults/ |
| Response DTO | [x] | Complete rewrite to match swagger nested structure |
| Tests | [x] | 10 tests covering all nested objects |

**Major fixes applied:**
- Complete rewrite of Defaults DTO - original had 4 incorrect fields
- Created 11 new nested DTOs in `src/Data/Setup/Defaults/`:
  - `DefaultsSystem` - top-level System object
  - `DefaultsGeneral` - dateFormat, timeFormat, thousandsSeparator, negativeNumberFormat
  - `DefaultsJobsQuotes` - defaultCostCenter (Reference), singleCostCenter
  - `DefaultsJobs` - warrantyCostCenter (Reference), mandatoryDueDateOnCreation
  - `DefaultsMandatoryDueDate` - serviceJob, projectJob booleans
  - `DefaultsQuotes` - mandatoryDueDateOnCreation
  - `DefaultsQuotesMandatoryDueDate` - serviceQuote, projectQuote booleans
  - `DefaultsFinancial` - top-level Financial object
  - `DefaultsAccounts` - 10 account string fields
  - `DefaultsInvoicing` - showSellCostPrices, financeChargeLabel, tracking, retainageHold
  - `DefaultsSchedule` - workWeekStart, scheduleFormat

#### 12.19 Task Categories ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/tasks/categories/ |
| Get endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/tasks/categories/{taskCategoryID} |
| Create endpoint | [x] | POST /api/v1.0/companies/{companyID}/setup/tasks/categories/ |
| Update endpoint | [x] | PATCH /api/v1.0/companies/{companyID}/setup/tasks/categories/{taskCategoryID} |
| Delete endpoint | [x] | DELETE /api/v1.0/companies/{companyID}/setup/tasks/categories/{taskCategoryID} |
| Response DTO (List) | [x] | TaskCategoryListItem - ID, Name |
| Response DTO (Detail) | [x] | TaskCategory - ID, Name, Archived |
| Detailed list support | [x] | listDetailed() added |
| Tests | [x] | Existing tests validated |

**Major fixes applied:**
- Fixed `$iD` to `$id` property naming convention
- Removed incorrect `color` field from TaskCategory (not in API)
- Added `archived` (bool) to TaskCategory
- Created ListDetailedTaskCategoriesRequest for bulk fetching full TaskCategory DTOs
- Added listDetailed() method to TaskCategoryResource

#### 12.20 Quote Archive Reasons ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/archiveReasons/quotes/ |
| Get endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/archiveReasons/quotes/{archiveReasonID} |
| Create endpoint | [x] | POST /api/v1.0/companies/{companyID}/setup/archiveReasons/quotes/ |
| Update endpoint | [x] | PATCH /api/v1.0/companies/{companyID}/setup/archiveReasons/quotes/{archiveReasonID} |
| Delete endpoint | [x] | DELETE /api/v1.0/companies/{companyID}/setup/archiveReasons/quotes/{archiveReasonID} |
| Response DTO (List) | [x] | QuoteArchiveReasonListItem - ID, ArchiveReason |
| Response DTO (Detail) | [x] | QuoteArchiveReason - ID, ArchiveReason, DisplayOrder, Archived |
| Detailed list support | [x] | listDetailed() added |
| Tests | [x] | Existing tests validated |

**Major fixes applied:**
- Fixed `$iD` to `$id` property naming convention
- Renamed `name` to `archiveReason` to match API
- Removed incorrect `description`, `isActive` fields (not in API)
- Added `displayOrder` (int), `archived` (bool) to QuoteArchiveReason
- Created ListDetailedQuoteArchiveReasonsRequest for bulk fetching full QuoteArchiveReason DTOs
- Added listDetailed() method to QuoteArchiveReasonResource

#### 12.21 Asset Service Levels ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/assets/serviceLevels/ |
| Get endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/assets/serviceLevels/{serviceLevelID} |
| Create endpoint | [x] | POST /api/v1.0/companies/{companyID}/setup/assets/serviceLevels/ |
| Update endpoint | [x] | PATCH /api/v1.0/companies/{companyID}/setup/assets/serviceLevels/{serviceLevelID} |
| Delete endpoint | [x] | DELETE /api/v1.0/companies/{companyID}/setup/assets/serviceLevels/{serviceLevelID} |
| Response DTO (List) | [x] | AssetServiceLevelListItem - ID, Name |
| Response DTO (Detail) | [x] | AssetServiceLevel - ID, Name, Years, Months, Days, Archived |
| Detailed list support | [x] | listDetailed() added |
| Tests | [x] | Existing tests validated |

**Major fixes applied:**
- Fixed `$iD` to `$id` property naming convention
- Removed incorrect `description`, `priority` fields (not in API)
- Added `years` (int), `months` (int), `days` (int), `archived` (bool) to AssetServiceLevel
- Created ListDetailedAssetServiceLevelsRequest for bulk fetching full AssetServiceLevel DTOs
- Added listDetailed() method to AssetServiceLevelResource

#### 12.22 Customer Profiles ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/customerProfiles/ |
| Get endpoint | [x] | GET /api/v1.0/companies/{companyID}/setup/customerProfiles/{customerProfileID} |
| Create endpoint | [x] | POST /api/v1.0/companies/{companyID}/setup/customerProfiles/ |
| Update endpoint | [x] | PATCH /api/v1.0/companies/{companyID}/setup/customerProfiles/{customerProfileID} |
| Delete endpoint | [x] | DELETE /api/v1.0/companies/{companyID}/setup/customerProfiles/{customerProfileID} |
| Response DTO (List) | [x] | CustomerProfileListItem - ID, Name |
| Response DTO (Detail) | [x] | CustomerProfile - ID, Name, Archived |
| Detailed list support | [x] | listDetailed() added |
| Tests | [x] | Existing tests validated |

**Major fixes applied:**
- Fixed `$iD` to `$id` property naming convention
- Removed incorrect `description`, `isDefault` fields (not in API)
- Added `archived` (bool) to CustomerProfile
- Created ListDetailedCustomerProfilesRequest for bulk fetching full CustomerProfile DTOs
- Added listDetailed() method to CustomerProfileResource

---

## Common DTOs

Verify these shared DTOs are accurate across all usages:

| DTO | Status | Notes |
|-----|--------|-------|
| `Money.php` | [ ] | ExTax, Tax, IncTax, TaxCode |
| `Reference.php` | [ ] | ID, Name pairs |
| `Address.php` | [ ] | Street, City, State, Postcode, Country |
| `CustomField.php` | [ ] | Generic custom field structure |
| `Attachment.php` | [ ] | Context-specific fields (public, email, default, base64Data) |
| `Note.php` | [ ] | Subject, note, dates, references |
| `StaffReference.php` | [ ] | Employee/Contractor/Plant references |
| `TaxCode.php` | [ ] | ID, code, type, rate |
| `NoteReference.php` | [ ] | Reference info for notes |
| `NoteAttachment.php` | [ ] | Simplified attachment for notes |

---

## Progress Tracking

| Resource           | Verified | Issues Found | Issues Resolved |
|--------------------|----------|--------------|-----------------|
| Info               | [x]      | 0            | 0               |
| Companies          | [x]      | 0            | 0               |
| Current User       | [x]      | 7            | 7               |
| Activity Schedules | [x]      | 0            | 0               |
| Schedules          | [x]      | 0            | 0               |
| Jobs               | [x]      | 12           | 12              |
| Customers          | [x]      | 8            | 8               |
| Quotes             | [x]      | 25+          | 25+             |
| Invoices           | [x]      | 15+          | 15+             |
| Employees          | [ ]      | 0            | 0               |
| Reports            | [ ]      | 0            | 0               |
| Setup (all sub-resources) | [x] | 71        | 71              |
| - Currencies       | [x]      | 0            | 0               |
| - Zones            | [x]      | 0            | 0               |
| - Teams            | [x]      | 0            | 0               |
| - Security Groups  | [x]      | 2            | 2               |
| - Response Times   | [x]      | 4            | 4               |
| - Activities       | [x]      | 3            | 3               |
| - Defaults         | [x]      | 1            | 1               |
| - Accounting Categories | [x] | 1            | 1               |
| - Business Groups  | [x]      | 1            | 1               |
| - Customer Groups  | [x]      | 0            | 0               |
| - Task Categories  | [x]      | 2            | 2               |
| - Payment Terms    | [x]      | 0            | 0               |
| - Payment Methods  | [x]      | 0            | 0               |
| - Chart of Accounts | [x]     | 0            | 0               |
| - Quote Archive Reasons | [x] | 4            | 4               |
| - Asset Service Levels | [x]  | 4            | 4               |
| - Customer Profiles | [x]     | 3            | 3               |
| - Webhooks         | [x]      | 0            | 0               |
| - Tax Codes        | [x]      | 0            | 0               |
| - Cost Centers     | [x]      | 0            | 0               |
| - Status Codes (3) | [x]      | 6            | 6               |
| - Tags (2)         | [x]      | 4            | 4               |
| - Commissions (2)  | [x]      | 5            | 5               |
| - Labor (5)        | [x]      | 10           | 10              |
| - Materials (5)    | [x]      | 4            | 4               |
| - Custom Fields (19) | [x]    | 3            | 3               |
| - Asset Types (8)  | [x]      | 14           | 14              |
| Common DTOs        | [ ]      | 0            | 0               |

---

## Notes

- When verifying, use `grep` on `swagger 2.json` to find exact schema definitions
- Example: `grep -A 50 '"/api/v1.0/companies/{companyID}/jobs/"' "swagger 2.json"`
- Pay special attention to:
  - Field names (camelCase vs PascalCase)
  - Nullable fields
  - Nested object structures
  - Array vs single object responses
  - Whether the list endpoint supports `columns` parameter for detailed responses
- For detailed list support:
  - Check if list endpoint has `columns` query parameter in swagger
  - Compare default list response fields vs detail GET response fields
  - If different, implement `listDetailed()` method that requests all columns
