<?php

declare(strict_types=1);

namespace prime\tests\unit\models\forms\user;

use Carbon\Carbon;
use Codeception\Test\Unit;
use prime\models\forms\user\RequestAccountForm;
use yii\caching\CacheInterface;

/**
 * @covers \prime\models\forms\user\RequestAccountForm
 */
class RequestAccountFormTest extends Unit
{
    public function testTooManyRequests()
    {
        $email = 'testRequest@test.com';
        $cache = $this->getMockBuilder(CacheInterface::class)->getMock();
        $cache->expects($this->once())
            ->method('get')
            ->with(RequestAccountForm::class . $email)
            ->willReturn((new Carbon())->addMinutes(2)->timestamp);

        $model = new RequestAccountForm($cache);
        $model->email = $email;
        $this->assertFalse($model->validate());
    }
}
