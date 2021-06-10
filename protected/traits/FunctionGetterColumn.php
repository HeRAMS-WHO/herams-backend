<?php
declare(strict_types=1);

namespace prime\traits;

trait FunctionGetterColumn
{
    public function getDataCellValue($model, $key, $index): mixed
    {
        $method = 'get' . ucfirst($this->attribute ?? '');
        if (is_object($model) && method_exists($model, $method)) {
            return $model->$method();
        }
        return parent::getDataCellValue($model, $key, $index);
    }
}
