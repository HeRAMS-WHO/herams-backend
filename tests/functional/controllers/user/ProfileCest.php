<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\user;

use prime\models\ar\User;
use prime\objects\enums\Language;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\user\Profile
 * @covers \prime\controllers\UserController
 */
final class ProfileCest
{
    public function testPageLoad(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['user/profile']);
        $I->seeResponseCodeIs(200);
    }

    public function testUpdateName(FunctionalTester $I): void
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

    public function testUpdateLanguage(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['user/profile']);
        $I->seeResponseCodeIs(200);
        $I->selectOption('Language', Language::frFR->value);
        $I->click('Update profile');
        /** @var User $user */
        $user = \Yii::$app->user->identity;
        $I->assertInstanceOf(User::class, $user);
        $user->refresh();
        $I->assertSame(Language::frFR->value, $user->language);
        $I->amOnPage(['user/profile']);
        $I->assertSame(Language::frFR->value, \Yii::$app->language);
    }
}
