<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\FreeShippingCoupons\Infrastructure;

interface LanguageProxyInterface
{
    public function formatCurrency(float $value, \stdClass $currency): string;
}
