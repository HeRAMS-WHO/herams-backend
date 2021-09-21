<?php
declare(strict_types=1);

namespace prime\tests\functional\controllers\user;

use prime\models\ar\User;
use prime\models\user\UserForSelect2;
use prime\tests\FunctionalTester;
use yii\helpers\Json;

/**
 * @covers \prime\controllers\user\Select2
 */
class Select2Cest
{
    public function testEmpty(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['user/select-2']);
        $I->seeResponseCodeIsSuccessful();
        $I->assertEquals(['results' => []], Json::decode($I->grabResponse()));
    }

    public function testSearch(FunctionalTester $I): void
    {
        $user = User::findOne(['id' => TEST_ADMIN_ID]);
        $userForSelect2 = new UserForSelect2($user);
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['user/select-2', 'q' => $user->name]);
        $I->seeResponseCodeIsSuccessful();

        $I->assertEquals(['results' => [['id' => $user->id, 'text' => $userForSelect2->getText()]]], Json::decode($I->grabResponse()));
    }
}
