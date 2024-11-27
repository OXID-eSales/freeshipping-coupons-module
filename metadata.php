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
    'title'       =>
        [
            'de' => 'Gutscheine für Versandkosten-Ausgleich',
            'en' => 'Shipping Cost Compensation Coupons'
        ],
    'description' => [
            'de' => 'Dieses Modul fügt einen neuen Gutschein-Typ hinzu, der die Versandkosten automatisch ausgleicht.',
            'en' => 'This module adds a new coupon type that compensates for shipping costs, balancing them to zero.'
        ],
    'thumbnail'   => 'logo.png',
    'version'     => '1.0.0',
    'author'      => 'OXID eSales AG',
    'url'         => 'https://www.oxid-esales.com',
    'email'       => 'info@oxid-esales.com',
    'extend'      => [
        \OxidEsales\Eshop\Application\Model\Basket::class => OxidEsales\FreeShippingCoupons\Extension\Model\Basket::class
    ],
    'controllers' => [],
    'events'      => [],
    'settings'    => [],
];
