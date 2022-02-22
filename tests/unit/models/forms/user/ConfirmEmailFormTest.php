<?php

declare(strict_types=1);

namespace prime\tests\unit\models\forms\user;

use Codeception\Test\Unit;
use prime\models\ar\User;
use prime\models\forms\user\ConfirmEmailForm;

/**
 * @covers \prime\models\forms\user\ConfirmEmailForm
 */
class ConfirmEmailFormTest extends Unit
{
    public function testChangedEmailValidation()
    {
        $oldEmail = 'old@test.com';
        $newEmail = 'new@test.com';
        $user = new User(['email' => $newEmail]);

        $model = new ConfirmEmailForm($user, $newEmail, $oldEmail);
        $this->assertFalse($model->validate());
        $this->assertEquals(
            'Your email address has already been changed',
            $model->getFirstError('newMail')
        );
    }

    public function testFailedSave()
    {
        $oldEmail = 'old@test.com';
        $newEmail = 'new@test.com';
        $user = new User(['email' => $newEmail]);

        $model = new ConfirmEmailForm($user, $newEmail, $oldEmail);
        $this->expectExceptionObject(new \RuntimeException('Failed to update email address'));
        $model->run();
    }
}
