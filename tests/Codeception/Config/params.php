<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\FreeShippingCoupons\Tests\Codeception\Config;

use Symfony\Component\Filesystem\Path;

if ($shopRootPath = getenv('SHOP_ROOT_PATH')){
    require_once(Path::join($shopRootPath, 'source', 'bootstrap.php'));
}

return (new CodeceptionParametersProvider())->getParameters();