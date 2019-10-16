<?php


namespace prime\tests\functional\actions;

use prime\actions\DeleteAction;
use prime\models\ar\Response;
use prime\tests\FunctionalTester;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;

class DeleteActionCest
{

    public function testInitNoConfig(FunctionalTester $I)
    {
        $I->expectThrowable(InvalidConfigException::class, function () {
            new DeleteAction('delete', null);
        });
    }

    public function testRecordNotFound(FunctionalTester $I)
    {
        $action = new DeleteAction('delete', null, [
            'query' => Response::find()->andWhere('0 = 1')
        ]);
        $I->expectException(NotFoundHttpException::class, function() use ($action) {
            $action->run(\Yii::$app->user, \Yii::$app->notificationService, 51);
        });
    }
}