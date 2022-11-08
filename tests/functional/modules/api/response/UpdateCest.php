<?php

declare(strict_types=1);

namespace prime\tests\functional\modules\api\response;

use herams\common\models\Project;
use prime\tests\FunctionalTester;
use yii\helpers\Url;

/**
 * @covers \prime\modules\Api\controllers\response\Update
 */
class UpdateCest
{
    public function testUpdateDoesNotUseCsrf(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $project = $I->haveProject();
        $project->visibility = Project::VISIBILITY_PUBLIC;
        $I->save($project);
        \Yii::$app->user->logout();

        $I->sendPost(Url::to(['/api/response']), [
            'json' => [
                'ab' => true,

            ],
        ]);
        $I->seeResponseCodeIs(401);
    }
}
