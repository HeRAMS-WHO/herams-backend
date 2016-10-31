<?php


use Step\Acceptance\Guest;
class UserCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function testDifferentPasswords(Guest $I)
    {
        $I->amOnPage('/user/register');
        $I->fillField('Password', 'Test');
        $I->fillField('Confirmation', 'Test1');

        $I->click('Submit');
        $I->wait(1);
        $I->seeCurrentUrlEquals('/user/register');
        $I->see('must be equal');
        $I->dontSeeInSource('Your account has been created');
    }

    public function testRegistration(AcceptanceTester $I) {
        $email = 'john.doe@test.com';
        $password = 'Test123';
        $I->amOnPage('/user/register');
        $I->fillField('Password', $password);
        $I->fillField('Confirmation', $password);
        $I->fillField('First Name', 'John');
        $I->fillField('Last Name', 'Doe');
        $I->fillField('Email', $email);
        $I->fillField('Organization', 'Tester');

        $I->select2Option(['css' => '[name*=country]'], 'Belgium');
        $I->fillField('Location', 'Corner');
        $I->fillField('Captcha', 'test');
        $I->click('Submit');
        $I->wait(5);
        $I->waitForText('Your account has been created');
        $I->seeInDatabase('user', [
            'email' => $email
        ]);
    }

    public function testUnconfirmed(AcceptanceTester $I)
    {
        $email = 'test@localhost.net';
        $password = 'test123';
        $I->seeInDatabase('user', [
            'email' => $email,
            'confirmed_at' => null
        ]);
        $I->amOnPage('/user/login');
        $I->fillField('Login', $email);
        $I->fillField('Password', $password);
        $I->click('Login');
        $I->wait(3);
        $I->see('You need to confirm your email address');
    }

    public function testLogin(\Step\Acceptance\User $I)
    {
        $I->amOnPage('/');
    }

    public function testAdminLogin(\Step\Acceptance\Admin $I)
    {
        $I->amOnPage('/');
    }
}
