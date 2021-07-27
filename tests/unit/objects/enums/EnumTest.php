<?php
declare(strict_types=1);

namespace prime\tests\unit\objects\enums;

use Codeception\Test\Unit;
use prime\objects\enums\Language;
use prime\objects\enums\ProjectStatus;
use prime\validators\EnumValidator;

/**
 * @covers \prime\objects\enums\Enum
 */
class EnumTest extends Unit
{
    public function testFromForm(): void
    {
        $this->assertEquals(null, ProjectStatus::fromForm(null));
        $this->assertEquals(ProjectStatus::baseline(), ProjectStatus::fromForm('1'));
    }

    public function testValidateFor(): void
    {
        $this->assertEquals([['testAttribute'], EnumValidator::class, 'enumClass' => ProjectStatus::class], ProjectStatus::validatorFor('testAttribute'));
        $this->assertEquals([['testAttribute2'], EnumValidator::class, 'enumClass' => Language::class], Language::validatorFor('testAttribute2'));
    }
}
