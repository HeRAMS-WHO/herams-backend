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

    public function _before(FunctionalTester $I)
    {
        \Yii::$app->auditService->disable();
    }

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

        $I->assertUserCan($workspace, Permission::PERMISSION_SURVEY_DATA);
        $I->amOnPage(['workspace/facilities', 'id' => $workspace->id]);
        $I->seeResponseCodeIs(200);
        $I->seeInSource('Refresh workspace');
    }
}
