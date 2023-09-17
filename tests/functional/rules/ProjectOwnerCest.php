<?php

declare(strict_types=1);

namespace prime\tests\functional\rules;

use herams\common\domain\user\User;
use herams\common\models\PermissionOld;
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
        $project = $I->haveProject();
        $workspace = $I->haveWorkspace();
        /** @var AuthManager $manager */
        $manager = \Yii::$app->abacManager;
        $user = \Yii::$app->user->identity;
        $I->assertInstanceOf(User::class, $user);
        $manager->grant($user, $project, PermissionOld::PERMISSION_SURVEY_DATA);

        $I->assertUserCan($workspace, PermissionOld::PERMISSION_SURVEY_DATA);
        $I->amOnPage([
            'workspace/facilities',
            'id' => $workspace->id,
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeInSource('Refresh workspace');
    }
}
