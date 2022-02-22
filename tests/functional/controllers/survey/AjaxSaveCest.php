<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\survey;

use prime\models\ar\Survey;
use prime\tests\FunctionalTester;
use yii\web\Request;

/**
 * @covers \prime\controllers\survey\AjaxSave
 */
class AjaxSaveCest
{
    public function testCreate(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $I->createAndSetCsrfCookie('abc');
        $I->haveHttpHeader(Request::CSRF_HEADER, \Yii::$app->security->maskToken('abc'));

        $I->sendPost(
            'survey/ajax-save',
            [
                'config' => [
                    'pages' => [
                        0 => [
                            'name' => 'testPage1',
                            'elements' => [
                                0 =>
                                    [
                                        'type' => 'text',
                                        'name' => 'question1',
                                        'title' => 'title1',
                                    ],
                            ],
                        ],
                    ],
                ]
            ]
        );
        $I->seeResponseCodeIsSuccessful();
        $I->seeRecord(Survey::class, ['and', ['like', 'config', 'testPage1']]);
    }

    public function testUpdate(FunctionalTester $I): void
    {
        $I->amLoggedInAs(TEST_ADMIN_ID);
        $I->createAndSetCsrfCookie('abc');
        $I->haveHttpHeader(Request::CSRF_HEADER, \Yii::$app->security->maskToken('abc'));

        $survey = $I->haveAdminSurvey();

        $I->sendPost(
            'survey/ajax-save?id=' . $survey->id,
            [
                'config' => [
                    'pages' => [
                        0 => [
                            'name' => 'testPage2',
                            'elements' => [
                                0 =>
                                    [
                                        'type' => 'text',
                                        'name' => 'question1',
                                        'title' => 'title1',
                                    ],
                            ],
                        ],
                    ],
                ]
            ]
        );
        $I->seeResponseCodeIsSuccessful();
        $I->seeRecord(Survey::class, ['and', ['like', 'config', 'testPage2']]);
    }
}
