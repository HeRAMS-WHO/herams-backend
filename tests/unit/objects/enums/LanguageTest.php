<?php
declare(strict_types=1);

namespace prime\tests\unit\objects\enums;

use Codeception\Test\Unit;
use prime\objects\enums\Language;

/**
 * @coversNothing
 * -- Coverage does not work for enums
 */
class LanguageTest extends Unit
{
    public function testValueHasDash(): void
    {
        $this->assertSame('en-US', Language::enUS()->value);
    }

    public function testLabel(): void
    {
        $this->assertSame('English (United States)', Language::enUS()->label);
    }
}
