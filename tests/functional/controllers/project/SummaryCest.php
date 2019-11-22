<?php


namespace prime\tests\functional\controllers\project;

use Codeception\Scenario;
use prime\models\ar\Page;
use prime\models\ar\User;
use prime\models\permissions\Permission;
use prime\tests\FunctionalTester;

class SummaryCest
{

    public function testSummaryNoDetail(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $I->amOnPage(['project/summary', 'id' => $project->id]);
        $I->seeResponseCodeIs(200);
        $I->dontSeeLink('Details');
    }

    public function testInvalidProject(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['project/summary', 'id' => 12345]);
        $I->seePageNotFound();
    }

    public function testSummaryWithReadNoDetail(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $I->amOnPage(['project/summary', 'id' => $project->id]);
        $I->seeResponseCodeIs(200);
        $I->dontSeeLink('Details');
    }

    public function testSummaryWithPageNoDetail(FunctionalTester $I, Scenario $scenario)
    {
        $scenario->incomplete('Currently we allow read to everyone');
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $page = new Page();
        $page->title = 'Main page';
        $page->project_id = $project->id;
        $I->save($page);
        $I->amOnPage(['project/summary', 'id' => $project->id]);
        $I->seeResponseCodeIs(200);
        $I->dontSeeLink('Details');
    }

    public function testSummaryWithPageAndReadDetail(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        Permission::grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_READ);
        $page = new Page();
        $page->title = 'Main page';
        $page->project_id = $project->id;
        $I->save($page);
        $I->amOnPage(['project/summary', 'id' => $project->id]);
        $I->seeResponseCodeIs(200);
        $I->seeLink('Details');
    }

    public function testSummaryWithPageAndWriteDetail(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        Permission::grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_WRITE);
        $page = new Page();
        $page->title = 'Main page';
        $page->project_id = $project->id;
        $I->save($page);
        $I->amOnPage(['project/summary', 'id' => $project->id]);
        $I->seeResponseCodeIs(200);
        $I->seeLink('Details');
    }

    public function testSummaryWithPageAndAdminDetail(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        Permission::grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_ADMIN);
        $page = new Page();
        $page->title = 'Main page';
        $page->project_id = $project->id;
        $I->save($page);
        $I->amOnPage(['project/summary', 'id' => $project->id]);
        $I->seeResponseCodeIs(200);
        $I->seeLink('Details');
    }

    public function testSummaryWithPageAdminDetail(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $project = $I->haveProject();
        $page = new Page();
        $page->title = 'Main page';
        $page->project_id = $project->id;
        $I->save($page);
        $I->amOnPage(['project/summary', 'id' => $project->id]);
        $I->seeResponseCodeIs(200);
        $I->seeLink('Details');
    }
}