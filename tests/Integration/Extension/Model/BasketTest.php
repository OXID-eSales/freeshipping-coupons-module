<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\FreeShippingCoupons\Tests\Integration\Extension\Model;

use OxidEsales\Eshop\Application\Model\VoucherSerie;
use OxidEsales\Eshop\Core\Exception\VoucherException;
use OxidEsales\Eshop\Core\Price;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\FreeShippingCoupons\Extension\Model\Basket as BasketModel;
use OxidEsales\Eshop\Application\Model\Voucher as VoucherModel;
use OxidEsales\FreeShippingCoupons\Infrastructure\LanguageProxyInterface;
use OxidEsales\FreeShippingCoupons\Infrastructure\UtilsViewProxyInterface;
use OxidEsales\FreeShippingCoupons\Service\FreeShippingVoucherServiceInterface;
use stdClass;

final class BasketTest extends IntegrationTestCase
{
    private ?VoucherModel $shipFreeVoucher;
    private ?VoucherModel $percentVoucher;
    private VoucherSerie $shipFreeVoucherSerie;
    private VoucherSerie $percentVoucherSerie;

    public function setUp(): void
    {
        parent::setUp();
        $this->callCreateVouchersAndSeries();
    }

    private function callCreateVouchersAndSeries(): void
    {
        $shipFreeVoucherData = $this->createVouchersAndSeries(discountType: 'shipfree');
        $this->shipFreeVoucher = $shipFreeVoucherData['voucher'];
        $this->shipFreeVoucherSerie = $shipFreeVoucherData['voucherSerie'];

        $percentVoucherData = $this->createVouchersAndSeries(discountType: 'percent');
        $this->percentVoucher = $percentVoucherData['voucher'];
        $this->percentVoucherSerie = $percentVoucherData['voucherSerie'];
    }

    private function createVouchersAndSeries($discountType): array
    {
        $voucherSerieName = uniqid();
        $voucherSerie = oxNew(VoucherSerie::class);
        $voucherSerie->assign([
            "oxname" => $voucherSerieName,
            "OXDISCOUNTTYPE" => $discountType,
            "oxdiscount" => 0
        ]);
        $voucherSerie->save();

        $voucherNo = uniqid();
        $voucher = oxNew(VoucherModel::class);
        $voucher->assign([
            'OXVOUCHERNR' => $voucherNo,
            'OXVOUCHERSERIEID' => $voucherSerie->getId(),
        ]);
        $voucher->save();

        $this->assertNotFalse($voucher, "Failed to create voucher.");
        $this->assertNotEmpty($voucher->getId(), "Voucher ID should not be empty.");

        return [
            'voucher' => $voucher,
            'voucherSerie' => $voucherSerie
        ];
    }

    public function testFreeShippingVoucherPriceIsSameAsDeliveryCost()
    {
        $shipFreeVoucher = $this->getVoucherObject($this->shipFreeVoucher->getId());
        $basketMock = $this->createPartialMock(BasketModel::class, [
            'getVouchers',
            'getCosts',
            'getBasketCurrency',
            'getService'
        ]);

        $basketMock->expects($this->once())
            ->method('getVouchers')
            ->willReturn([$shipFreeVoucher]);

        $freeShipServiceSpy = $this->createMock(FreeShippingVoucherServiceInterface::class);
        $freeShipServiceSpy
            ->method('isFreeShippingVoucher')
            ->willReturn(true);

        $languageServiceMock = $this->createMock(LanguageProxyInterface::class);
        $basketMock
            ->method('getService')
            ->willReturnCallback(function ($serviceName) use ($languageServiceMock, $freeShipServiceSpy) {
                return match ($serviceName) {
                    LanguageProxyInterface::class => $languageServiceMock,
                    FreeShippingVoucherServiceInterface::class => $freeShipServiceSpy,
                    default => $this->createMock(stdClass::class),
                };
            });

        $expectedDeliveryPrice = 10.5;

        $priceStub = $this->createMock(Price::class);
        $priceStub
            ->method('getPrice')
            ->willReturn($expectedDeliveryPrice);

        $basketMock
            ->method('getCosts')
            ->with('oxdelivery')
            ->willReturn($priceStub);

        $currency = new \stdClass();
        $currency->id = 0;
        $currency->name = "EUR";
        $currency->rate = "1.00";
        $currency->sign = "€";
        $currency->decimal = "2";

        $basketMock
            ->method('getBasketCurrency')
            ->willReturn($currency);

        $expectedFormatedDeliveryPrice = '10.50 €';
        $languageServiceMock->expects($this->once())
            ->method('formatCurrency')
            ->with($expectedDeliveryPrice, $currency)
            ->willReturn($expectedFormatedDeliveryPrice);

        $this->invokeMethod($basketMock, 'setFreeShippingVoucherPrice');

        $this->assertEquals(
            $expectedDeliveryPrice,
            $shipFreeVoucher->dVoucherdiscount,
            "Voucher discount should equal delivery price."
        );

        $this->assertEquals(
            $expectedFormatedDeliveryPrice,
            $shipFreeVoucher->fVoucherdiscount,
            "Formatted voucher discount should match the formatted delivery price."
        );
    }

