<?php

declare(strict_types=1);

namespace prime\tests\unit\objects\enums;

use Codeception\Test\Unit;
use prime\objects\enums\Language;

/**
 * @covers \prime\objects\enums\Language
 */
class LanguageTest extends Unit
{
    public function testLabel(): void
    {
        $this->assertSame('English (United States)', Language::enUS->label());
    }

    public function testToLocalizedArrayWithoutSourceLanguage(): void
    {
        $this->assertEquals([
            'ar' => 'Arabic',
            'fr-FR' => 'French (France)',
            'en' => 'English',
            'fr' => 'French',
        ], Language::toLocalizedArrayWithoutSourceLanguage(Language::enUS));
    }
}
