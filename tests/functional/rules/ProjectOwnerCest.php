<?php
declare(strict_types=1);

namespace prime\tests\functional\rules;

use prime\helpers\ProposedGrant;
use prime\models\ar\Permission;
use prime\models\ar\User;
use prime\tests\FunctionalTester;
use SamIT\abac\AuthManager;

class ProjectOwnerCest
{

    public function testCanSyncWorkspace(FunctionalTester $I)
    {
        $project = $I->haveProject();
        $workspace = $I->haveWorkspace();
        /** @var AuthManager $manager */
        $manager = \Yii::$app->abacManager;
        $I->amLoggedInAs(TEST_USER_ID);
        $user = \Yii::$app->user->identity;
        $I->assertInstanceOf(User::class, $user);
        $manager->grant($user, $project, Permission::PERMISSION_SURVEY_DATA);
        $manager->grant($user, $project, Permission::PERMISSION_MANAGE_WORKSPACES);

        $I->amOnPage(['workspace/limesurvey', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(200);
        $I->assertTrue(\Yii::$app->user->can(Permission::PERMISSION_SURVEY_DATA, $workspace));
        $I->seeInSource('Refresh workspace');
    }
}
