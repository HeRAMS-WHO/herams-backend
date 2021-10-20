<?php
declare(strict_types=1);

namespace prime\tests\unit\models\ar;

/**
 * @covers \prime\models\ar\Audit
 */
class AuditTest extends ActiveRecordTest
{
    /**
     * @skip This model is not used for insertion
     */
    public function testValidationRulesAreNotEmptyAndValid(): void
    {
    }

    public function validSamples(): array
    {
        return [];
    }

    public function invalidSamples(): array
    {
        return [];
    }
}
