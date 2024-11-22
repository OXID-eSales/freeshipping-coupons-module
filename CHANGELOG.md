# Change Log for OXID eShop Freeshipping Coupons Module

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [v1.0.0] - Unreleased

### Added
- Update PhpUnit version to 11.4

## [v1.0.0-rc.1] - 2024-11-08

### Added
- New ``shipfree`` option in the Admin Coupon Series settings, enabling the creation of free shipping coupons.
- Frontend display for zero-value ``shipfree`` vouchers in the cart and checkout steps.
- Informational error message in cart when a ``shipfree`` voucher is applied without delivery fees, warning users of potential voucher waste.
- Designed for OXID eShop 7.2.x

