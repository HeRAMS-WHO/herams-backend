<?php

declare(strict_types=1);

namespace prime\tests\functional\mails;

use herams\common\models\Project;
use prime\models\ar\AccessRequest;
use prime\tests\FunctionalTester;
use yii\helpers\Url;

/**
 * Should cover email view file
 * @coversNothing
 */
class AccessRequestCreatedNotificationCest
{
    public function testContent(FunctionalTester $I)
    {
        $target = new Project([
            'title' => 'Test project',
        ]);
        $accessRequest = new AccessRequest([
            'id' => 12345,
            'subject' => 'Test access request',
            'target' => $target,
        ]);
        $accessRequest->populateRelation('target', $target);

        $url = Url::to([
            '/access-request/respond',
            'id' => $accessRequest->id,
        ], true);

        \Yii::$app->mailer->compose(
            'access_request_created_notification',
            [
                'respondUrl' => $url,
                'accessRequest' => $accessRequest,
            ]
        )
            ->setTo('test@test.com')
            ->send()
        ;

        $htmlContent = $I->grabHtmlContentFromLastSentEmail();
        $I->assertStringContainsString($url, $htmlContent);
    }
}
