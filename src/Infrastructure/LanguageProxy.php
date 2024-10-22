<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\FreeShippingCoupons\Infrastructure;

class LanguageProxy implements LanguageProxyInterface
{
    public function __construct(
        private readonly \OxidEsales\Eshop\Core\Language $language
    ) {
    }

    public function formatCurrency(float $value, \stdClass $currency): string
    {
        return $this->language->formatCurrency($value, $currency);
    }
}
