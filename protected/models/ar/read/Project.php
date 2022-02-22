<?php

declare(strict_types=1);

namespace prime\models\ar\read;

use prime\behaviors\LocalizableReadBehavior;
use prime\traits\ReadOnlyTrait;

class Project extends \prime\models\ar\Project
{
    use ReadOnlyTrait;

    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'localizable' => [
                'class' => LocalizableReadBehavior::class,
                'locale' => \Yii::$app->language,
                'attributes' => ['title']
            ]
        ]);
    }
}
