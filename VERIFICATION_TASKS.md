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
| Response DTO | [x] | |
| Tests | [x] | |

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

### 6. Jobs Resource
**Files:** `src/Resources/JobResource.php`, `src/Resources/Jobs/`, `src/Data/Jobs/`

| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [ ] | GET /api/v1.0/companies/{companyID}/jobs/ |
| Get endpoint | [ ] | GET /api/v1.0/companies/{companyID}/jobs/{jobID} |
| Create endpoint | [ ] | POST /api/v1.0/companies/{companyID}/jobs/ |
| Update endpoint | [ ] | PATCH /api/v1.0/companies/{companyID}/jobs/{jobID} |
| Delete endpoint | [ ] | DELETE /api/v1.0/companies/{companyID}/jobs/{jobID} |
| Response DTO (List) | [ ] | |
| Response DTO (Detail) | [ ] | |
| Request body DTO | [ ] | |
| Tests | [ ] | |

#### 6.1 Job Sections
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [ ] | |
| Get endpoint | [ ] | |
| Create endpoint | [ ] | |
| Update endpoint | [ ] | |
| Delete endpoint | [ ] | |
| DTOs | [ ] | |

#### 6.2 Job Cost Centers
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [ ] | |
| Get endpoint | [ ] | |
| Create endpoint | [ ] | |
| Update endpoint | [ ] | |
| Delete endpoint | [ ] | |
| DTOs | [ ] | |

#### 6.3 Job Cost Center Sub-resources
- [ ] Assets
- [ ] Labor
- [ ] Service Fees
- [ ] Contractor Jobs
- [ ] Prebuilds
- [ ] Schedules
- [ ] Stock
- [ ] One-Offs
- [ ] Work Orders
- [ ] Tasks
- [ ] Lock

#### 6.4 Job Attachments
- [ ] Files (List, Get, Create, Update, Delete)
- [ ] Folders (List, Get, Create, Update, Delete)

#### 6.5 Job Custom Fields
- [ ] List, Get, Update

#### 6.6 Job Notes
- [ ] List, Get, Create, Update, Delete

#### 6.7 Job Lock
- [ ] Get, Update

#### 6.8 Job Tasks
- [ ] List, Get

#### 6.9 Job Timelines
- [ ] List

---

### 7. Customers Resource
**Files:** `src/Resources/CustomerResource.php`, `src/Resources/Customers/`, `src/Data/Customers/`

| Check | Status | Notes |
|-------|--------|-------|
| General List | [ ] | GET /api/v1.0/companies/{companyID}/customers/ |
| Tests | [ ] | |

#### 7.1 Customer Companies
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [ ] | GET /api/v1.0/companies/{companyID}/customers/companies/ |
| Get endpoint | [ ] | GET /api/v1.0/companies/{companyID}/customers/companies/{customerID} |
| Create endpoint | [ ] | POST /api/v1.0/companies/{companyID}/customers/companies/ |
| Update endpoint | [ ] | PATCH /api/v1.0/companies/{companyID}/customers/companies/{customerID} |
| Delete endpoint | [ ] | DELETE /api/v1.0/companies/{companyID}/customers/companies/{customerID} |
| Response DTO (List) | [ ] | |
| Response DTO (Detail) | [ ] | |
| Request body DTO | [ ] | |

#### 7.2 Customer Individuals
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [ ] | |
| Get endpoint | [ ] | |
| Create endpoint | [ ] | |
| Update endpoint | [ ] | |
| Delete endpoint | [ ] | |
| DTOs | [ ] | |

#### 7.3 Customer Contacts
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [ ] | |
| Get endpoint | [ ] | |
| Create endpoint | [ ] | |
| Update endpoint | [ ] | |
| Delete endpoint | [ ] | |
| Custom Fields | [ ] | |
| DTOs | [ ] | |

#### 7.4 Customer Contracts
| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [ ] | |
| Get endpoint | [ ] | |
| Create endpoint | [ ] | |
| Update endpoint | [ ] | |
| Delete endpoint | [ ] | |
| Inflation | [ ] | |
| Labor Rates | [ ] | |
| Service Levels | [ ] | |
| Custom Fields | [ ] | |
| DTOs | [ ] | |

---

### 8. Quotes Resource
**Files:** `src/Resources/QuoteResource.php`, `src/Data/Quotes/`

| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [ ] | GET /api/v1.0/companies/{companyID}/quotes/ |
| Get endpoint | [ ] | GET /api/v1.0/companies/{companyID}/quotes/{quoteID} |
| Create endpoint | [ ] | POST /api/v1.0/companies/{companyID}/quotes/ |
| Update endpoint | [ ] | PATCH /api/v1.0/companies/{companyID}/quotes/{quoteID} |
| Delete endpoint | [ ] | DELETE /api/v1.0/companies/{companyID}/quotes/{quoteID} |
| Response DTO (List) | [ ] | |
| Response DTO (Detail) | [ ] | |
| Request body DTO | [ ] | |
| Tests | [ ] | |

---

### 9. Invoices Resource
**Files:** `src/Resources/InvoiceResource.php`, `src/Data/Invoices/`

