# Codebase History

## Overview

This Simpro PHP SDK was initially created using the [Uptick SDK](https://github.com/stitch-digital/uptick-php-sdk) as a structural template. The Uptick SDK provided a solid foundation built on Saloon v3, which was adapted and extended to work with the Simpro API.

This document tracks the evolution of the codebase, clearly identifying which files originated from the Uptick SDK template, which were created specifically for Simpro, and the changes made during the dual authentication refactor.

## Files from Uptick SDK Template

The following files and structure originated from the Uptick SDK and served as the foundation:

### Core Structure
- **src/Simpro.php** - Main connector class (originally `Uptick.php`)
  - Template provided the basic connector structure with Saloon traits
  - Template handled OAuth password grant authentication
  - Adapted constructor and configuration for Simpro API
  
- **src/SimproAuthenticator.php** - Authentication handler (originally `UptickAuthenticator.php`)
  - Template provided token storage and expiration checking
  - Minimal changes needed for Simpro compatibility
  
- **src/Paginators/SimproPaginator.php** - Pagination handler (originally `UptickPaginator.php`)
  - Template provided basic PagedPaginator structure
  - Adapted to use Simpro's header-based pagination (`Result-Total`, `Result-Pages`)
  - Modified default page size from Uptick's values to Simpro's 30 items per page

### Supporting Infrastructure
- **src/Exceptions/ValidationException.php**
  - Template provided custom 422 validation error handling
  - Used as-is with namespace changes only

- **src/Concerns/SupportsClientsEndpoints.php** (originally `SupportsContactsEndpoints.php`)
  - Template pattern for organizing resource endpoints
  - Renamed and adapted for Simpro's "clients" terminology

- **tests/TestCase.php**
  - Template provided basic Pest test configuration
  - Used as-is with namespace updates

- **tests/Pest.php**
  - Template provided Pest test configuration
  - Used as-is with namespace updates

### Configuration Files
- **composer.json** - Package configuration
  - Template structure maintained
  - Updated package name, description, and Simpro-specific details
  - Dependencies remained largely the same (Saloon, Pest, PHPStan, Pint)

- **phpstan.neon** - PHPStan configuration
  - Used template configuration as-is

- **phpunit.xml.dist** - PHPUnit configuration
  - Used template configuration with namespace updates

- **pint.json** - Laravel Pint configuration
  - Used template configuration as-is

- **.gitignore** - Git ignore rules
  - Used template configuration as-is

## Simpro-Specific Implementation

The following files were created specifically for the Simpro SDK:

### Domain Models & Data Transfer Objects
- **src/Data/Clients/Client.php**
  - Simpro's client entity DTO
  - Maps Simpro API response structure to typed PHP objects

- **src/Data/Clients/ClientAttributes.php**
  - Comprehensive client attributes with 40+ properties
  - Includes Simpro-specific fields like billing settings, sector, price tiers

- **src/Data/Clients/ClientListResponse.php**
  - Response wrapper for paginated client lists
  - Includes Simpro-specific pagination metadata structure

- **src/Data/Clients/Sector.php**
  - Backed enum for Simpro's industry sectors
  - 18 predefined sectors (Accommodation, Agriculture, Construction, etc.)

- **src/Data/PaginationLinks.php**
  - Simpro pagination links structure (self, first, last)

- **src/Data/PaginationMeta.php**
  - Simpro pagination metadata (total, count, per_page, etc.)

### API Requests
- **src/Requests/Clients/ListClientsRequest.php**
  - Request class for listing/filtering clients
  - Implements Simpro's limit/offset pagination scheme
  - Returns `ClientListResponse` DTO

- **src/Requests/Auth/GetAccessTokenRequest.php**
  - OAuth password grant token request
  - Adapted for Simpro's OAuth2 endpoints

- **src/Requests/Auth/RefreshAccessTokenRequest.php**
  - OAuth token refresh request
  - Adapted for Simpro's OAuth2 token refresh flow

### Resources
- **src/Resources/ClientResource.php**
  - Comprehensive client resource with 30+ methods
  - Simpro-specific filtering by sector, billing settings, price tiers, etc.
  - Provides fluent API for client operations

### Tests
- **tests/Data/Clients/SectorTest.php**
  - Tests for Sector enum values and cases

- **tests/Requests/Auth/GetAccessTokenRequestTest.php**
  - Tests for OAuth token acquisition

- **tests/Requests/Auth/RefreshAccessTokenRequestTest.php**
  - Tests for OAuth token refresh

- **tests/Requests/Clients/ListClientsRequestTest.php**
  - Tests for client listing and pagination

- **tests/Sdk/ClientsTest.php**
  - Integration tests for client resource methods

### Test Fixtures
- **tests/Fixtures/Saloon/get_access_token.json**
  - Mock response for OAuth token request

- **tests/Fixtures/Saloon/refresh_access_token.json**
  - Mock response for token refresh

- **tests/Fixtures/Saloon/list_clients.json**
  - Mock response for client listing with Simpro structure

### Documentation
- **README.md**
  - Completely rewritten for Simpro SDK
  - Comprehensive documentation of authentication, resources, pagination
  - Examples specific to Simpro API usage

- **CHANGELOG.md**
  - Simpro-specific release history

## Modified Template Files

Files from the Uptick template that required significant customization:

### src/Simpro.php
**Changes:**
- Renamed from `Uptick.php`
- Constructor parameters changed to match Simpro authentication (username/password instead of API key)
- Base URL resolution adapted for Simpro API structure
- Uses Simpro-specific endpoints (`/api/v2/` paths)
- OAuth configuration for Simpro's OAuth2 implementation
- Integrated `SupportsClientsEndpoints` concern (vs Uptick's contacts)

### src/Paginators/SimproPaginator.php
**Changes:**
- Adapted to read Simpro-specific response headers (`Result-Total`, `Result-Pages`)
- Changed default per-page limit from Uptick's value to 30 (Simpro's default)
- Query parameter structure adjusted for Simpro's `page` and `pageSize` format
- Returns Simpro DTOs instead of Uptick DTOs

### src/Concerns/SupportsClientsEndpoints.php
**Changes:**
- Renamed from `SupportsContactsEndpoints` 
- Changed to return `ClientResource` instead of Uptick's contact resource
- Terminology changed from "contacts" to "clients" throughout

## Current Refactor: Dual Authentication Support

This refactor adds support for both OAuth (Authorization Code Grant) and API Key authentication by introducing a cleaner architecture with separate connectors. The old password grant implementation was removed since this package is in early development with no releases yet.

### New Files Added

#### Connectors
- **src/Connectors/AbstractSimproConnector.php** (NEW)
  - Abstract base connector with shared logic
  - Contains common Saloon traits, pagination, error handling
  - Base URL resolution and timeout configuration
  - Allows both OAuth and API Key connectors to share core functionality

- **src/Connectors/SimproOAuthConnector.php** (NEW)
  - OAuth Authorization Code Grant implementation
  - Uses Saloon's `AuthorizationCodeGrant` trait
  - Handles authorization URL generation, token exchange, refresh
  - Suitable for web applications with callback URLs

- **src/Connectors/SimproApiKeyConnector.php** (NEW)
  - Simple API Key authentication using `TokenAuthenticator`
  - Lightweight connector without OAuth complexity
  - Suitable for server-to-server integrations

#### Tests
- **tests/Connectors/AbstractSimproConnectorTest.php** (NEW)
  - Tests for shared base connector functionality

- **tests/Connectors/SimproOAuthConnectorTest.php** (NEW)
  - Tests for OAuth authorization flow, token exchange, refresh

- **tests/Connectors/SimproApiKeyConnectorTest.php** (NEW)
  - Tests for API Key authentication

### Files Modified

1. `src/Paginators/SimproPaginator.php` - Fixed DTO handling
2. `README.md` - Added dual authentication documentation  
3. `tests/TestCase.php` - Updated to use SimproApiKeyConnector
4. `tests/Sdk/ClientsTest.php` - Removed OAuth password grant references

### Files Removed

Since this package is in early development with no releases, the deprecated password grant implementation was completely removed:

1. `src/Simpro.php` - Old connector with password grant (REMOVED)
2. `src/SimproAuthenticator.php` - Password grant authenticator (REMOVED)
3. `src/Requests/Auth/GetAccessTokenRequest.php` - Password grant token request (REMOVED)
4. `src/Requests/Auth/RefreshAccessTokenRequest.php` - Password grant refresh (REMOVED)
5. `tests/Requests/Auth/GetAccessTokenRequestTest.php` - Password grant tests (REMOVED)
6. `tests/Requests/Auth/RefreshAccessTokenRequestTest.php` - Password grant tests (REMOVED)
7. `tests/Fixtures/Saloon/get_access_token.json` - Password grant fixture (REMOVED)
8. `tests/Fixtures/Saloon/refresh_access_token.json` - Password grant fixture (REMOVED)

## Design Philosophy

### Template Reuse
The Uptick SDK template provided an excellent foundation because:
1. **Proven Structure** - Already implements best practices with Saloon v3
2. **Complete Testing** - PHPStan, Pest, and fixture-based testing setup
3. **Quality Tooling** - Pint for formatting, comprehensive CI/CD patterns
4. **Pagination Handling** - Reusable paginator pattern with minor adaptations

### Simpro Customization
Key areas requiring Simpro-specific implementation:
1. **Domain Models** - Client structure is fundamentally different from Uptick contacts
2. **Authentication** - Simpro supports both OAuth and API Keys (vs Uptick's OAuth only)
3. **Filtering** - 30+ client filtering methods specific to Simpro's capabilities
4. **Pagination Metadata** - Different header structure and response format

### Refactor Goals
The dual authentication refactor aims to:
1. **Separate Concerns** - Distinct connectors for OAuth vs API Key authentication
2. **Clean Codebase** - Removed deprecated password grant implementation
3. **Share Logic** - Abstract base connector eliminates duplication
4. **Enable Growth** - Easy to add future features like rate limiting, caching per connector type

## Maintenance Guidelines

### When Adding New Features
- **Core functionality** → Add to `AbstractSimproConnector`
- **OAuth-specific** → Add to `SimproOAuthConnector`
- **API Key-specific** → Add to `SimproApiKeyConnector`
- **Domain models** → Add to `src/Data/` with appropriate DTOs
- **Resources** → Add to `src/Resources/` and wire into base connector via Concerns

### When Updating Tests
- **Shared logic** → Update `AbstractSimproConnectorTest.php`
- **Authentication** → Update connector-specific test files
- **API responses** → Add fixtures to `tests/Fixtures/Saloon/`
- **Integration** → Add to appropriate resource test file

### Backward Compatibility
Since this package is in early development with no releases yet, breaking changes were acceptable. The password grant OAuth implementation was completely removed in favor of the cleaner Authorization Code Grant and API Key approaches.

## Version History

### v0.x (Pre-1.0) - Initial Development
- Created from Uptick SDK template
- Implemented Simpro-specific domain models
- Built out client resource with comprehensive filtering
- Initial OAuth password grant authentication (later removed)

### v0.x (Current) - Dual Authentication Refactor
- Introduced connector architecture
- Added OAuth Authorization Code Grant support
- Added API Key authentication support
- Removed password grant implementation (pre-release, breaking change acceptable)
- All existing functionality maintained through new connectors

### v1.0 (Planned) - Stable Release
- Finalize connector API
- Complete API coverage beyond clients
- Production-ready release
