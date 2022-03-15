<?php

declare(strict_types=1);

namespace prime\tests\components;

use Yii;
use yii\symfonymailer\Message;

final class EmailTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        //\Yii::$app->mailer->messageClass = \yii\symfonymailer\Message::class;
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
            #->setFrom('from@test.com')
            ->setTo('to@test.com')
            ->setSubject('Test email')
            ->setTextBody('See you later.');

        $this->assertSame('Test email', $message->getSubject());
        $this->assertSame('support@herams.org', key($message->getFrom()));
        $this->assertSame('to@test.com', key($message->getTo()));
        $this->assertSame('See you later.', $message->getTextBody());
        $this->assertTrue($message->send());
    }

    /**
     * @depends testEmailSent
     */
    public function testHtmlEmailSent(): void
    {
        $message = \Yii::$app->get('mailer')->compose('layouts/html', ['content' => 'See you later.'])
            ->setTo('to@test.com')
            ->setSubject('Test email');

        $this->assertTrue($message->send());
    }

    /**
     * @depends testEmailSent
     */
    public function testEmailWithAttachedFile(): void
    {
        $fileName = __FILE__;

        $message = \Yii::$app->mailer->compose()
            ->setTo('test@test.com')
            ->setSubject('Attach file')
            ->setTextBody('Test body')
            ->attach($fileName)
            ->send();

        $this->assertTrue($message);
    }
}
