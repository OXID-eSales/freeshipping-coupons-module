<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\FreeShippingCoupons\Tests\Codeception\Support;

use Codeception\Util\Fixtures;
use OxidEsales\Codeception\Admin\AdminLoginPage;
use OxidEsales\Codeception\Admin\AdminPanel;
use OxidEsales\FreeShippingCoupons\Tests\Codeception\Page\Home;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
final class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    /**
     * Open shop first page.
     */
    public function openShop(): Home
    {
        $I = $this;
        $homePage = new Home($I);
        $I->amOnPage($homePage->URL);

        return $homePage;
    }

    public function openAdminLoginPage(): AdminLoginPage
    {
        $I = $this;
        $adminLoginPage = new AdminLoginPage($I);
        $I->amOnPage($adminLoginPage->URL);

        return $adminLoginPage;
    }

    public function loginAdmin(): AdminPanel
    {
        $adminPage = $this->openAdminLoginPage();
        $admin = Fixtures::get('adminUser');

        return $adminPage->login($admin['userLoginName'], $admin['userPassword']);
    }
}
