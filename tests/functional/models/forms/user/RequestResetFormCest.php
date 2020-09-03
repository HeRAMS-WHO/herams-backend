<?php
declare(strict_types=1);

namespace prime\tests\functional\models\forms\user;

use prime\models\ar\User;
use prime\models\forms\user\RequestResetForm;
use prime\tests\FunctionalTester;
use yii\caching\DummyCache;

class RequestResetFormCest
{

    public function testSend(FunctionalTester $I)
    {
        $form = new RequestResetForm(new DummyCache());
        $form->email = User::findOne(['id' => TEST_USER_ID])->email;
        $form->validate();
        $I->assertEmpty($form->errors);
        $I->assertTrue($form->send(\Yii::$app->mailer, \Yii::$app->urlSigner));
    }
}
