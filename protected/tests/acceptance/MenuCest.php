<?php


use \Step\Acceptance\User;
use \Step\Acceptance\Admin;

class MenuCest

{

    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function testMenu(User $I)
    {
        //Only pick urls where no data method is set (like logout)
        foreach($I->grabMultiple('.navbar a:not([data-method])', 'href') as $url) {
            $I->amOnUrl($url);
            $I->dontSeeInSource('#404');
            $I->dontSeeInSource('#500');
        };
    }

    public function testAdminMenu(Admin $I)
    {
        //Only pick urls where no data method is set (like logout)
        foreach($I->grabMultiple('.navbar a:not([data-method])', 'href') as $url) {
            $I->amOnPage($url);
        };
    }
}
