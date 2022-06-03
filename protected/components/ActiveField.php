<?php

declare(strict_types=1);

namespace prime\components;

use yii\helpers\Html;

class ActiveField extends \kartik\form\ActiveField
{
    public function dropDownList($items, $options = [])
    {
        $value = Html::getAttributeValue($this->model, $this->attribute);
        if ($value instanceof \BackedEnum) {
            $options['value'] = $value->value;
        }
        return parent::dropDownList($items, $options);
    }
}
