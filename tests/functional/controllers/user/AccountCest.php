<?php
declare(strict_types=1);

namespace prime\tests\functional\controllers\user;

use prime\models\ar\User;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\user\Account
 */
class AccountCest
{

    public function testPageLoad(FunctionalTester $I)
    {
        \Yii::$app->urlSigner;
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['user/account']);
        $I->seeResponseCodeIs(200);
    }

    public function testUpdateName(FunctionalTester $I)
    {
        \Yii::$app->urlSigner;
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['user/account']);
        $I->seeResponseCodeIs(200);
        $I->fillField('Name', 'newname');
        $I->click('Update account information');
        /** @var User $user */
        $user = \Yii::$app->user->identity;
        $I->assertInstanceOf(User::class, $user);
        $user->refresh();
        $I->assertSame('newname', $user->name);
    }

    public function testUpdateLanguage(FunctionalTester $I)
    {
        \Yii::$app->urlSigner;
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['user/account']);
        $I->seeResponseCodeIs(200);
        $I->selectOption('Language', 'fr-FR');
        $I->click('Update account information');
        /** @var User $user */
        $user = \Yii::$app->user->identity;
        $I->assertInstanceOf(User::class, $user);
        $user->refresh();
        $I->assertSame('fr-FR', $user->language);
        $I->assertSame('fr-FR', \Yii::$app->language);
    }


}