<?php
declare(strict_types=1);

namespace prime\tests\functional\controllers\project;

use prime\models\ar\Permission;
use prime\models\ar\User;
use prime\tests\FunctionalTester;

class ShareCest
{

    public function testAccessControl(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();

        $I->amOnPage(['project/share', 'id' => $project->id]);
        $I->seeResponseCodeIs(403);
    }

    public function testShareWithWriteAccess(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_WRITE);

        $I->amOnPage(['project/share', 'id' => $project->id]);
        $I->seeResponseCodeIs(403);
    }

    public function testLeadPermission(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $project = $I->haveProject();
        $user = User::findOne(['id' => TEST_USER_ID]);

        $I->amOnPage(['project/share', 'id' => $project->id]);
        $I->seeResponseCodeIs(200);
        $I->see(\Yii::t('app', 'Project coordinator'));

        $I->amLoggedInAs(TEST_USER_ID);
        $I->grantCurrentUser($project, Permission::ROLE_LEAD);
        $I->amOnPage(['project/index']);
        $I->seeResponseCodeIs(200);
        $I->see($user->name, 'table tr td');
    }
}
