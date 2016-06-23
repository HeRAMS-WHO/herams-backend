<?php
namespace Step\Acceptance;

use Codeception\Scenario;

class UserTester extends \AcceptanceTester
{
    /**
     * Define custom actions here
     */

    public function login($user = USER_NAME, $password = USER_PASS)
    {
        $I = $this;
        $I->amOnPage('/user/login');
        $I->fillField('Login', $user);
        $I->fillField('Password', $password);
        $I->click('Login');
        $I->expectTo("See the logout menu item.");
        $I->dontSeeInCurrentUrl('/user/login');
        $I->seeInSource('Log out');
        codecept_debug("Logged in!");
    }

    public function __construct(Scenario $scenario)
    {
        parent::__construct($scenario);
        $this->login();
    }


}