<?php


/**
 * Inherited Methods
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
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

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
        $I->seeElement('a[href="/user/logout"]');
    }
}
