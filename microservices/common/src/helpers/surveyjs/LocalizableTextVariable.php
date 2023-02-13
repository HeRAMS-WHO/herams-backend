<?php

declare(strict_types=1);

namespace herams\common\helpers\surveyjs;

use Collecthor\DataInterfaces\Measure;
use Collecthor\DataInterfaces\RecordInterface;
use Collecthor\DataInterfaces\StringValueInterface;
use Collecthor\DataInterfaces\ValueInterface;
use Collecthor\DataInterfaces\VariableInterface;
use Collecthor\SurveyjsParser\Traits\GetName;
use Collecthor\SurveyjsParser\Traits\GetRawConfiguration;
use Collecthor\SurveyjsParser\Traits\GetTitle;
use Collecthor\SurveyjsParser\Values\InvalidValue;
use Collecthor\SurveyjsParser\Values\MissingStringValue;
use Collecthor\SurveyjsParser\Values\StringValue;
use herams\common\helpers\Locale;

class LocalizableTextVariable implements VariableInterface
{
    use GetTitle;
    use GetName;
    use GetRawConfiguration;

    /**
     * @param array<string, string> $titles
     * @phpstan-param non-empty-list<string> $dataPath
     */
    public function __construct(
        string $name,
        array $titles,
        private readonly array $dataPath,
        array $rawConfiguration = []
    ) {
        $this->rawConfiguration = $rawConfiguration;
        $this->titles = $titles;
        $this->name = $name;
    }

    public function getValue(RecordInterface $record): ValueInterface
    {
        $result = $record->getDataValue($this->dataPath);

        if ($result === null) {
            return new MissingStringValue();
        } elseif (is_array($result) && !array_is_list($result)) {
            // We always want to include all locales.
            return new ArrayMapValue($result);
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
