<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\FreeShippingCoupons\Tests\Codeception\Acceptance;

use OxidEsales\Codeception\Admin\DataObject\Voucher;
use OxidEsales\Codeception\Admin\DataObject\VoucherSerie;
use OxidEsales\Codeception\Admin\Voucher\MainVoucherPage;
use OxidEsales\FreeShippingCoupons\Tests\Codeception\Support\AcceptanceTester;

/**
 * @group oe_freeshipping
 * @group oe_freeshipping_admin
 * @group oe_freeshipping_create_voucher_serie
 */
class CreateVoucherCest
{
    public function testCreateVoucherSerie(AcceptanceTester $I): void
    {
        $voucherSerieData = $this->getVoucherSerieData();

        $voucherPage = $I->loginAdmin()
            ->openVouchers();

        $mainVoucherPage = new MainVoucherPage($I);
        $mainVoucherPage->createVoucherSerie($voucherSerieData);

        $mainVoucherPage = $voucherPage->findByTitle($voucherSerieData->getTitle());
        $mainVoucherPage->seeVoucherSerie($voucherSerieData);

        $mainVoucherPage->checkVoucherDiscountFieldForShipfreeVoucher();

        $mainVoucherPage->checkAllowSameSeriesRadioDisabled();

        $voucherData = $this->getVoucherData();
        $mainVoucherPage->createVoucher($voucherData);
        $mainVoucherPage->seeVoucher($voucherData);
    }

    private function getVoucherData(): Voucher
    {
        $voucher = new Voucher();
        $voucher->setVoucherNr('shipfree');
        $voucher->setVoucherAmount('20');

        return $voucher;
    }

    private function getVoucherSerieData(): VoucherSerie
    {
        $voucherSerie = new VoucherSerie();
        $voucherSerie->setTitle('test');
        $voucherSerie->setVoucherType('shipfree');

        return $voucherSerie;
    }
}
