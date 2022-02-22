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
        $environment = $this->getMockBuilder(EnvironmentInterface::class)->getMock();
        $environment->expects($this->once())
            ->method('getSecret')
            ->with('test')
            ->willReturn('secretvalue');

        $secret = new Secret($environment, 'test');

        $this->assertSame('secretvalue', (string)$secret);
    }
}
