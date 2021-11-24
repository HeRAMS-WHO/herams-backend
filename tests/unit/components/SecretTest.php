<?php

declare(strict_types=1);

namespace prime\tests\unit\components;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\Rule\InvokedAtLeastOnce;
use prime\components\Secret;
use prime\interfaces\EnvironmentInterface;

/**
 * @covers \prime\components\Secret
 */
class SecretTest extends Unit
{
    public function testToString()
    {
        $environment = $this->makeEmpty(EnvironmentInterface::class, [
            'getSecret' => Expected::once(function ($param) {
                $this->assertSame('test', $param);
                return 'secretvalue';
            })
        ]);

        $secret = new Secret($environment, 'test');

        $this->assertSame('secretvalue', (string)$secret);
    }
}
