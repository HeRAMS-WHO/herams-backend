<?php
declare(strict_types=1);

namespace prime\tests\unit\objects;

use Codeception\Test\Unit;
use prime\objects\UserNotification;

/**
 * @covers \prime\objects\UserNotification
 */
class UserNotificationTest extends Unit
{
    public function test()
    {
        $testTitle = 'test title';
        $testUrl = ['test'];
        $object = new UserNotification(
            $testTitle,
            $testUrl
        );

        $this->assertEquals($testTitle, $object->getTitle());
        $this->assertEquals($testUrl, $object->getUrl());

        $testTitle .= ' changed';
        $testUrl['id'] = 'changed';

        $object
            ->setTitle($testTitle)
            ->setUrl($testUrl);

        $this->assertEquals($testTitle, $object->getTitle());
        $this->assertEquals($testUrl, $object->getUrl());
    }
}
