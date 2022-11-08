<?php

declare(strict_types=1);

namespace prime\tests\unit\models\user;

use Codeception\Test\Unit;
use herams\common\domain\user\User;
use herams\common\values\UserId;
use prime\models\user\UserForSelect2;

/**
 * @covers \prime\models\user\UserForSelect2
 */
class UserForSelect2Test extends Unit
{
    public function testConstructor(): void
    {
        $email = 'test@test.com';
        $id = 12345;
        $name = 'Test user';

        $user = new User();
        $user->email = $email;
        $user->id = $id;
        $user->name = $name;

        $forSelect2 = new UserForSelect2($user);
        $this->assertEquals(new UserId($id), $forSelect2->getUserId());
        $this->assertEquals("{$name} ({$email})", $forSelect2->getText());
    }
}
