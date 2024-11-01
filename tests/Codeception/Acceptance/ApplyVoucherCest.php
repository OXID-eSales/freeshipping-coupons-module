<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\FreeShippingCoupons\Tests\Codeception\Acceptance;

use Codeception\Util\Fixtures;
use OxidEsales\Codeception\Module\Translation\Translator;
use OxidEsales\Codeception\Step\Basket;
use OxidEsales\FreeShippingCoupons\Tests\Codeception\Support\AcceptanceTester;

/**
 * @group oe_freeshipping
 * @group oe_freeshipping_frontend
 * @group oe_freeshipping_apply_voucher
 */
class ApplyVoucherCest
{
    private const COUPON_DISCOUNT = '0,00 €';
    private const PRODUCT_FIXTURE_KEY = 'prod-1000';
    private const VOUCHER_FIXTURE_KEY = 'voucher-01';
	// phpcs:ignore Generic.Files.LineLength.TooLong
    private string $couponInformationSelector = '//div[contains(@class,"list-group")] //div[contains(@class,"list-group-item")]';
	// phpcs:ignore Generic.Files.LineLength.TooLong
    private string $couponExceptionSelector = '//div[contains(@class,"sticky-md-top")] //div[contains(@class,"alert-danger")]';

    public function applyShipFreeVoucher(AcceptanceTester $I): void
    {
        $I->wantToTest('Add products to basket and check free ship coupon');

        $basket = new Basket($I);
        $homePage = $I->openShop();
        $product = Fixtures::get(self::PRODUCT_FIXTURE_KEY);

        $basket->addProductToBasket($product['id'], 1);

        $basketPage = $homePage->openMiniBasket()->openBasketDisplay();
        $voucher = Fixtures::get(self::VOUCHER_FIXTURE_KEY);
        $basketPage->addCouponToBasket($voucher['voucherNr']);

        $this->validateCouponApplied($I, $voucher['voucherNr'], self::COUPON_DISCOUNT);
        $this->resetVoucherInDatabase($I, $voucher['voucherNr']);
    }

    private function resetVoucherInDatabase(AcceptanceTester $I, string $voucherNr): void
    {
        $I->updateInDatabase('oxvouchers', ['oxreserved' => 0], ['OXVOUCHERNR' => $voucherNr]);
    }

    private function validateCouponApplied(AcceptanceTester $I, string $couponId, string $couponDiscount): void
    {
        $couponText = $this->getCouponInformationText($couponId, $couponDiscount);
        $I->see($couponText, $this->couponInformationSelector);

        $couponExceptionMessage = $this->getCouponExceptionMessage();
        $I->see($couponExceptionMessage, $this->couponExceptionSelector);
    }

    private function getCouponInformationText(string $couponId, string $couponDiscount): string
    {
        return sprintf(
            '%s (%s) %s',
            Translator::translate('COUPON'),
            $couponId,
            $couponDiscount
        );
    }

    private function getCouponExceptionMessage(): string
    {
        return Translator::translate('ERROR_MESSAGE_VOUCHER_WHEN_NO_DELIVERY');
    }
}
