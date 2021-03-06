<?php


namespace prime\tests\functional\actions;

use prime\actions\DeleteAction;
use prime\models\ar\Response;
use prime\tests\FunctionalTester;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;
use yii\web\Request;

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
        \Yii::$app->set('request', new class extends Request {
            public function getIsDelete()
            {
                return true;
            }
        });
        $action = new DeleteAction('delete', null, [
            'query' => Response::find()->andWhere('0 = 1')
        ]);
        $I->expectThrowable(NotFoundHttpException::class, function () use ($action) {
            $action->run(\Yii::$app->user, \Yii::$app->notificationService, 51);
        });
    }
}
