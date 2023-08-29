<?php

declare(strict_types=1);

namespace herams\api\components;

use herams\common\helpers\Locale;
use herams\common\interfaces\LocalizableInterface;

class Locales extends \yii\rest\Serializer
{
    public function serialize($data): mixed
    {
        if ($data instanceof LocalizableInterface) {
            return $this->serializeLocalizable($data);
        }
        return parent::serialize($data);
    }

    private function serializeLocalizable(LocalizableInterface $data): array
    {
        return $data->toLocalizedArray(Locale::from(\Yii::$app->language));
    }
}
