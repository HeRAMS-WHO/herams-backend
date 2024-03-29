<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\project;

use herams\common\domain\user\User;
use herams\common\models\PermissionOld;
use prime\tests\FunctionalTester;
use yii\helpers\Url;

/**
 * @covers \prime\controllers\project\Workspaces
 */
class WorkspacesCest
{
    public function testAccessControl(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $I->amOnPage([
            'project/workspaces',
            'id' => $project->id,
        ]);
        $I->seeResponseCodeIs(200);
        $I->dontSeeLink('Import workspaces', Url::to([
            '/workspace/import',
            'project_id' => $project->id,
        ]));
        $I->dontSeeLink('Create workspace', Url::to([
            '/workspace/create',
            'project_id' => $project->id,
        ]));

        \Yii::$app->abacManager->grant(User::findOne([
            'id' => TEST_USER_ID,
        ]), $project, PermissionOld::PERMISSION_MANAGE_WORKSPACES);
        $I->amOnPage([
            'project/workspaces',
            'id' => $project->id,
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeInSource('Import workspaces');
        $I->seeLink('Import workspaces', Url::to([
            '/workspace/import',
            'project_id' => $project->id,
        ]));
        $I->seeLink('Create workspace', Url::to([
            '/workspace/create',
            'project_id' => $project->id,
        ]));
    }

    public function testNoLogin(FunctionalTester $I)
    {
        $I->stopFollowingRedirects();
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        \Yii::$app->user->logout();

        $I->amOnPage([
            'project/workspaces',
            'id' => $project->id,
        ]);
        $I->seeResponseCodeIs(302);
    }

    public function testInvalidProject(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage([
            'project/workspaces',
            'id' => 12345,
        ]);
        $I->seePageNotFound();
    }
}
