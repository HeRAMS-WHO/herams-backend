<?php
declare(strict_types=1);

namespace prime\components;

use yii\base\Arrayable;
use yii\base\ArrayableTrait;

class Link extends \yii\web\Link implements Arrayable
{
    use ArrayableTrait {
        toArray as private toArrayTrait;
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        return array_filter(self::toArrayTrait($fields, $expand, false));
    }
}
