<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\FreeShippingCoupons\Infrastructure;

use OxidEsales\Eshop\Application\Model\Voucher;

interface VoucherFactoryInterface
{
    public function create(): Voucher;
}
