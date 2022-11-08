<?php

declare(strict_types=1);

namespace prime\tests\unit\components;

use Codeception\Test\Unit;
use herams\common\helpers\Secret;
use herams\common\interfaces\EnvironmentInterface;

/**
 * @covers \herams\common\helpers\Secret
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

        $this->assertSame('secretvalue', (string) $secret);
    }
}
