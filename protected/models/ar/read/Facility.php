<?php
declare(strict_types=1);

namespace prime\models\ar\read;

use prime\behaviors\LocalizableReadBehavior;
use prime\traits\ReadOnlyTrait;

class Facility extends \prime\models\ar\Facility
{
    use ReadOnlyTrait;

    public function behaviors(): array
    {
        return [
            LocalizableReadBehavior::class => [
                'class' => LocalizableReadBehavior::class,
                'attributes' => ['name', 'alternative_name'],
                'locale' => \Yii::$app->language,
                'defaultLocale' => \Yii::$app->sourceLanguage,
            ]
        ];
    }

    public function attributeLabels(): array
    {
        return parent::attributeLabels() + [
            'uuid' => \Yii::t('app', 'Universal ID')
        ];
    }
}
