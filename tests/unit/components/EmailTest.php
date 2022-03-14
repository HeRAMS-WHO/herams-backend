<?php

declare(strict_types=1);

namespace prime\tests\components;

use Yii;
use yii\symfonymailer\Message;

final class EmailTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        \Yii::$app->mailer->messageClass = \yii\symfonymailer\Message::class;
    }

    protected function _after()
    {
    }

    public function testGetSymfonyMessage(): void
    {
        $message = new Message();
        $this->assertTrue(is_object($message->getSymfonyEmail()), 'Unable to get Symfony email!');
    }

    public function testEmailSent(): void
    {
        $message = \Yii::$app->get('mailer')->compose()
            ->setFrom('from@domain.com')
            ->setTo('test@test.com')
            ->setSubject('Test email')
            ->send();

        $this->assertTrue($message);
    }

    /**
     * @depends testEmailSent
     */
    public function testAttachFile(): void
    {
        $fileName = __FILE__;

        $message = \Yii::$app->mailer->compose()
            ->setTo('test@test.com')
            ->setFrom('someuser@somedomain.com')
            ->setSubject('Attach file')
            ->setTextBody('Test body')
            ->attach($fileName)
            ->send();

        $this->assertTrue($message);
    }
}
