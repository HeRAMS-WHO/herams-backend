<?php

declare(strict_types=1);

namespace prime\tests\unit\values;

use Codeception\Test\Unit;
use herams\common\values\StringId;

/**
 * @covers \herams\common\values\StringId
 */
class StringIdTest extends Unit
{
    private function getStringId(): StringId
    {
        return new StringId('id');
    }

    public function testGetValue(): void
    {
        $this->assertEquals('id', $this->getStringId()->getValue());
    }
}
