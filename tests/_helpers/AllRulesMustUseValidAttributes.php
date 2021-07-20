<?php
declare(strict_types=1);

namespace prime\tests\_helpers;

use yii\base\Model;

trait AllRulesMustUseValidAttributes
{
    abstract private function getModel(): Model;

    /**
     * @coversNothing
     */
    public function testRuleAttributes(): void
    {
        $model = $this->getModel();
        foreach ($model->getValidators() as $validator) {
            foreach ($validator->getAttributeNames() as $attribute) {
                $this->assertTrue($model->canGetProperty($attribute), "Found validation rule for non gettable property '$attribute'");
            }
        }
    }
}
