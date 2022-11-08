<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\page;

use herams\common\models\Page;
use herams\common\models\Permission;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\page\Create
 */
class CreateCest
{
    public function _before(FunctionalTester $I)
    {
        $I->assertTrue(\Yii::$app->authManager->checkAccess(TEST_ADMIN_ID, Permission::PERMISSION_ADMIN));
        $I->assertFalse(\Yii::$app->authManager->checkAccess(TEST_USER_ID, Permission::PERMISSION_ADMIN));
    }

    public function testAccessControl(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $project = $I->haveProject();

        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage([
            'page/create',
            'project_id' => $project->id,
        ]);
        $I->seeResponseCodeIs(403);
    }

    public function testCreateRootPage(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $project = $I->haveProject();
        $I->amOnPage([
            'page/create',
            'project_id' => $project->id,
        ]);
        $I->seeResponseCodeIs(200);
        $I->fillField([
            'name' => 'Page[title]',
        ], 'Test');
        $I->click('Create page');
        $I->seeResponseCodeIsSuccessful();
        $I->seeRecord(Page::class, [
            'project_id' => $project->id,
            'title' => 'Test',
        ]);
    }

    public function testCreateInvalidProject(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $I->amOnPage([
            'page/create',
            'project_id' => 1245,
        ]);
        $I->seeResponseCodeIs(404);
    }

    public function testCreateSubPage(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $project = $I->haveProject();
        $parentPage = new Page();
        $parentPage->title = 'parent';
        $parentPage->project_id = $project->id;
        $I->save($parentPage);
        $I->amOnPage([
            'page/create',
            'project_id' => $project->id,
        ]);
        $I->seeResponseCodeIs(200);
        $I->fillField([
            'name' => 'Page[title]',
        ], 'Test');
        $I->selectOption([
            'name' => 'Page[parent_id]',
        ], $parentPage->id);
        $I->click('Create page');
        $I->seeResponseCodeIsSuccessful();
        $I->seeRecord(Page::class, [
            'project_id' => $project->id,
            'parent_id' => $parentPage->id,
            'title' => 'Test',
        ]);
    }
}
