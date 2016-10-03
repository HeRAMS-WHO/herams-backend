<?php
namespace Page;

class Login
{
    // include url of current page
    public static $URL = '/user/login';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
    public static $emailField = ['css' => 'input[name*="[login]"]'];
    public static $passwordField = ['css' => 'input[name*="[password]"]'];
    public static $loginButton = ['css' => 'button[type=submit]'];

    /**
     * @var \AcceptanceTester
     */
    protected $tester;

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($param = "")
    {
        return static::$URL.$param;
    }


    public function login($user, $password, $admin = false)
    {
        $I = $this->tester;
        $I->amOnPage(self::route());

        $I->fillField(self::$emailField, $user);
        $I->fillField(self::$passwordField, $password);
        $I->click(self::$loginButton);
        $I->expectTo("See the logout menu item.");
        $I->seeElementInDOM(['css' => 'a[href$="logout"]']);
        codecept_debug("Logged in!");
        if ($admin) {
            $I->seeInSource('Configuration', '.navbar');
        }
    }

    public function __construct(\AcceptanceTester $tester)
    {
        $this->tester = $tester;
        $tester->resetCookie('PHPSESSID');

    }


}
