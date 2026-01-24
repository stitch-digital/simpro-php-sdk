# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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
