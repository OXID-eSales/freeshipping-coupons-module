<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\FreeShippingCoupons\Tests\Unit\Extension\Model;

use OxidEsales\Eshop\Application\Model\VoucherSerie;
use OxidEsales\Eshop\Core\Price;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\FreeShippingCoupons\Extension\Model\Basket as BasketModel;
use OxidEsales\Eshop\Application\Model\Voucher as VoucherModel;
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

    public function testNoneFreeShippingVoucherDoesNotGetDiscount()
    {
        $percentVoucher = $this->getVoucherObject($this->percentVoucher->getId());

        $basketSpy = $this->createPartialMock(BasketModel::class, ['getVouchers']);
        $basketSpy
            ->method('getVouchers')
            ->willReturn([$percentVoucher]);

        $this->invokeProtectedMethod($basketSpy, 'setFreeShippingVoucherPrice');

        $this->assertNull(
            $percentVoucher->dVoucherdiscount,
            "None free shipping voucher discount should remain null."
        );
    }

    public function testFreeShippingVoucherPriceIsSameAsDeliveryCost()
    {
        $shipFreeVoucher = $this->getVoucherObject($this->shipFreeVoucher->getId());
        $basketSpy = $this->createPartialMock(BasketModel::class, ['getVouchers', 'getCosts']);

        $basketSpy->expects($this->once())
            ->method('getVouchers')
            ->willReturn([$shipFreeVoucher]);

        $expectedDeliveryPrice = 10.5;
        $priceStub = $this->createMock(Price::class);
        $priceStub
            ->method('getPrice')
            ->willReturn($expectedDeliveryPrice);

        $basketSpy
            ->method('getCosts')
            ->with('oxdelivery')
            ->willReturn($priceStub);

        $this->invokeProtectedMethod($basketSpy, 'setFreeShippingVoucherPrice');

        $this->assertEquals(
            $expectedDeliveryPrice,
            $shipFreeVoucher->dVoucherdiscount,
            "Voucher discount should equal delivery price."
        );
    }

    public function testInitializeVoucherDiscount()
    {
        $basketSpy = $this->createMock(BasketModel::class);

        $basketReflection = new \ReflectionClass($basketSpy);
        $voucherDiscount = $basketReflection->getProperty('_oVoucherDiscount');
        $voucherDiscount->setValue($basketSpy, null);

        $this->invokeProtectedMethod($basketSpy, 'initializeVoucherDiscount');

        $this->assertNotNull($voucherDiscount->getValue($basketSpy), "_oVoucherDiscount should be initialized");
    }

    public function testLogDeliveryCostError()
    {
        $shipFreeVoucher = $this->getVoucherObject($this->shipFreeVoucher->getId());

        $basketSpy = $this->createPartialMock(BasketModel::class, ['getVouchers','getCosts']);
        $basketSpy
            ->method('getVouchers')
            ->willReturn([$shipFreeVoucher]);

        $priceStub = $this->createMock(Price::class);
        $priceStub
            ->method('getPrice')
            ->willReturn(0.0);

        $basketSpy
            ->method('getCosts')
            ->with('oxdelivery')
            ->willReturn($priceStub);

        $this->invokeProtectedMethod($basketSpy, 'setFreeShippingVoucherPrice');

        $errors = Registry::getSession()->getVariable('Errors');
        $this->assertTrue(
            str_contains(
                serialize($errors),
                'ERROR_MESSAGE_VOUCHER_WHEN_NO_DELIVERY'
            ),
            "Expected error message was not found."
        );
    }

    private function getVoucherObject($voucherId): stdClass
    {
        $voucher = oxNew(VoucherModel::class);
        $voucher->load($voucherId);

        return $voucher->getSimpleVoucher();
    }

    private function invokeProtectedMethod($object, string $methodName): void
    {
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        $method->invoke($object);
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
