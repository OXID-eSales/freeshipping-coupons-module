<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\FreeShippingCoupons\Service;

interface FreeShippingVoucherServiceInterface
{
    public function isFreeShippingVoucher(string $voucherId): bool;
}
