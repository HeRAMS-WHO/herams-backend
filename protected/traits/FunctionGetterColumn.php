<?php

declare(strict_types=1);

namespace prime\traits;

trait FunctionGetterColumn
{
    /**
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function getDataCellValue($model, $key, $index): mixed
    {
        if ($this->value !== null) {
            return call_user_func($this->value, $model, $key, $index, $this);
        }
        $method = 'get' . ucfirst($this->attribute ?? '');
        if (is_object($model) && method_exists($model, $method)) {
            return $model->$method();
        }
        return parent::getDataCellValue($model, $key, $index);
    }
}
