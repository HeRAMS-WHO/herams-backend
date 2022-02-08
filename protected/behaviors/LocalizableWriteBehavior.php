<?php

declare(strict_types=1);

namespace prime\behaviors;

use yii\base\Behavior;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\validators\InlineValidator;

use function iter\map;
use function iter\toArray;

/**
 * LocalizableWriteBehavior allows writing of localized attribute values.
 * @property ActiveRecord $owner
 */
class LocalizableWriteBehavior extends Behavior
{
    public string $translationProperty = 'i18n';
    public string $defaultLocale = 'en-US';
    public string $locale = 'en-US';
    /**
     * @var array Attribute names that are localizable
     */
    public array $attributes = [];

    private const REGEX = '~^i18n(.+)$~';

    private function attributeName(string $name): string
    {
        if (!preg_match(self::REGEX, $name, $matches)) {
            throw new \InvalidArgumentException("$name does not have expected format");
        }
        return lcfirst($matches[1]);
    }

    public function attach($owner)
    {
        parent::attach($owner);

        $validator =  new InlineValidator();
        $behavior = $this;
        $validator->attributes = toArray(map(fn($attribute) => "i18n" . ucfirst($attribute), $this->attributes));
        $validator->method = function (string $attribute, ?array $params, InlineValidator $validator, $current) use ($behavior) {
            $realAttributeName = $behavior->attributeName($attribute);
            // Store real value and real errors.
            $realValue = $this->{$realAttributeName};
            $realErrors = $this->getErrors($realAttributeName);
            $this->clearErrors($realAttributeName);

            try {
                $validators = $this->getActiveValidators($realAttributeName);
                \Yii::error($this->{$behavior->translationProperty}[$realAttributeName] ?? []);
                foreach ($this->{$behavior->translationProperty}[$realAttributeName] ?? [] as $locale => $value) {
                    $this->{$realAttributeName} = $value;
                    foreach ($validators as $validator) {
                        try {
                            $validator->validateAttribute($this, $realAttributeName);
                        } catch (NotSupportedException $e) {
                            $this->addError($attribute, $e->getMessage());
                        }
                    }
                }
            } finally {
                $this->{$realAttributeName} = $realValue;
                $this->addErrors([$attribute => $this->getErrors($realAttributeName)]);
                $this->clearErrors($realAttributeName);
                $this->addErrors([$realAttributeName => $realErrors]);
            }
        };
        $owner->getValidators()->append($validator);
    }

    public function detach()
    {
        $this->owner->getValidators()->offsetUnset(self::class);
        parent::detach();
    }


    public function canSetProperty($name, $checkVars = true)
    {
        if (preg_match(self::REGEX, $name, $matches)) {
            return in_array(lcfirst($matches[1]), $this->attributes);
        }
        return parent::canSetProperty($name, $checkVars);
    }

    public function canGetProperty($name, $checkVars = true)
    {
        if (preg_match(self::REGEX, $name, $matches)) {
            return in_array(lcfirst($matches[1]), $this->attributes);
        }
        return parent::canGetProperty($name, $checkVars);
    }

    public function __set($name, $value): void
    {
        $i18n = $this->owner->{$this->translationProperty};
        if ($value === null) {
            unset($i18n[$this->attributeName($name)]);
        } else {
            $i18n[$this->attributeName($name)] = is_array($value) ? $value : json_decode($value, true);
        }
        $this->owner->{$this->translationProperty} = $i18n;
    }

    public function __get($name)
    {
        return json_encode($this->owner->{$this->translationProperty}[$this->attributeName($name)] ?? []);
    }
}
