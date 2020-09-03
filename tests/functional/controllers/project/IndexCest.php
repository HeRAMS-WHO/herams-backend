<?php


namespace prime\tests\functional\controllers\project;

use prime\models\ar\Page;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\User;
use prime\tests\FunctionalTester;
use yii\helpers\Url;

class IndexCest
{

    public function testAccessControl(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['project/index']);
        $I->seeResponseCodeIs(200);
    }

    public function testNoLogin(FunctionalTester $I)
    {
        $I->stopFollowingRedirects();
        $I->amOnPage(['project/index']);
        $I->seeResponseCodeIs(302);
    }

    public function testDashboardAction(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $project->visibility = Project::VISIBILITY_PRIVATE;
        $I->save($project);
        $I->amOnPage(['project/index']);

        $I->dontSeeElement('a', [
            'href' => Url::to(['project/view', 'id' => $project->id]),
        ]);

        $page = new Page();
        $page->title = 'test';
        $page->project_id = $project->id;
        $I->save($page);

        $I->amOnPage(['project/index']);

        $I->dontSeeElement('a', [
            'href' => Url::to(['project/view', 'id' => $project->id]),
        ]);

        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_READ);
        $I->amOnPage(['project/index']);

        $I->seeElement('a', [
            'href' => Url::to(['project/view', 'id' => $project->id]),
        ]);
    }

    public function testUpdateAction(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $I->amOnPage(['project/index']);

        $I->dontSeeElement('a', [
            'href' => Url::to(['project/update', 'id' => $project->id]),
        ]);

        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_READ);
        $I->amOnPage(['project/index']);

        $I->dontSeeElement('a', [
            'href' => Url::to(['project/update', 'id' => $project->id]),
        ]);

        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_WRITE);
        $I->amOnPage(['project/index']);

        $I->seeElement('a', [
            'href' => Url::to(['project/update', 'id' => $project->id]),
        ]);
    }

    public function testShareAction(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $I->amOnPage(['project/index']);

        $I->dontSeeElement('a', [
            'href' => Url::to(['project/share', 'id' => $project->id]),
        ]);

        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_READ);
        $I->amOnPage(['project/index']);

        $I->dontSeeElement('a', [
            'href' => Url::to(['project/share', 'id' => $project->id]),
        ]);

        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_WRITE);
        $I->amOnPage(['project/index']);

        $I->dontSeeElement('a', [
            'href' => Url::to(['project/share', 'id' => $project->id]),
        ]);

        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_ADMIN);
        $I->amOnPage(['project/index']);

        $I->seeElement('a', [
            'href' => Url::to(['project/share', 'id' => $project->id]),
        ]);
    }

    public function testWorkspacesAction(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $I->amOnPage(['project/index']);

        $I->seeElement('a', [
            'href' => Url::to(['project/workspaces', 'id' => $project->id]),
        ]);
    }
}
