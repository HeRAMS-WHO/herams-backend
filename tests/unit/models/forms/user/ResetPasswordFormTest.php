<?php
declare(strict_types=1);

namespace prime\tests\unit\models\forms\user;

use Carbon\Carbon;
use Codeception\Test\Unit;
use prime\models\ar\User;
use prime\models\forms\user\RequestAccountForm;
use prime\models\forms\user\ResetPasswordForm;
use yii\base\InvalidConfigException;
use yii\caching\CacheInterface;

/**
 * @covers \prime\models\forms\user\ResetPasswordForm
 */
class ResetPasswordFormTest extends Unit
{
    public function testResetPasswordWhileInvalid()
    {
        $user = new User();
        $model = new ResetPasswordForm($user);

        $this->expectExceptionObject(new InvalidConfigException(\Yii::t('app', 'Validation failed')));
        $model->resetPassword();
    }
}
