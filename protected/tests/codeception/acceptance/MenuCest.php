<?php

class ToolCest
{

    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function testMenu(AcceptanceTester $I)
    {
        $I->login();
        //Only pick urls where no data method is set (like logout)
        foreach($I->grabMultiple('.navbar a:not([data-method])', 'href') as $url) {
            $I->click('a[href="' . $url . '"]');
            $I->seeResponseCodeIs(200);
        };
    }

    public function testAdminMenu(\Step\Acceptance\AdminTester $I)
    {
        $I->login();
        //Only pick urls where no data method is set (like logout)
        foreach($I->grabMultiple('.navbar a:not([data-method])', 'href') as $url) {
            $I->click('a[href="' . $url . '"]');
            $I->seeResponseCodeIs(200);
        };
    }
}
