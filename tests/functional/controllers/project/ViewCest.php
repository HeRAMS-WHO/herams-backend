<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\project;

use Codeception\Scenario;
use herams\common\domain\user\User;
use herams\common\models\Page;
use herams\common\models\PermissionOld;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\project\View
 */
class ViewCest
{
    public function testView(FunctionalTester $I, Scenario $scenario)
    {
        $scenario->incomplete('Project dashboard is open to all users');
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $I->amOnPage([
            'project/view',
            'id' => $project->id,
        ]);
        $I->seeResponseCodeIs(403);
    }

    public function testViewWithRead(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        \Yii::$app->abacManager->grant(User::findOne([
            'id' => TEST_USER_ID,
        ]), $project, PermissionOld::PERMISSION_READ);
        $I->amOnPage([
            'project/view',
            'id' => $project->id,
        ]);
        $I->seeResponseCodeIs(404);
    }

    public function testViewWithPageAndRead(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $page = new Page();
        $page->title = 'Main page';
        $page->project_id = $project->id;
        $I->save($page);
        \Yii::$app->abacManager->grant(User::findOne([
            'id' => TEST_USER_ID,
        ]), $project, PermissionOld::PERMISSION_READ);
        $I->amOnPage([
            'project/view',
            'id' => $project->id,
        ]);
        $I->seeResponseCodeIs(200);

        $I->assertSame($page->title, $I->grabTextFrom('.header'));
    }

    public function testViewBadSurvey(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $project->base_survey_eid = 11111;
        $I->save($project);
        $page = new Page();
        $page->title = 'Main page';
        $page->project_id = $project->id;
        $I->save($page);
        \Yii::$app->abacManager->grant(User::findOne([
            'id' => TEST_USER_ID,
        ]), $project, PermissionOld::PERMISSION_READ);
        $I->amOnPage([
            'project/view',
            'id' => $project->id,
        ]);
        $I->seeResponseCodeIs(200);
        $I->assertSame($page->title, $I->grabTextFrom('.header'));
    }

    public function testWrongPage(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $page = new Page();
        $page->title = 'Main page';
        $page->project_id = $project->id;
        $I->save($page);
        \Yii::$app->abacManager->grant(User::findOne([
            'id' => TEST_USER_ID,
        ]), $project, PermissionOld::PERMISSION_READ);
        $I->amOnPage([
            'project/view',
            'id' => $project->id,
            'page_id' => $page->id + 1,
        ]);
        $I->seeResponseCodeIs(404);
    }

    public function testOtherPage(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        \Yii::$app->abacManager->grant(User::findOne([
            'id' => TEST_USER_ID,
        ]), $project, PermissionOld::PERMISSION_READ);

        $page = new Page();
        $page->title = 'Main page';
        $page->project_id = $project->id;
        $I->save($page);

        $otherPage = new Page();
        $otherPage->title = 'Other page';
        $otherPage->project_id = $project->id;
        $I->save($otherPage);

        $I->amOnPage([
            'project/view',
            'id' => $project->id,
            'page_id' => $otherPage->id,
        ]);
        $I->seeResponseCodeIs(200);
        $I->assertSame($otherPage->title, $I->grabTextFrom('.header'));
    }

    public function testChildPage(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        \Yii::$app->abacManager->grant(User::findOne([
            'id' => TEST_USER_ID,
        ]), $project, PermissionOld::PERMISSION_READ);

        $page = new Page();
        $page->title = 'Main page';
        $page->project_id = $project->id;
        $I->save($page);

        $child = new Page();
        $child->parent_id = $page->id;
        $child->title = 'Child page';
        $child->project_id = $project->id;
        $I->save($child);

        $I->amOnPage([
            'project/view',
            'id' => $project->id,
            'page_id' => $child->id,
            'parent_id' => $child->parent_id,
        ]);
        $I->seeResponseCodeIs(200);
        $I->assertSame($child->title, $I->grabTextFrom('.header'));
    }
}
