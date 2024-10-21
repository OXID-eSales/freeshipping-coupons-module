<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\FreeShippingCoupons\Service;

use OxidEsales\FreeShippingCoupons\Infrastructure\VoucherFactoryInterface;

class FreeShippingVoucherService implements FreeShippingVoucherServiceInterface
{
    public function __construct(
        private readonly VoucherFactoryInterface $voucherFactory
    ) {
    }

    public function isFreeShippingVoucher(string $voucherId): bool
    {
        $voucher = $this->voucherFactory->create();
        $voucher->load($voucherId);
        return $voucher->getDiscountType() === 'shipfree';
    }
}
