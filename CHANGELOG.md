# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- `BulkResponseItem::errors()`, `errorMessage()` and `isLocked()`. A failed item's body is nested in the 200 envelope as a JSON *string*, so callers that read `$item->body` directly saw a string and silently fell back to reporting the bare status code — losing both the real message (e.g. `"/ProjectManager: Must be an integer"`) and the ability to tell a retryable lock apart from a payload validation failure. An already-decoded array body is still accepted.

## [1.1.17] - 2026-07-09

### Fixed
- `CreateAttachmentFileRequest::createDtoFromResponse()` now throws `RuntimeException` when the Simpro response omits `ID`, returns a non-numeric value, or returns a non-positive integer. Previously the missing/invalid case silently returned `0`, which surfaced downstream as a bogus `simpro_attachment_id` and 404s on later DELETE calls.
- Fixed `MobileSignature` DTO to match actual API response structure (`Technician` and `Client` fields)

### Changed
- Refactored package from Uptick to Simpro
- Updated package name to `stitch-digital/simpro-php-sdk`
- Changed namespace from `Uptick\PhpSdk\Uptick` to `Simpro\PhpSdk\Simpro`
- Renamed main classes: `Uptick` → `Simpro`, `UptickAuthenticator` → `SimproAuthenticator`, `UptickPaginator` → `SimproPaginator`
- Updated documentation and README for Simpro branding

### Added
- Initial release
- OAuth2 authentication with automatic token refresh
- Client listing with pagination support
- Framework-agnostic design with constructor injection
