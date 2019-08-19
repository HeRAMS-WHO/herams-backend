<?php


namespace prime\components;


use yii\helpers\Json;
use yii\validators\Validator;

class JsonValidator extends Validator
{
    public const ROOT_OBJECT = 'object';
    public const ROOT_ARRAY = 'array';

    public $rootType;

    protected function validateValue($value)
    {
        try {
            $decoded = Json::decode($value, false);
            if (isset($this->rootType)) {
                switch ($this->rootType) {
                    case self::ROOT_ARRAY:
                        if (!is_array($decoded)) {
                            return ['JSON root must be an array', []];
                        }
                        break;
                    case self::ROOT_OBJECT:
                        if (!is_object($decoded)) {
                            return ['JSON root must be an object', []];
                        }
                    default:

                }
            }

        } catch (\Throwable $t) {
            return [$t->getMessage(), []];
        }
    }




}