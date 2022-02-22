<?php

declare(strict_types=1);

namespace prime\tests\unit\values;

use Codeception\Test\Unit;
use prime\values\IntegerId;

/**
 * @covers \prime\values\IntegerId
 */
class IntegerIdTest extends Unit
{
    private function getIntegerId(): IntegerId
    {
        return new IntegerId(1);
    }

    public function testJsonSerialize(): void
    {
        $this->assertEquals(1, $this->getIntegerId()->jsonSerialize());
    }

    public function testGetValue(): void
    {
        $this->assertEquals(1, $this->getIntegerId()->getValue());
    }

    public function testToString(): void
    {
        $this->assertEquals('1', (string) $this->getIntegerId());
    }
}