    public function testNoneFreeShippingVoucherDoesNotGetDiscount()
    {
        $percentVoucher = $this->getVoucherObject($this->percentVoucher->getId());

        $percentVoucher->dVoucherdiscount ??= null;
        $percentVoucher->fVoucherdiscount ??= null;

        $basketMock = $this->createPartialMock(BasketModel::class, [
            'getVouchers',
            'getService'
        ]);
        $basketMock
            ->method('getVouchers')
            ->willReturn([$percentVoucher]);

        $freeShipServiceSpy = $this->createMock(FreeShippingVoucherServiceInterface::class);
        $freeShipServiceSpy
            ->method('isFreeShippingVoucher')
            ->willReturn(false);

        $languageServiceStub = $this->createMock(LanguageProxyInterface::class);
        $basketMock
            ->method('getService')
            ->willReturnCallback(function ($serviceName) use ($languageServiceStub, $freeShipServiceSpy) {
                return match ($serviceName) {
                    LanguageProxyInterface::class => $languageServiceStub,
                    FreeShippingVoucherServiceInterface::class => $freeShipServiceSpy,
                    default => $this->createMock(stdClass::class),
                };
            });

        $this->invokeMethod($basketMock, 'setFreeShippingVoucherPrice');

        $this->assertNull(
            $percentVoucher->dVoucherdiscount,
            "None free shipping voucher discount should remain null."
        );

        $this->assertNull(
            $percentVoucher->fVoucherdiscount,
            "None free shipping voucher  formatted discount should remain null."
        );
    }

    public function testInitializeVoucherDiscountIfNotAvailableYet()
    {
        $basketMock = $this->createMock(BasketModel::class);

        $basketReflection = new \ReflectionClass($basketMock);
        $voucherDiscount = $basketReflection->getProperty('_oVoucherDiscount');
        $voucherDiscount->setValue($basketMock, null);

        $this->invokeMethod($basketMock, 'initializeVoucherDiscountIfNotAvailableYet');

        $this->assertNotNull($voucherDiscount->getValue($basketMock), "_oVoucherDiscount should be initialized");
    }

    public function testLogDeliveryCostErrorAddsErrorToDisplay()
    {
        $voucherNr = uniqid();
        $deliveryCost = 0.0;

        $utilsViewSpy = $this->createMock(UtilsViewProxyInterface::class);
        $utilsViewSpy->expects($this->once())
            ->method('addErrorToDisplay')
            ->with($this->isInstanceOf(VoucherException::class));

        $basketSpy = $this->createPartialMock(BasketModel::class, ['getService']);
        $basketSpy->method('getService')
            ->with(UtilsViewProxyInterface::class)
            ->willReturn($utilsViewSpy);

        $this->invokeMethod($basketSpy, 'logDeliveryCostError', [$voucherNr, $deliveryCost]);
    }
    private function getVoucherObject($voucherId): stdClass
    {
        $voucher = oxNew(VoucherModel::class);
        $voucher->load($voucherId);

        return $voucher->getSimpleVoucher();
    }

    private function invokeMethod(object $object, string $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function tearDown(): void
    {
        $this->shipFreeVoucherSerie->delete();
        $this->shipFreeVoucher->delete();

        $this->percentVoucherSerie->delete();
        $this->percentVoucher->delete();

        parent::tearDown();
    }
}
