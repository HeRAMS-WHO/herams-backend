<?php
declare(strict_types=1);

namespace prime\tests\functional\controllers\element;

use prime\models\ar\Permission;
use prime\tests\FunctionalTester;

class CreateCest
{
    public function testCreateChart(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $page = $I->havePage();
        $I->grantCurrentUser($page->project, Permission::PERMISSION_ADMIN);

        $I->amOnPage(['element/create', 'page_id' => $page->id, 'type' => 'chart']);
        $I->seeResponseCodeIs(200);
    }

    public function testCreateMap(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $page = $I->havePage();
        $I->grantCurrentUser($page->project, Permission::PERMISSION_ADMIN);

        $I->amOnPage(['element/create', 'page_id' => $page->id, 'type' => 'map']);
        $I->seeResponseCodeIs(200);
    }

    public function testCreateTable(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $page = $I->havePage();
        $I->grantCurrentUser($page->project, Permission::PERMISSION_ADMIN);

        $I->amOnPage(['element/create', 'page_id' => $page->id, 'type' => 'table']);
        $I->seeResponseCodeIs(200);
    }
}
