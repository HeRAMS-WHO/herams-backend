<?php

declare(strict_types=1);

namespace herams\common\helpers\surveyjs;

use Collecthor\DataInterfaces\Measure;
use Collecthor\DataInterfaces\RecordInterface;
use Collecthor\DataInterfaces\StringValueInterface;
use Collecthor\DataInterfaces\ValueSetInterface;
use Collecthor\DataInterfaces\VariableInterface;
use Collecthor\SurveyjsParser\Traits\GetName;
use Collecthor\SurveyjsParser\Traits\GetRawConfiguration;
use Collecthor\SurveyjsParser\Traits\GetTitle;
use Collecthor\SurveyjsParser\Values\InvalidValue;
use Collecthor\SurveyjsParser\Values\StringValue;
use Collecthor\SurveyjsParser\Values\ValueSet;
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
        private string $name,
        private array $titles,
        private readonly array $dataPath,
        array $rawConfiguration = []
    ) {
        $this->rawConfiguration = $rawConfiguration;
    }

    public function getValue(RecordInterface $record): ValueSetInterface|InvalidValue
    {
        $result = $record->getDataValue($this->dataPath);
        if ($result === null) {
            return new ValueSet();
        }

        if (is_array($result)) {
            return new ValueSet(...$result);
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
