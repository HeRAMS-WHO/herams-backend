<?php
declare(strict_types=1);

namespace prime\tests\functional\controllers\user;

use prime\models\ar\User;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\user\Profile
 * @covers \prime\controllers\UserController
 */
class ProfileCest
{
    public function testPageLoad(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['user/profile']);
        $I->seeResponseCodeIs(200);
    }

    public function testUpdateName(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['user/profile']);
        $I->seeResponseCodeIs(200);

        $newName = 'newName';
        $I->fillField('Name', $newName);
        $I->click('Update profile');
        /** @var User $user */
        $user = \Yii::$app->user->identity;
        $I->assertInstanceOf(User::class, $user);
        $user->refresh();
        $I->assertSame($newName, $user->name);
    }

    public function testUpdateLanguage(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['user/profile']);
        $I->seeResponseCodeIs(200);
        $I->selectOption('Language', 'fr-FR');
        $I->click('Update profile');
        /** @var User $user */
        $user = \Yii::$app->user->identity;
        $I->assertInstanceOf(User::class, $user);
        $user->refresh();
        $I->assertSame('fr-FR', $user->language);
        $I->assertSame('fr-FR', \Yii::$app->language);
    }
}
