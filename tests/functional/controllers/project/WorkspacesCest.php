<?php


namespace prime\tests\functional\controllers\project;

use prime\models\ar\User;
use prime\models\permissions\Permission;
use prime\tests\FunctionalTester;
use yii\helpers\Url;

class WorkspacesCest
{

    public function testAccessControl(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $I->amOnPage(['project/workspaces', 'id' => $project->id]);
        $I->seeResponseCodeIs(200);
        $I->dontSeeLink('Import workspaces', Url::to(['/workspace/import', 'project_id' => $project->id]));
        $I->dontSeeLink('Create workspace', Url::to(['/workspace/create', 'project_id' => $project->id]));

        Permission::grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_WRITE);
        $I->amOnPage(['project/workspaces', 'id' => $project->id]);
        $I->seeResponseCodeIs(200);
        $I->seeLink('Import workspaces', Url::to(['/workspace/import', 'project_id' => $project->id]));
        $I->seeLink('Create workspace', Url::to(['/workspace/create', 'project_id' => $project->id]));
    }

    public function testNoLogin(FunctionalTester $I)
    {
        $I->stopFollowingRedirects();
        $project = $I->haveProject();
        $I->amOnPage(['project/workspaces', 'id' => $project->id]);
        $I->seeResponseCodeIs(302);
    }

    public function testDataUpdateActionWorkspaceAdmin(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $workspace =  $I->haveWorkspace();
        $I->amOnPage(['project/workspaces', 'id' => $project->id]);

        $I->dontSeeElement('a', [
            'href' => Url::to(['workspace/limesurvey', 'id' => $workspace->id]),
        ]);

        Permission::grant(User::findOne(['id' => TEST_USER_ID]), $workspace, Permission::PERMISSION_ADMIN);

        $I->amOnPage(['project/workspaces', 'id' => $project->id]);

        $I->seeElement('a', [
            'href' => Url::to(['workspace/limesurvey', 'id' => $workspace->id]),
        ]);
    }

    public function testDataUpdateActionProjectWrite(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $workspace =  $I->haveWorkspace();
        $I->amOnPage(['project/workspaces', 'id' => $project->id]);

        $I->dontSeeElement('a', [
            'href' => Url::to(['workspace/limesurvey', 'id' => $workspace->id]),
        ]);

        Permission::grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_WRITE);

        $I->amOnPage(['project/workspaces', 'id' => $project->id]);

        $I->seeElement('a', [
            'href' => Url::to(['workspace/limesurvey', 'id' => $workspace->id]),
        ]);


    }

    public function testUpdateActionWorkspaceAdmin(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $workspace =  $I->haveWorkspace();
        $I->amOnPage(['project/workspaces', 'id' => $project->id]);

        $I->dontSeeElement('a', [
            'href' => Url::to(['workspace/update', 'id' => $workspace->id]),
        ]);

        Permission::grant(User::findOne(['id' => TEST_USER_ID]), $workspace, Permission::PERMISSION_ADMIN);

        $I->amOnPage(['project/workspaces', 'id' => $project->id]);

        $I->seeElement('a', [
            'href' => Url::to(['workspace/update', 'id' => $workspace->id]),
        ]);
    }

    public function testUpdateActionProjectWrite(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $workspace =  $I->haveWorkspace();
        $I->amOnPage(['project/workspaces', 'id' => $project->id]);

        $I->dontSeeElement('a', [
            'href' => Url::to(['workspace/update', 'id' => $workspace->id]),
        ]);

        Permission::grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_WRITE);

        $I->amOnPage(['project/workspaces', 'id' => $project->id]);

        $I->seeElement('a', [
            'href' => Url::to(['workspace/update', 'id' => $workspace->id]),
        ]);


    }

    public function testUpdateActionProjectAdmin(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $workspace =  $I->haveWorkspace();
        $I->amOnPage(['project/workspaces', 'id' => $project->id]);

        $I->dontSeeElement('a', [
            'href' => Url::to(['workspace/update', 'id' => $workspace->id]),
        ]);

        Permission::grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_ADMIN);

        $I->amOnPage(['project/workspaces', 'id' => $project->id]);

        $I->seeElement('a', [
            'href' => Url::to(['workspace/update', 'id' => $workspace->id]),
        ]);
    }

    public function testRemoveActionProjectWrite(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $workspace =  $I->haveWorkspace();
        $I->amOnPage(['project/workspaces', 'id' => $project->id]);

        $I->dontSeeElement('a', [
            'href' => Url::to(['workspace/delete', 'id' => $workspace->id]),
        ]);

        Permission::grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_WRITE);

        $I->amOnPage(['project/workspaces', 'id' => $project->id]);

        $I->seeElement('a', [
            'href' => Url::to(['workspace/delete', 'id' => $workspace->id]),
        ]);


    }

    public function testInvalidProject(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['project/workspaces', 'id' => 12345]);
        $I->seePageNotFound();
    }

    public function testDownloadActionProjectWrite(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $workspace =  $I->haveWorkspace();
        $I->amOnPage(['project/workspaces', 'id' => $project->id]);

        $I->dontSeeElement('a', [
            'href' => Url::to(['workspace/download', 'id' => $workspace->id]),
        ]);

        Permission::grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_WRITE);

        $I->amOnPage(['project/workspaces', 'id' => $project->id]);

        $I->seeElement('a', [
            'data-code' => Url::to(['workspace/download', 'id' => $workspace->id]),
            'data-text' => Url::to(['workspace/download', 'id' => $workspace->id, 'text' => true]),
        ]);


    }

}
