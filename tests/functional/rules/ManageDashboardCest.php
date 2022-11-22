<?php

declare(strict_types=1);

namespace prime\tests\functional\rules;

use herams\common\domain\element\Element;
use herams\common\domain\user\User;
use herams\common\models\Page;
use herams\common\models\Permission;
use prime\tests\FunctionalTester;
use SamIT\abac\AuthManager;

/**
 * @coversNothing
 */
class ManageDashboardCest
{
    public function testCascade(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        /** @var AuthManager $manager */
        $manager = \Yii::$app->abacManager;
        $user = \Yii::$app->user->identity;
        $I->assertInstanceOf(User::class, $user);
        $manager->grant($user, $project, Permission::PERMISSION_MANAGE_DASHBOARD);

        $page = new Page([
            'title' => 'test',
            'project_id' => $project->id,
        ]);
        $I->save($page);

        $I->assertTrue($manager->check($user, $page, Permission::PERMISSION_WRITE));

        $element = new Element([
            'sort' => 1,
            'type' => 'chart',
            'page_id' => $page->id,
            'code' => 'q1',
        ]);
        $I->save($element);

        $I->assertTrue($manager->check($user, $element, Permission::PERMISSION_WRITE));
        $I->assertTrue(\Yii::$app->user->can(Permission::PERMISSION_WRITE, $element));
        $I->amOnPage([
            'element/update',
            'id' => $element->id,
        ]);
        $I->seeResponseCodeIs(200);
    }
}
