<?php
declare(strict_types=1);

namespace prime\tests\functional\controllers\project;

use Codeception\Stub;
use prime\components\Controller;
use prime\controllers\project\SyncWorkspaces;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\project\SyncWorkspaces
 */
class SyncWorkspacesCest
{
    public function testAdminPermissionIsRequired(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);

        $project = $I->haveProject();

        $I->assertFalse(\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $project));
        $I->amOnPage(['/project/sync-workspaces', 'id' => $project->id]);
        $I->seeResponseCodeIs(403);
    }

    public function testGetRequestRendersCorrectView(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);

        $project = $I->haveProject();

        $I->assertTrue(\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $project));

        $controller = Stub::make(Controller::class, [
            'render' => Stub\Expected::once(static function ($view, $params) use ($I, $project) {
                $I->assertSame('sync-workspaces-form', $view);
                $I->assertArrayHasKey('project', $params);
                $I->assertTrue($project->equals($params['project']));

                $I->assertArrayHasKey('model', $params);
                $I->assertInstanceOf(\prime\models\forms\project\SyncWorkspaces::class, $params['model']);
                return '';
            })
        ], $this);
        $action = new SyncWorkspaces('sync-workspaces', $controller);
        $action->run(\Yii::$app->request, \Yii::$app->notificationService, \Yii::$container->get(AccessCheckInterface::class), $project->id);
    }

    public function testPostRequestRendersCorrectView(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);

        $project = $I->haveProject();

        $I->assertTrue(\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $project));

        $controller = Stub::make(Controller::class, [
            'render' => Stub\Expected::once(static function ($view, $params) use ($I, $project) {
                $I->assertSame('sync-workspaces-execute', $view);
                $I->assertArrayHasKey('project', $params);
                $I->assertTrue($project->equals($params['project']));

                $I->assertArrayHasKey('model', $params);
                $I->assertInstanceOf(\prime\models\forms\project\SyncWorkspaces::class, $params['model']);
                return '';
            })
        ], $this);
        $action = new SyncWorkspaces('sync-workspaces', $controller);

        $request = \Yii::$app->request;
        $request->headers->add('X-Http-Method-Override', 'POST');
        $request->bodyParams = [
            'SyncWorkspaces' => [
                'workspaces' => [
                    15
                ]
            ]
        ];
        $action->run($request, \Yii::$app->notificationService, \Yii::$container->get(AccessCheckInterface::class), $project->id);
    }
}
