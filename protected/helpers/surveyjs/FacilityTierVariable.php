<?php

declare(strict_types=1);

namespace prime\helpers\surveyjs;

use Collecthor\DataInterfaces\ClosedVariableInterface;
use Collecthor\DataInterfaces\Measure;
use Collecthor\DataInterfaces\RecordInterface;
use Collecthor\DataInterfaces\StringValueInterface;
use Collecthor\SurveyjsParser\Traits\GetRawConfiguration;
use Collecthor\SurveyjsParser\Values\StringValue;
use prime\objects\enums\FacilityTier;

class FacilityTierVariable implements ClosedVariableInterface
{
    use GetRawConfiguration;

    public function __construct(
        private ClosedVariableInterface $closedVariable,
        private array $tierMap,
        array $rawConfiguration = []
    ) {
        $this->rawConfiguration = $rawConfiguration;
    }

    public function getValueOptions(): array
    {
        return $this->closedVariable->getValueOptions();
    }

    public function getName(): string
    {
        return $this->closedVariable->getName() . "_tier";
    }

    public function getTitle(?string $locale = null): string
    {
        return \Yii::t('app', 'Tier', language: $locale);
    }

    public function getValue(RecordInterface $record): FacilityTier
    {
        $value = $this->closedVariable->getValue($record);
        return $this->tierMap[$value->getRawValue()] ?? FacilityTier::Unknown;
    }

    public function getDisplayValue(RecordInterface $record, ?string $locale = null): StringValueInterface
    {
        return new StringValue($this->getValue($record)->label($locale));
    }

    public function getMeasure(): Measure
    {
        return Measure::Nominal;
    }
}
