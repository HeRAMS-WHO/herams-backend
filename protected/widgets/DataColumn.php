<?php

declare(strict_types=1);

namespace prime\widgets;

use prime\interfaces\LabeledEnum;
use prime\traits\FunctionGetterColumn;

class DataColumn extends \yii\grid\DataColumn
{
    use FunctionGetterColumn {
        getDataCellValue as getFunctionDataCellValue;
    }

    public function getDataCellValue($model, $key, $index): mixed
    {
        $value = $this->getFunctionDataCellValue($model, $key, $index);


        return $value instanceof LabeledEnum ? $value->label() : $value;
    }


}
