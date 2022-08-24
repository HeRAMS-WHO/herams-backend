<?php

declare(strict_types=1);

namespace prime\helpers\surveyjs;

use Collecthor\DataInterfaces\ClosedVariableInterface;
use Collecthor\DataInterfaces\Measure;
use Collecthor\DataInterfaces\RecordInterface;
use Collecthor\DataInterfaces\StringValueInterface;
use Collecthor\DataInterfaces\ValueInterface;
use Collecthor\DataInterfaces\ValueSetInterface;
use Collecthor\DataInterfaces\VariableInterface;
use Collecthor\SurveyjsParser\Traits\GetDisplayValue;
use Collecthor\SurveyjsParser\Traits\GetName;
use Collecthor\SurveyjsParser\Traits\GetTitle;
use Collecthor\SurveyjsParser\Values\ArrayValue;
use Collecthor\SurveyjsParser\Values\FloatValue;
use Collecthor\SurveyjsParser\Values\IntegerValue;
use Collecthor\SurveyjsParser\Values\InvalidValue;
use Collecthor\SurveyjsParser\Values\MissingIntegerValue;
use Collecthor\SurveyjsParser\Values\MissingStringValue;
use Collecthor\SurveyjsParser\Values\StringValue;
use prime\objects\enums\FacilityTier;
use prime\objects\Locale;
use yii\base\NotSupportedException;

class LocalizableTextVariable implements VariableInterface
{
    use GetTitle, GetName;
    /**
     * @param array<string, string> $titles
     * @phpstan-param non-empty-list<string> $dataPath
     */
    public function __construct(
        private string $name,
        private array $titles,
        private readonly array $dataPath
    ) {
    }

    public function getValue(RecordInterface $record): ArrayValue|InvalidValue
    {
        $result = $record->getDataValue($this->dataPath);
        if ($result === null) {
            return new ArrayValue([]);
        }

        if (is_array($result)) {
            return new ArrayValue($result);
        }

        return new InvalidValue($result);
    }

    public function getDisplayValue(RecordInterface $record, ?string $locale = null): StringValueInterface
    {
        $result = $record->getDataValue($this->dataPath);
        if ($result === null) {
            return new StringValue('');
        }

        return new StringValue($result[$locale ?? Locale::default()->locale] ?? $result[array_keys($result)[0]]);
    }

    public function getMeasure(): Measure
    {
        return Measure::Nominal;
    }
}
