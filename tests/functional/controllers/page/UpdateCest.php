<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\page;

use herams\common\models\Page;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\page\Update
 */
class UpdateCest
{
    public function _before(FunctionalTester $I)
    {
        $I->assertTrue(\Yii::$app->authManager->checkAccess(TEST_ADMIN_ID, 'admin'));
        $I->assertFalse(\Yii::$app->authManager->checkAccess(TEST_USER_ID, 'admin'));
    }

    public function testAccessControl(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $project = $I->haveProject();

        $parentPage = new Page();
        $parentPage->title = 'parent';
        $parentPage->project_id = $project->id;
        $I->save($parentPage);

        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage([
            'page/update',
            'id' => $parentPage->id,
        ]);
        $I->seeResponseCodeIs(403);
    }

    /**
     * @incomplete
     */
    public function testUpdate(FunctionalTester $I)
    {
    }

    /**
     * @incomplete
     */
    public function testUpdateParent(FunctionalTester $I)
    {
    }
}
