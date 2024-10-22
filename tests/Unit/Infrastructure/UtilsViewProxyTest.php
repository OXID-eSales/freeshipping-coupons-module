<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\FreeShippingCoupons\Tests\Unit\Infrastructure;

use OxidEsales\Eshop\Core\Exception\VoucherException;
use OxidEsales\Eshop\Core\UtilsView;
use OxidEsales\FreeShippingCoupons\Infrastructure\UtilsViewProxy;
use PHPUnit\Framework\TestCase;

final class UtilsViewProxyTest extends TestCase
{
    public function testAddErrorToDisplay(): void
    {

        $utilsViewSpy = $this->createMock(UtilsView::class);
        $voucherException = new VoucherException('Test exception');

        $utilsViewSpy
            ->expects($this->once())
            ->method('addErrorToDisplay')
            ->with($voucherException, false, true);

        $sut = new UtilsViewProxy($utilsViewSpy);

        $sut->addErrorToDisplay(
            exception: $voucherException,
            useCustomDestination: true,
            customDestination: 'basket'
        );
    }
}
