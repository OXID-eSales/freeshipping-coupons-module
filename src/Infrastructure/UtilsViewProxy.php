<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\FreeShippingCoupons\Infrastructure;

use OxidEsales\Eshop\Core\Contract\IDisplayError;
use OxidEsales\Eshop\Core\Exception\StandardException;

class UtilsViewProxy implements UtilsViewProxyInterface
{
    public function __construct(
        private readonly \OxidEsales\Eshop\Core\UtilsView $utilsView
    ) {
    }

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function addErrorToDisplay(
        StandardException|IDisplayError|string $exception,
        bool $full = false,
        bool $useCustomDestination = false,
        string $customDestination = '',
        string $activeController = '',
    ): void {
        $this->utilsView->addErrorToDisplay(
            exception: $exception,
            blFull: $full,
            useCustomDestination: $useCustomDestination,
            customDestination: $customDestination,
            activeController: $activeController
        );
    }
}
