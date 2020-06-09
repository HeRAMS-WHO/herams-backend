<?php
declare(strict_types=1);

namespace prime\tests\functional\rules;

use prime\models\ar\Element;
use prime\models\ar\Page;
use prime\models\ar\Permission;
use prime\models\ar\User;
use prime\tests\FunctionalTester;
use SamIT\abac\AuthManager;

class ManageDashboardCest
{

    public function testCascade(FunctionalTester $I)
    {
        $project = $I->haveProject();
        /** @var AuthManager $manager */
        $manager = \Yii::$app->abacManager;
        $I->amLoggedInAs(TEST_USER_ID);
        $user = \Yii::$app->user->identity;
        $I->assertInstanceOf(User::class, $user);
        $manager->grant($user, $project, Permission::PERMISSION_MANAGE_DASHBOARD);

        $page = new Page([
            'title' => 'test',
            'project_id' => $project->id
        ]);
        $I->save($page);

        $I->assertTrue($manager->check($user, $page, Permission::PERMISSION_WRITE));

        $element = new Element([
            'sort' => 1,
            'type' => 'chart',
            'page_id' => $page->id,
            'code' => 'q1'
        ]);
        $I->save($element);

        $I->assertTrue($manager->check($user, $element, Permission::PERMISSION_WRITE));
        $I->assertTrue(\Yii::$app->user->can(Permission::PERMISSION_WRITE, $element));
        $I->amOnPage(['element/update', 'id' => $element->id]);
        $I->seeResponseCodeIs(200);
    }
}