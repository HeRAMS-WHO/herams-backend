<?php

declare(strict_types=1);

namespace prime\tests\unit\models\forms\user;

use Codeception\Test\Unit;
use prime\models\ar\User;
use prime\models\forms\user\CreateForm;

/**
 * @covers \prime\models\forms\user\CreateForm
 */
class CreateFormTest extends Unit
{
    public function testTooManyRequests()
    {
        $user = $this->getMockBuilder(User::class)->getMock();
        $user->expects($this->once())
            ->method('save')
            ->willReturn(false);

        $model = new CreateForm($user);
        $this->expectExceptionObject(new \RuntimeException('Failed to create user'));
        $model->run();
    }
}
