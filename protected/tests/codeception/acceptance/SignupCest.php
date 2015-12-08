<?php

class SignupCest
{

    protected $password = 'Test123';
    protected $email = 'john.doe@test.com';

    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function testDifferentPasswords(AcceptanceTester $I)
    {
        $I->wantTo('Sign up');
        $I->amOnPage('/');
        $I->see('Sign up');
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
        $I->amOnPage('/user/register');
        $I->fillField('Password', $this->password);
        $I->fillField('Confirmation', $this->password);
        $I->fillField('First Name', 'John');
        $I->fillField('Last Name', 'Doe');
        $I->fillField('Email', $this->email);
        $I->fillField('Organization', 'Tester');
        $I->selectOption('Country', 'Belgium');
        $I->fillField('Office', 'Corner');
        $I->fillField('Captcha', 'test');
        $I->click('Submit');
        $I->seeCurrentUrlEquals('/user/login');
        // Check source since this is a javascript popup.
        $I->seeInSource('Your account has been created');
        $I->seeInDatabase('user', [
            'email' => $this->email
        ]);


    }

    public function testUnconfirmed(AcceptanceTester $I)
    {
        $I->amOnPage('/user/login');
        $I->fillField('Login', 'test@localhost.net');
        $I->fillField('Password', 'test123');
        $I->click('Login');
        $I->see('You need to confirm your email address');

    }

    public function testLogin(AcceptanceTester $I)
    {

        $I->amOnPage('/user/login');
        $I->fillField('Login', 'test2@localhost.net');
        $I->fillField('Password', 'test123');
        $I->click('Login');
        $I->seeCurrentUrlEquals('/');
    }




}
