<?php
declare(strict_types=1);

namespace herams\api\components;

use yii\base\Arrayable;
use yii\base\NotSupportedException;
use yii\helpers\Url;

class Link extends \yii\web\Link implements \JsonSerializable, Arrayable
{


    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function fields(): never
    {
        throw new NotSupportedException();
    }

    public function extraFields(): never
    {
        throw new NotSupportedException();
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true): array
    {
        if ($fields !== [] || $expand !== []) {
            throw new NotSupportedException();
        }

        $data = (array) $this;
        if (is_array($this->href)) {
            $data['href'] = Url::to($this->href);
        }
        return array_filter($data);
    }
}
