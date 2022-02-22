<?php

declare(strict_types=1);

namespace prime\tests\_helpers;

use yii\base\Model;

trait AllAttributesMustHaveLabels
{
    abstract private function getModel(): Model;

    public function testAttributeLabel(): void
    {
        $model = $this->getModel();
        $class = get_class($model);
        $labels = $model->attributeLabels();

        foreach ($model->attributes() as $attribute) {
            $this->assertArrayHasKey($attribute, $labels, "Model {$class} is missing a label for attribute '{$attribute}'");
        }
    }
}
