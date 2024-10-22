<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\FreeShippingCoupons\Extension\Model;

use OxidEsales\Eshop\Core\Exception\VoucherException;
use OxidEsales\Eshop\Core\Price;
use OxidEsales\FreeShippingCoupons\Infrastructure\LanguageProxyInterface;
use OxidEsales\FreeShippingCoupons\Infrastructure\UtilsViewProxyInterface;
use OxidEsales\FreeShippingCoupons\Service\FreeShippingVoucherServiceInterface;

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

        $shopLanguageService = $this->getService(LanguageProxyInterface::class);
        $freeShipService = $this->getService(FreeShippingVoucherServiceInterface::class);

        foreach ($vouchers as $voucher) {
            if ($freeShipService->isFreeShippingVoucher(voucherId: $voucher->sVoucherId)) {
                $this->initializeVoucherDiscountIfNotAvailableYet();

                $deliveryCost = $this->getDeliveryPrice();

                $voucher->dVoucherdiscount = $deliveryCost;
                $voucher->fVoucherdiscount = $shopLanguageService->formatCurrency(
                    $deliveryCost,
                    $this->getBasketCurrency()
                );
                $this->_oVoucherDiscount->add($deliveryCost);

                $this->logDeliveryCostError(voucherNr: $voucher->sVoucherNr, deliveryCost: $deliveryCost);
            }
        }
    }

    private function logDeliveryCostError(string $voucherNr, float $deliveryCost): void
    {
        if ($deliveryCost === 0.0) {
            $voucherException = new VoucherException('ERROR_MESSAGE_VOUCHER_WHEN_NO_DELIVERY');
            $voucherException->setVoucherNr($voucherNr);

            $utilsView = $this->getService(UtilsViewProxyInterface::class);
            $utilsView->addErrorToDisplay(
                exception: $voucherException,
                useCustomDestination: true,
                customDestination: 'basket'
            );
        }
    }

    /**
     * This Ensures the _oVoucherDiscount property is initialized for calculation of voucher discount in Basket.
     */
    private function initializeVoucherDiscountIfNotAvailableYet(): void
    {
        if ($this->_oVoucherDiscount === null) {
            $this->_oVoucherDiscount = $this->getPriceObject();
        }
    }

    private function getDeliveryPrice(): float
    {
        $deliveryCost = $this->getCosts('oxdelivery');
        return ($deliveryCost instanceof Price) ? $deliveryCost->getPrice() : 0.0;
    }
}
