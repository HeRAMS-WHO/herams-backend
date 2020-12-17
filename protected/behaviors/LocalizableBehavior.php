<?php
declare(strict_types=1);

namespace prime\behaviors;

use yii\base\Behavior;
use yii\base\Event;
use yii\base\Model;
use yii\base\ModelEvent;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;

class LocalizableBehavior extends Behavior
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
            throw new NotSupportedException("LocalizableBehavior is for read only models");
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

    /**
     * @param Model $model
     */
    private function loadLocalizedAttributes(Model $model):void
    {
        if ($this->locale === $this->defaultLocale
            || !is_array($model->{$this->translationProperty})
            || !isset($model->{$this->translationProperty}[$this->locale])
        ) {
            return;
        }

        foreach ($this->attributes as $attribute) {
            if (isset($model->{$this->translationProperty}[$this->locale][$attribute])) {
                $model->$attribute = $model->{$this->translationProperty}[$this->locale][$attribute];
            }
        }
    }
}