| Check | Status | Notes |
|-------|--------|-------|
| List endpoint | [ ] | GET /api/v1.0/companies/{companyID}/accounts/receivable/invoices/ |
| Get endpoint | [ ] | GET /api/v1.0/companies/{companyID}/accounts/receivable/invoices/{invoiceID} |
| Create endpoint | [ ] | POST |
| Update endpoint | [ ] | PATCH |
| Delete endpoint | [ ] | DELETE |
| Response DTO (List) | [ ] | |
| Response DTO (Detail) | [ ] | |
| Request body DTO | [ ] | |
| Tests | [ ] | |

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

### 12. Setup Resource
**Files:** `src/Resources/SetupResource.php`, `src/Resources/Setup/`, `src/Data/Setup/`

#### 12.1 Webhooks
| Check | Status | Notes |
|-------|--------|-------|
| CRUD endpoints | [ ] | |
| DTOs | [ ] | |

#### 12.2 Tax Codes
| Check | Status | Notes |
|-------|--------|-------|
| CRUD endpoints | [ ] | |
| DTOs | [ ] | |

#### 12.3 Payment Methods
| Check | Status | Notes |
|-------|--------|-------|
| CRUD endpoints | [ ] | |
| DTOs | [ ] | |

#### 12.4 Payment Terms
| Check | Status | Notes |
|-------|--------|-------|
| CRUD endpoints | [ ] | |
| DTOs | [ ] | |

#### 12.5 Customer Groups
| Check | Status | Notes |
|-------|--------|-------|
| CRUD endpoints | [ ] | |
| DTOs | [ ] | |

#### 12.6 Zones ✅ VERIFIED
| Check | Status | Notes |
|-------|--------|-------|
| CRUD endpoints | [x] | |
| DTOs | [x] | |
| Detailed list support | [x] | Checked |

#### 12.7 Accounting Categories
| Check | Status | Notes |
|-------|--------|-------|
| CRUD endpoints | [ ] | |
| DTOs | [ ] | |

#### 12.8 Business Groups
| Check | Status | Notes |
|-------|--------|-------|
| CRUD endpoints | [ ] | |
| DTOs | [ ] | |

#### 12.9 Chart of Accounts
| Check | Status | Notes |
|-------|--------|-------|
| CRUD endpoints | [ ] | |
| DTOs | [ ] | |

#### 12.10 Cost Centers
| Check | Status | Notes |
|-------|--------|-------|
| CRUD endpoints | [ ] | |
| DTOs | [ ] | |

#### 12.11 Custom Fields (19 types)
- [ ] Catalog
- [ ] Contact
- [ ] Contractor
- [ ] Contractor Invoice
- [ ] Contractor Job
- [ ] Customer
- [ ] Customer Contact
- [ ] Customer Contract
- [ ] Employee
- [ ] Invoice
- [ ] Prebuild
- [ ] Project
- [ ] Site
- [ ] Site Contact
- [ ] Task
- [ ] Vendor
- [ ] Vendor Contact
- [ ] Vendor Order
- [ ] Work Order

#### 12.12 Asset Types (with deep nesting)
| Check | Status | Notes |
|-------|--------|-------|
| CRUD endpoints | [ ] | |
| Folders | [ ] | |
| Files | [ ] | |
| Custom Fields | [ ] | |
| Failure Points | [ ] | |
| Recommendations | [ ] | |
| Service Levels | [ ] | |
| Test Readings | [ ] | |
| DTOs | [ ] | |

#### 12.13 Labor Resources
- [ ] Labor Rates
- [ ] Fit Times
- [ ] Plant Rates
- [ ] Schedule Rates
- [ ] Service Fees

#### 12.14 Materials Resources
- [ ] Pricing Tiers
- [ ] Purchasing Stages
- [ ] Stock Take Reasons
- [ ] Stock Transfer Reasons
- [ ] UOMs (Units of Measure)

#### 12.15 Other Setup Resources
- [ ] Activities
- [ ] Archive Reasons (Quote)
- [ ] Asset Service Levels
- [ ] Basic Commissions
- [ ] Advanced Commissions
- [x] Currencies ✅ VERIFIED
- [ ] Customer Profiles
- [ ] Customer Tags
- [ ] Project Tags
- [ ] Defaults
- [ ] Response Times
- [ ] Security Groups
- [ ] Status Codes (Project, Customer Invoice, Vendor Order)
- [ ] Task Categories
- [x] Teams ✅ VERIFIED

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
| Current User       | [x]      | 0            | 0               |
| Activity Schedules | [x]      | 0            | 0               |
| Schedules          | [x]      | 0            | 0               |
| Jobs               | [ ]      | 0            | 0               |
| Customers          | [ ]      | 0            | 0               |
| Quotes             | [ ]      | 0            | 0               |
| Invoices           | [ ]      | 0            | 0               |
| Employees          | [ ]      | 0            | 0               |
| Reports            | [ ]      | 0            | 0               |
| Setup (partial)    | [ ]      | 0            | 0               |
| - Currencies       | [x]      | 0            | 0               |
| - Zones            | [x]      | 0            | 0               |
| - Teams            | [x]      | 0            | 0               |
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
