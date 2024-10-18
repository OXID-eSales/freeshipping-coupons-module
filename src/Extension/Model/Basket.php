<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\FreeShippingCoupons\Extension\Model;

use OxidEsales\Eshop\Application\Model\Voucher as VoucherModel;
use OxidEsales\Eshop\Core\Exception\VoucherException;
use OxidEsales\Eshop\Core\Price;
use OxidEsales\Eshop\Core\Registry;

/** @mixin \OxidEsales\Eshop\Application\Model\Basket */
class Basket extends Basket_parent
{
    /**
     * @codeCoverageIgnore not testable because of parent call
     */
    protected function calcTotalPrice(): void
    {
        $this->setFreeShippingVoucherPrice();
        parent::calcTotalPrice();
    }

    protected function setFreeShippingVoucherPrice(): void
    {
        $vouchers = $this->getVouchers();

        foreach ($vouchers as $key => $voucher) {
            if ($this->isFreeShippingVoucher(voucherId: $voucher->sVoucherId)) {
                $this->initializeVoucherDiscount();

                $deliveryCost = $this->getDeliveryPrice();
                $voucher->dVoucherdiscount = $deliveryCost;
                $this->_oVoucherDiscount->add($deliveryCost);
                $this->_aVouchers[$key] = $voucher;

                $this->logDeliveryCostError(voucherNr: $voucher->sVoucherNr, deliveryCost: $deliveryCost);
            }
        }
    }

    private function logDeliveryCostError(string $voucherNr, float $deliveryCost): void
    {
        if ($deliveryCost === 0.0) {
            $voucherException = new VoucherException('ERROR_MESSAGE_VOUCHER_WHEN_NO_DELIVERY');
            $voucherException->setVoucherNr($voucherNr);
            Registry::getUtilsView()->addErrorToDisplay($voucherException, false, true);
        }
    }

    private function initializeVoucherDiscount(): void
    {
        if ($this->_oVoucherDiscount === null) {
            $this->_oVoucherDiscount = $this->getPriceModel();
        }
    }

    private function getDeliveryPrice(): float
    {
        $deliveryCost = $this->getCosts('oxdelivery');
        return ($deliveryCost instanceof Price) ? $deliveryCost->getPrice() : 0.0;
    }


    private function isFreeShippingVoucher(string $voucherId): bool
    {
        $voucher = $this->getVoucherModel();
        $voucher->load($voucherId);
        return $voucher->getDiscountType() === 'shipfree';
    }

    protected function getVoucherModel(): VoucherModel
    {
        return oxNew(VoucherModel::class);
    }

    protected function getPriceModel(): Price
    {
        return oxNew(Price::class);
    }
}
