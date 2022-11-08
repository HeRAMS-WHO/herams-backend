<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\user;

use herams\common\models\Permission;
use prime\models\ar\AccessRequest;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\user\Notifications
 * @covers \prime\repositories\UserNotificationRepository
 */
class NotificationsCest
{
    public function testPageLoad(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $I->amOnPage(['user/notifications']);
        $I->seeResponseCodeIs(200);
    }

    public function testAccessRequestNotification(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $accessRequest = new AccessRequest([
            'subject' => 'test',
            'body' => 'test',
            'target' => $project,
            'permissions' => [AccessRequest::PERMISSION_WRITE],
        ]);
        $I->save($accessRequest);

        $I->amLoggedInAs(TEST_OTHER_USER_ID);
        $I->grantCurrentUser($project, Permission::PERMISSION_ADMIN);

        $I->amOnPage(['user/notifications']);
        $I->see('There are open access requests that you can respond to');
    }
}
