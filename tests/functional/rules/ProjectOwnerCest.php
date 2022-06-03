<?php

declare(strict_types=1);

namespace prime\tests\functional\rules;

use prime\helpers\ProposedGrant;
use prime\models\ar\Permission;
use prime\models\ar\User;
use prime\tests\FunctionalTester;
use SamIT\abac\AuthManager;

/**
 * @coversNothing
 */
class ProjectOwnerCest
{
    public function testCanSyncWorkspace(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProjectForLimesurvey();
        $workspace = $I->haveWorkspaceForLimesurvey();
        /** @var AuthManager $manager */
        $manager = \Yii::$app->abacManager;
        $user = \Yii::$app->user->identity;
        $I->assertInstanceOf(User::class, $user);
        $manager->grant($user, $project, Permission::PERMISSION_SURVEY_DATA);

        $I->assertUserCan($workspace, Permission::PERMISSION_SURVEY_DATA);
        $I->amOnPage([
            'workspace/facilities',
            'id' => $workspace->id,
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeInSource('Refresh workspace');
    }
}
