<?php
declare(strict_types=1);

namespace prime\validators;

use prime\objects\enums\ProjectStatus;
use prime\objects\enums\ProjectVisibility;
use Yii;
use yii\validators\ValidationAsset;
use yii\validators\Validator;

class EnumValidator extends Validator
{
    /**
     * @var class-string
     */
    public string $enumClass;

    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('yii', '{attribute} is invalid.');
        }
    }


    protected function validateValue($value): array|null
    {
        if (!$value instanceof $this->enumClass && $this->enumClass::tryFrom($value) === null) {
            return [\Yii::t('app', "Invalid value {value} for enum"), ['value' => $value]];
        }
        return null;
    }

    public function getClientOptions($model, $attribute): array
    {
        $range = [];
        foreach ($this->enumClass::toValues() as $value) {
            $range[] = (string) $value;
        }
        $options = [
            'range' => $range,
            'not' => false,
            'allowArray' => 0,
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
