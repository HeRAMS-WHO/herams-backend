<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\project;

use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\User;
use prime\tests\FunctionalTester;

/**
 * @covers \prime\controllers\project\Update
 */
final class UpdateCest
{
    public function testAccessControl(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();

        $I->amOnPage([
            'project/update',
            'id' => $project->id,
        ]);
        $I->seeResponseCodeIs(403);
    }

    public function testAccessControlWithWriteAccess(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        \Yii::$app->abacManager->grant(User::findOne([
            'id' => TEST_USER_ID,
        ]), $project, Permission::PERMISSION_WRITE);
        $I->assertTrue(\Yii::$app->user->can(Permission::PERMISSION_WRITE, $project));

        $I->amOnPage([
            'project/update',
            'id' => $project->id,
        ]);
        $I->seeResponseCodeIs(200);
        $I->dontSee('Delete project');
        $I->dontSee('Empty project');
    }

    public function testAccessControlWithAdminAccess(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        \Yii::$app->abacManager->grant(User::findOne([
            'id' => TEST_USER_ID,
        ]), $project, Permission::PERMISSION_ADMIN);

        $I->assertTrue(\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $project));
        $I->assertTrue(\Yii::$app->user->can(Permission::PERMISSION_WRITE, $project));
        $I->assertTrue(\Yii::$app->user->can(Permission::PERMISSION_DELETE_ALL_WORKSPACES, $project));
        $I->amOnPage([
            'project/update',
            'id' => $project->id,
        ]);
        $I->seeResponseCodeIs(200);
        $I->see('Delete project');
        $I->see('Empty project');
    }

    public function testUpdate(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        \Yii::$app->abacManager->grant(User::findOne([
            'id' => TEST_USER_ID,
        ]), $project, Permission::PERMISSION_ADMIN);

        $I->amOnPage([
            'project/update',
            'id' => $project->id,
        ]);
        $I->seeResponseCodeIs(200);

        $attributes = [
            'title' => 'test123',
            'latitude' => 1.43,
            'longitude' => 1.55,
        ];

        foreach ($attributes as $key => $value) {
            $I->fillField([
                'name' => "Project[$key]",
            ], $value);
        }

        $overrides = [
            'typeCounts' => [
                'A1' => 15,
                'A2' => 30,
            ],
            'facilityCount' => 20,
            'contributorCount' => 40,
        ];
        $I->fillField([
            'name' => "Project[overrides]",
        ], json_encode($overrides));

        $typemap = [
            'A1' => 'primary',
            'A2' => 'primary',
            'A3' => 'primary',
            'A4' => 'secondary',
            'A5' => 'secondary',
            'A6' => 'secondary',
        ];
        $I->fillField([
            'name' => "Project[typemap]",
        ], json_encode($typemap));

        $options = [
            'status' => Project::STATUS_EMERGENCY_SPECIFIC,
        ];
        foreach ($options as $key => $value) {
            $I->selectOption([
                'name' => "Project[$key]",
            ], $value);
        }

        $I->click('Update');
        $I->seeResponseCodeIsSuccessful();
        $project->refresh();
        foreach ($attributes as $key => $value) {
            $I->assertEquals($value, $project->$key, '', 0.001);
        }
        $I->assertSame($overrides, $project->overrides);
        $I->assertSame($typemap, $project->typemap);

        foreach ($options as $key => $value) {
            $I->assertEquals($value, $project->$key, '', 0.001);
        }
    }
}
