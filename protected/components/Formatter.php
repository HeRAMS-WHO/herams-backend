<?php
declare(strict_types=1);

namespace prime\components;

use CrEOF\Geo\WKB\Parser;
use prime\objects\enums\Enum;
use Ramsey\Uuid\Uuid;

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

    public function asText($value)
    {
        if ($value instanceof Enum) {
            return parent::asText($value->label);
        }
        return parent::asText($value);
    }


}
