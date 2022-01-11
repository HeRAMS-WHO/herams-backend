<?php
declare(strict_types=1);

namespace prime\tests\functional\controllers\project;

use prime\models\ar\Permission;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\project\Pages
 */
class PagesCest
{
    public function testPageAccess(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $page = $I->havePage();
        $I->grantCurrentUser($page->project, Permission::PERMISSION_ADMIN);
        $I->amOnPage(['project/pages', 'id' => $page->project_id]);
        $I->seeResponseCodeIsSuccessful();

        $I->see($page->title);
    }
}
