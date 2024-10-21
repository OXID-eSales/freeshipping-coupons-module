<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\FreeShippingCoupons\Tests\Integration\Infrastructure;

use OxidEsales\Eshop\Application\Model\Voucher;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\FreeShippingCoupons\Infrastructure\VoucherFactory;

final class VoucherFactoryTest extends IntegrationTestCase
{
    public function testCreate()
    {
        $voucherFactory = new VoucherFactory();
        $voucher = $voucherFactory->create();
        $this->assertEquals(new Voucher(), $voucher);

        $anotherVoucher = $voucherFactory->create();
        $this->assertNotSame($voucher, $anotherVoucher);
    }
}
