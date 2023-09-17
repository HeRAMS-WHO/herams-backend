<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\project;

use herams\common\domain\user\User;
use herams\common\models\Page;
use herams\common\models\PermissionOld;
use herams\common\models\Project;
use prime\tests\FunctionalTester;
use yii\helpers\Url;

/**
 * @covers \prime\controllers\project\Index
 */
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
        $I->markTestSkipped('Action column hidden');
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $project->visibility = Project::VISIBILITY_PRIVATE;
        $I->save($project);
        $I->amOnPage(['project/index']);

        $I->dontSeeElement('a', [
            'href' => Url::to([
                'project/view',
                'id' => $project->id,
            ]),
        ]);

        $page = new Page();
        $page->title = 'test';
        $page->project_id = $project->id;
        $I->save($page);

        $I->amOnPage(['project/index']);

        $I->dontSeeElement('a', [
            'href' => Url::to([
                'project/view',
                'id' => $project->id,
            ]),
        ]);

        \Yii::$app->abacManager->grant(User::findOne([
            'id' => TEST_USER_ID,
        ]), $project, PermissionOld::PERMISSION_READ);
        $I->amOnPage(['project/index']);

        $I->seeElement('a', [
            'href' => Url::to([
                'project/view',
                'id' => $project->id,
            ]),
        ]);
    }

    public function testUpdateAction(FunctionalTester $I)
    {
        $I->markTestSkipped('Action column hidden');
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $I->amOnPage(['project/index']);

        $I->dontSeeElement('a', [
            'href' => Url::to([
                'project/update',
                'id' => $project->id,
            ]),
        ]);

        \Yii::$app->abacManager->grant(User::findOne([
            'id' => TEST_USER_ID,
        ]), $project, PermissionOld::PERMISSION_READ);
        $I->amOnPage(['project/index']);

        $I->dontSeeElement('a', [
            'href' => Url::to([
                'project/update',
                'id' => $project->id,
            ]),
        ]);

        \Yii::$app->abacManager->grant(User::findOne([
            'id' => TEST_USER_ID,
        ]), $project, PermissionOld::PERMISSION_WRITE);
        $I->amOnPage(['project/index']);

        $I->seeElement('a', [
            'href' => Url::to([
                'project/update',
                'id' => $project->id,
            ]),
        ]);
    }

    public function testShareAction(FunctionalTester $I)
    {
        $I->markTestSkipped('Action column hidden');
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $I->amOnPage(['project/index']);

        $I->dontSeeElement('a', [
            'href' => Url::to([
                'project/share',
                'id' => $project->id,
            ]),
        ]);

        \Yii::$app->abacManager->grant(User::findOne([
            'id' => TEST_USER_ID,
        ]), $project, PermissionOld::PERMISSION_READ);
        $I->amOnPage(['project/index']);

        $I->dontSeeElement('a', [
            'href' => Url::to([
                'project/share',
                'id' => $project->id,
            ]),
        ]);

        \Yii::$app->abacManager->grant(User::findOne([
            'id' => TEST_USER_ID,
        ]), $project, PermissionOld::PERMISSION_WRITE);
        $I->amOnPage(['project/index']);

        $I->dontSeeElement('a', [
            'href' => Url::to([
                'project/share',
                'id' => $project->id,
            ]),
        ]);

        \Yii::$app->abacManager->grant(User::findOne([
            'id' => TEST_USER_ID,
        ]), $project, PermissionOld::PERMISSION_ADMIN);
        $I->amOnPage(['project/index']);

        $I->seeElement('a', [
            'href' => Url::to([
                'project/share',
                'id' => $project->id,
            ]),
        ]);
    }

    public function testWorkspacesAction(FunctionalTester $I)
    {
        // Normal visibility
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $I->amOnPage(['project/index']);

        $I->seeElement('a', [
            'href' => Url::to([
                'project/workspaces',
                'id' => $project->id,
            ]),
        ]);

        // Private visibility
        $project->visibility = Project::VISIBILITY_PRIVATE;
        $I->save($project);
        $I->amOnPage(['project/index']);

        // Don't see the link
        $I->dontSeeElement('a', [
            'href' => Url::to([
                'project/workspaces',
                'id' => $project->id,
            ]),
        ]);
        // But do see the project title listed
        $I->see($project->title, 'table tr td');

        // Hidden visibility
        $project->visibility = Project::VISIBILITY_HIDDEN;
        $I->save($project);
        $I->amOnPage(['project/index']);

        // Don't see the link
        $I->dontSeeElement('a', [
            'href' => Url::to([
                'project/workspaces',
                'id' => $project->id,
            ]),
        ]);
        // Don't see the project title listed
        $I->dontSee($project->title, 'table tr td');
    }
}
