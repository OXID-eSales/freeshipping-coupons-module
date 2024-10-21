<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\FreeShippingCoupons\Tests\Unit\Service;

use OxidEsales\Eshop\Application\Model\Voucher;
use OxidEsales\FreeShippingCoupons\Infrastructure\VoucherFactoryInterface;
use OxidEsales\FreeShippingCoupons\Service\FreeShippingVoucherService;
use PHPUnit\Framework\TestCase;

final class FreeShippingVoucherServiceTest extends TestCase
{
    public function testIsFreeShippingVoucherReturnsTrue(): void
    {
        $voucherId = uniqid();
        $voucherFactoryMock = $this->createMock(VoucherFactoryInterface::class);
        $voucherMock = $this->createMock(Voucher::class);

        $voucherFactoryMock
            ->method('create')
            ->willReturn($voucherMock);

        $voucherMock
            ->method('load')
            ->with($voucherId);

        $voucherMock
            ->method('getDiscountType')
            ->willReturn('shipfree');

        $sut = new FreeShippingVoucherService(voucherFactory: $voucherFactoryMock);

        $this->assertTrue($sut->isFreeShippingVoucher($voucherId));
    }

    public function testIsFreeShippingVoucherReturnsFalse(): void
    {
        $voucherId = uniqid();
        $voucherFactoryMock = $this->createMock(VoucherFactoryInterface::class);
        $voucherMock = $this->createMock(Voucher::class);

        $voucherFactoryMock
            ->method('create')
            ->willReturn($voucherMock);

        $voucherMock
            ->method('load')
            ->with($voucherId);

        $voucherMock
            ->method('getDiscountType')
            ->willReturn('percentage');

        $sut = new FreeShippingVoucherService(voucherFactory: $voucherFactoryMock);

        $this->assertFalse($sut->isFreeShippingVoucher($voucherId));
    }
}
