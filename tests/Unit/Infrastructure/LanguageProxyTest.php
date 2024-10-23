<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\FreeShippingCoupons\Tests\Unit\Infrastructure;

use OxidEsales\Eshop\Core\Language;
use OxidEsales\FreeShippingCoupons\Infrastructure\LanguageProxy;
use PHPUnit\Framework\TestCase;

class LanguageProxyTest extends TestCase
{
    public function testFormatCurrency(): void
    {
        $currency = new \stdClass();
        $currency->id = 0;
        $currency->name = "EUR";
        $currency->rate = "1.00";
        $currency->sign = "€";
        $currency->decimal = "2";

        $expectedFormatedAmount = '100.00 €';
        $languageMock = $this->createMock(Language::class);
        $languageMock
            ->expects($this->once())
            ->method('formatCurrency')
            ->with(100.00, $currency)
            ->willReturn($expectedFormatedAmount);

        $sut = new LanguageProxy($languageMock);

        $actualFormatedAmount = $sut->formatCurrency(value: 100.00, currency: $currency);

        $this->assertEquals($expectedFormatedAmount, $actualFormatedAmount);
    }
}
