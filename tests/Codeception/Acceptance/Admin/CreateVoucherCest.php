<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\FreeShippingCoupons\Tests\Codeception\Acceptance;

use OxidEsales\Codeception\Admin\DataObject\Voucher;
use OxidEsales\Codeception\Admin\Voucher\MainVoucherPage;
use OxidEsales\FreeShippingCoupons\Tests\Codeception\Support\AcceptanceTester;

/**
 * @group oe_freeshipping
 * @group oe_freeshipping_admin
 * @group oe_freeshipping_create_voucher
 */
class CreateVoucherCest
{
    public function testCreateVoucher(AcceptanceTester $I): void
    {
        $voucherData = $this->getVoucherData();

        $voucherPage = $I->loginAdmin()
            ->openVouchers();

        $mainVoucherPage = new MainVoucherPage($I);
        $mainVoucherPage->createVoucher($voucherData);

        $mainVoucherPage = $voucherPage->findByTitle($voucherData->getTitle());
        $mainVoucherPage->seeVoucher($voucherData);

        $mainVoucherPage->checkVoucherDiscountFieldForShipfreeVoucher();

        $mainVoucherPage->checkAllowSameSeriesRadioDisabled();
    }

    private function getVoucherData(): Voucher
    {
        $voucher = new Voucher();
        $voucher->setTitle('test');
        $voucher->setVoucherType('shipfree');

        return $voucher;
    }
}
