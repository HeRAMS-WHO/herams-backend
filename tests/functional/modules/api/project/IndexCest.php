<?php

declare(strict_types=1);

namespace prime\tests\functional\modules\api\project;

use prime\models\ar\Project;
use prime\tests\FunctionalTester;
use yii\helpers\Url;

/**
 * @covers \prime\modules\Api\controllers\project\Index
 */
class IndexCest
{
    public function testIndex(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProjectForLimesurvey();
        $project->visibility = Project::VISIBILITY_PUBLIC;
        $I->save($project);

        $I->sendGET(Url::to(['/api/project/index']));

        $I->seeResponseCodeIs(200);
        $data = json_decode($I->grabResponse(), true);
        $latestProject = array_pop($data);
        foreach ($data as $projectEntry) {
            $I->assertNotSame($project->id, $projectEntry['id']);
        }
        $I->assertSame($project->id, $latestProject['id']);
    }

    public function testIndexDoesNotContainHidden(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProjectForLimesurvey();
        $project->visibility = Project::VISIBILITY_HIDDEN;
        $I->save($project);

        $I->sendGET(Url::to(['/api/project/index']));

        $I->seeResponseCodeIs(200);
        $data = json_decode($I->grabResponse(), true);
        foreach ($data as $projectEntry) {
            $I->assertNotSame($project->id, $projectEntry['id']);
        }
    }

    public function testIndexDoesNotContainPrivate(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProjectForLimesurvey();
        $project->visibility = Project::VISIBILITY_PRIVATE;
        $I->save($project);

        $I->sendGET(Url::to(['/api/project/index']));

        $I->seeResponseCodeIs(200);
        $data = json_decode($I->grabResponse(), true);
        foreach ($data as $projectEntry) {
            $I->assertNotSame($project->id, $projectEntry['id']);
        }
    }
}
