<?php

declare(strict_types=1);

namespace prime\tests\unit\traits;

use Codeception\Test\Unit;
use prime\traits\StrictModelScenario;
use yii\base\Model;

/**
 * No covers annotation since they don't work for traits
 */
class StrictModelScenarioTest extends Unit
{

    public function testInvalidScenarioThrowsException()
    {
        $subject = new class extends Model {
            use StrictModelScenario;

            public string $a = 'test';

            public function scenarios(): array
            {
                return [
                    'scenario1' => ['a']
                ];
            }
        };

        $this->assertNotSame('scenario1', $subject->getScenario());
        $subject->setScenario('scenario1');

        $this->assertSame('scenario1', $subject->getScenario());
        $this->expectException(\InvalidArgumentException::class);
        $subject->setScenario('scenario2');
    }
}
