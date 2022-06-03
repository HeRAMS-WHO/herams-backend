<?php

declare(strict_types=1);

namespace prime\validators;

use Yii;
use yii\validators\ValidationAsset;
use yii\validators\Validator;

class BackedEnumValidator extends Validator
{
    public \BackedEnum $example;

    public bool $allowArray = false;

    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('yii', '{attribute} is invalid.');
        }
    }

    protected function validateValue($value): array|null
    {
        if (is_array($value) && ! $this->allowArray) {
            return [\Yii::t('app', "Unexpected array value"), []];
        }
        foreach (is_array($value) ? $value : [$value] as $v) {
            if (! $v instanceof $this->example && is_string($v) && $this->example::tryFrom($v) === null) {
                return [
                    \Yii::t('app', "Invalid value {value} for enum"), [
                        'value' => $v,

                    ], ];
            }
        }

        return null;
    }

    public function getClientOptions($model, $attribute): array
    {
        $range = [];
        foreach ($this->example::cases() as $value) {
            $range[] = (string) $value->value;
        }
        $options = [
            'range' => $range,
            'not' => false,
            'allowArray' => $this->allowArray ? 1 : 0,
            'message' => $this->formatMessage($this->message, [
                'attribute' => $model->getAttributeLabel($attribute),
            ]),
        ];
        if ($this->skipOnEmpty) {
            $options['skipOnEmpty'] = 1;
        }

        return $options;
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
        ValidationAsset::register($view);
        $options = $this->getClientOptions($model, $attribute);
        return 'yii.validation.range(value, messages, ' . json_encode($options, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ');';
    }
}
