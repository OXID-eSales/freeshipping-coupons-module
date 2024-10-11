# OXID Free Shipping Coupons Module

[![Development](https://github.com/OXID-eSales/freeshipping-coupons-module/actions/workflows/trigger.yaml/badge.svg?branch=b-7.2.x)](https://github.com/OXID-eSales/freeshipping-coupons-module/actions/workflows/trigger.yaml)
[![Latest Version](https://img.shields.io/packagist/v/OXID-eSales/freeshipping-coupons-module?logo=composer&label=latest&include_prereleases&color=orange)](https://packagist.org/packages/oxid-esales/freeshipping-coupons-module)
[![PHP Version](https://img.shields.io/packagist/php-v/oxid-esales/freeshipping-coupons-module)](https://github.com/oxid-esales/freeshipping-coupons-module)

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=OXID-eSales_freeshipping-coupons-module&metric=alert_status&token=0026d27eda3483728f0985d44d32714927ad2f3d)](https://sonarcloud.io/dashboard?id=OXID-eSales_freeshipping-coupons-module)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=OXID-eSales_freeshipping-coupons-module&metric=coverage&token=0026d27eda3483728f0985d44d32714927ad2f3d)](https://sonarcloud.io/dashboard?id=OXID-eSales_freeshipping-coupons-module)
[![Technical Debt](https://sonarcloud.io/api/project_badges/measure?project=OXID-eSales_freeshipping-coupons-module&metric=sqale_index&token=0026d27eda3483728f0985d44d32714927ad2f3d)](https://sonarcloud.io/dashboard?id=OXID-eSales_freeshipping-coupons-module)

## Compatibility

This module assumes you have OXID eShop Compilation version 7.2.0 installed.

## Development installation

To install from github as a source, first clone the repository.

```bash
$ git clone https://github.com/OXID-eSales/freeshipping-coupons-module ./dev-packages/freeshipping-coupons-module
```
Set the repository up in composer.json

```bash
$ composer config repositories.oxid-esales/freeshipping-coupons-module \
  --json '{"type":"path", "url":"./dev-packages/freeshipping-coupons-module", "options": {"symlink": true}}'
```

Ensure you're in the shop root directory (the file `composer.json` and the directories `source/` and `vendor/` are located there) and require the module.

```bash
$ composer require oxid-esales/freeshipping-coupons-module
```

## Freeshipping coupons

The module introduces a new ``shipfree`` option under the Discounts section within the Main settings of the shop's Coupon Series.
This option allows you to create a coupon that dynamically calculates its value based on the basket's shipping cost, 
effectively providing the user with free delivery.

### Configuration

- Navigate to Shop Settings → Coupon Series → Main.
- Select the ``shipfree`` option under the Discount settings.
- Configure the remaining coupon settings as desired.

## Testing
### Linting, syntax check, static analysis

```bash
$ composer update
$ composer static
```

### Unit/Integration/Acceptance tests

- Install this module in a running OXID eShop
- Reset the shop's database

```bash
$ bin/oe-console oe:database:reset --db-host=db-host --db-port=db-port --db-name=db-name --db-user=db-user --db-password=db-password --force
```

- Run all the tests

```bash
$ composer tests-all
```

- Or the desired suite

```bash
$ composer tests-unit
$ composer tests-integration
$ composer tests-codeception
```
