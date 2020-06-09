<?php


namespace prime\tests\functional\controllers\project;

use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\User;
use prime\tests\FunctionalTester;
use yii\helpers\Json;

class UpdateCest
{

    public function testAccessControl(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();

        $I->amOnPage(['project/update', 'id' => $project->id]);
        $I->seeResponseCodeIs(403);
    }

    public function testAccessControlWithWriteAccess(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_WRITE);
        $I->assertTrue(\Yii::$app->user->can(Permission::PERMISSION_WRITE, $project));

        $I->amOnPage(['project/update', 'id' => $project->id]);
        $I->seeResponseCodeIs(200);
    }

    public function testAccessControlWithAdminAccess(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_ADMIN);

        $I->assertTrue(\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $project));
        $I->assertTrue(\Yii::$app->user->can(Permission::PERMISSION_WRITE, $project));
        $I->amOnPage(['project/update', 'id' => $project->id]);
        $I->seeResponseCodeIs(200);
    }

    public function testUpdate(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        \Yii::$app->abacManager->grant(User::findOne(['id' => TEST_USER_ID]), $project, Permission::PERMISSION_ADMIN);

        $I->amOnPage(['project/update', 'id' => $project->id]);
        $I->seeResponseCodeIs(200);

        $attributes = [
            'title' => 'test123',
            'latitude' => 1.43,
            'longitude' => 1.55,
            'typemapAsJson' => Json::encode([
                'A1' => 'primary',
                'A2' => 'primary',
                'A3' => 'primary',
                'A4' => 'secondary',
                'A5' => 'secondary',
                'A6' => 'secondary'
            ], JSON_PRETTY_PRINT),
            'overridesAsJson' => Json::encode([
                'typeCounts' => [
                    'A1' => 15,
                    'A2' => 30
                ],
                'facilityCount' => 20,
                'contributorCount' => 40
            ], JSON_PRETTY_PRINT)
        ];

        foreach($attributes as $key => $value) {
            $I->fillField(['name' => "Project[$key]"], $value);
        }

        $options = [
            'status' => Project::STATUS_EMERGENCY_SPECIFIC,
        ];
        foreach($options as $key => $value) {
            $I->selectOption(['name' => "Project[$key]"], $value);
        }
        $I->click('Update project');
        $I->seeResponseCodeIsSuccessful();
        $project->refresh();
        foreach($attributes as $key => $value) {
            $I->assertEquals($value, $project->$key, '', 0.001);
        }

        foreach($options as $key => $value) {
            $I->assertEquals($value, $project->$key, '', 0.001);
        }
    }
}