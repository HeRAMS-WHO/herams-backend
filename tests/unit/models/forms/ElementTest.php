<?php

declare(strict_types=1);

namespace prime\tests\unit\models\forms;

use prime\models\forms\Element;
use prime\tests\_helpers\Survey;
use prime\tests\unit\models\ModelTest;
use yii\base\Model;

/**
 * @covers \prime\models\forms\Element
 */
class ElementTest extends ModelTest
{
    public function testRuleAttributes(): void
    {
        $this->markTestSkipped('This model uses getter and setter magic');
    }

    protected function getModel(): Model
    {
        return new Element(new Survey(), new \prime\models\ar\Element());
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
