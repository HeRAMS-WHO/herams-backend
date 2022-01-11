<?php

declare(strict_types=1);

namespace prime\behaviors;

use yii\base\Behavior;
use yii\base\Event;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;

/**
 * LocalizableReadBehavior allows reading of model localized properties in the current locale
 */
class LocalizableReadBehavior extends Behavior
{
    public string $translationProperty = 'i18n';
    public string $defaultLocale = 'en-US';
    public string $locale = 'en-US';
    /**
     * @var array Attribute names that are localizable
     */
    public array $attributes = [];

    public function events(): array
    {
        $exception = static function () {
            throw new NotSupportedException("LocalizableReadBehavior is for read only models");
        };
        return [
            ActiveRecord::EVENT_AFTER_FIND => function (Event $event): void {
                $this->loadLocalizedAttributes($event->sender);
            },
            ActiveRecord::EVENT_BEFORE_VALIDATE => $exception,
            ActiveRecord::EVENT_AFTER_INSERT => $exception,
            ActiveRecord::EVENT_AFTER_UPDATE => $exception,
        ];
    }

    private function loadLocalizedAttributes(Model $model): void
    {
        if ($this->locale === $this->defaultLocale || !is_array($model->{$this->translationProperty})) {
            return;
        }

        foreach ($this->attributes as $attribute) {
            if (isset($model->{$this->translationProperty}[$attribute][$this->locale])) {
                $model->$attribute = $model->{$this->translationProperty}[$attribute][$this->locale];
            }
        }
    }
}
