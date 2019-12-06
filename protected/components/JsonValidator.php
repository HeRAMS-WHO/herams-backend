<?php


namespace prime\components;


use yii\helpers\Json;
use yii\validators\Validator;

class JsonValidator extends Validator
{
    protected function validateValue($value)
    {
        if (!is_string($value)) {
            return ['Value is not a string', []];
        }
        try {
            \json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $e) {
            return [$e->getMessage(), []];
        }
    }




}