<?php

declare(strict_types=1);

namespace herams\common\components;

use CrEOF\Geo\WKB\Parser;
use herams\common\interfaces\LabeledEnum;
use Ramsey\Uuid\Uuid;
use yii\helpers\Html;

class Formatter extends \yii\i18n\Formatter
{
    public const FORMAT_UUID = 'uuid';

    public const FORMAT_COORDS = 'coords';

    public function asUuid(string|null $value)
    {
        return isset($value) ? Uuid::fromBytes($value) : $this->nullDisplay;
    }

    public function asCoords($value)
    {
        $parser = new Parser();
        return isset($value) ? $parser->parse($value) : $this->nullDisplay;
    }

    public function asText($value): string
    {
        if ($value instanceof LabeledEnum) {
            return parent::asText($value->label());
        }

        return parent::asText($value);
    }

    public function asJson(array $value): string
    {
        return Html::tag('pre', json_encode($value, JSON_PRETTY_PRINT));
    }
}
