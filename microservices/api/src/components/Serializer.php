<?php

declare(strict_types=1);

namespace herams\api\components;

use herams\common\helpers\Locale;
use herams\common\interfaces\LocalizableInterface;

class Serializer extends \yii\rest\Serializer
{
    /**
     * @param $data
     * @return array|array[]|mixed|null
     */
    public function serialize($data)
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
