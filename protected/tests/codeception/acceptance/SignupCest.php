<?php

class SignupCest
{

    public function _before(AcceptanceTester $I)
    {
        $I->runMigrations();
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function testDifferentPasswords(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->click('Login or sign up');
        $I->click('Sign up');
        $I->seeCurrentUrlEquals('/user/register');

        $I->fillField('Password', 'Test');
        $I->fillField('Confirmation', 'Test1');

        $I->click('Submit');
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
        $I->selectOption('Country', 'Belgium');
        $I->fillField('Location', 'Corner');
        $I->fillField('Captcha', 'test');
        $I->click('Submit');
        // Check source since this is a javascript popup.
        $I->seeInSource('Your account has been created');
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
        $I->see('You need to confirm your email address');
    }

    public function testLogin(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->dontSeeInSource('Log out');
        $I->login();
    }

    public function testAdminLogin(\Step\Acceptance\AdminTester $I)
    {
        $I->amOnPage('/');
        $I->dontSeeInSource('Log out');
        $I->login();
    }
}
