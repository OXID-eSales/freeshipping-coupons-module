<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

/**
 * Metadata version
 */

use OxidEsales\FreeShippingCoupons\Core\Module;

$sMetadataVersion = '2.1';

/**
 * Module information
 */
$aModule = [
    'id'          => Module::MODULE_ID,
    'title'       => 'OxidEsales Freeshipping Coupons Module',
    'description' => 'OxidEsales Freeshipping Coupons Module',
    'thumbnail'   => 'logo.png',
    'version'     => '1.0.0',
    'author'      => 'OXID eSales AG',
    'url'         => '',
    'email'       => '',
    'extend'      => [
        \OxidEsales\Eshop\Application\Model\Basket::class => OxidEsales\FreeShippingCoupons\Extension\Model\Basket::class
    ],
    'controllers' => [],
    'events'      => [],
    'settings'    => [],
];
