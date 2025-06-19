# Change Log for OXID eShop Freeshipping Coupons Module

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Undecided] - unreleased

### Changed
- Update module for Shop 8.0.x

## [Undecided] - unreleased

### Changed
- Update module for Shop 7.4.x

## [v1.1.0] - 2025-06-11
This is the stable release for v1.1.0. No changes have been made since v1.1.0-rc.2.

## [v1.1.0-rc.2] - 2025-05-13

### Fixed
- Corrected license in `composer.json`: was mistakenly declared as `GPL-3.0`, now set to `proprietary`.

## [v1.1.0-rc.1] - 2025-04-24

### Added
- Update PhpUnit version to 11.4
- Support for PHP 8.4.
- OXID SDK recipe for development setup

## [v1.0.0] - 2024-11-27

### Changed
- Updated and improved setup instructions in README.md

## [v1.0.0-rc.1] - 2024-11-08

### Added
- New ``shipfree`` option in the Admin Coupon Series settings, enabling the creation of free shipping coupons.
- Frontend display for zero-value ``shipfree`` vouchers in the cart and checkout steps.
- Informational error message in cart when a ``shipfree`` voucher is applied without delivery fees, warning users of potential voucher waste.
- Designed for OXID eShop 7.2.x

[1.1.0]: https://github.com/OXID-eSales/freeshipping-coupons-module/compare/v1.1.0-rc.2...v1.1.0
[1.1.0-rc.2]: https://github.com/OXID-eSales/freeshipping-coupons-module/compare/v1.1.0-rc.1...v1.1.0-rc.2
[1.1.0-rc.1]: https://github.com/OXID-eSales/freeshipping-coupons-module/compare/v1.0.0...v1.1.0-rc.1
[1.0.0]: https://github.com/OXID-eSales/freeshipping-coupons-module/compare/v1.0.0-rc.1...v1.0.0
[1.0.0-rc.1]: https://github.com/OXID-eSales/freeshipping-coupons-module/releases/tag/v1.0.0-rc.1
