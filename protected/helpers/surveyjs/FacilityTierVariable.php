<?php

declare(strict_types=1);

namespace prime\helpers\surveyjs;

use Collecthor\DataInterfaces\ClosedVariableInterface;
use Collecthor\DataInterfaces\Measure;
use Collecthor\DataInterfaces\RecordInterface;
use Collecthor\DataInterfaces\StringValueInterface;
use Collecthor\DataInterfaces\ValueInterface;
use Collecthor\DataInterfaces\ValueSetInterface;
use Collecthor\SurveyjsParser\Values\InvalidValue;
use prime\objects\enums\FacilityTier;

class FacilityTierVariable implements ClosedVariableInterface
{
    public function __construct(
        private ClosedVariableInterface $closedVariable,
        private array $tierMap
    ) {
    }

    public function getValueOptions(): array
    {
        return $this->closedVariable->getValueOptions();
    }

    public function getName(): string
    {
        return $this->closedVariable->getName() . " - Tier";
    }

    public function getTitle(?string $locale = null): string
    {
        return $this->closedVariable->getTitle($locale);
    }

    public function getValue(RecordInterface $record): ValueInterface|ValueSetInterface
    {
        return $this->closedVariable->getValue($record);
    }

    public function getDisplayValue(RecordInterface $record, ?string $locale = null): StringValueInterface
    {
        return $this->closedVariable->getDisplayValue($record, $locale);
    }

    public function getTier(RecordInterface $record): FacilityTier
    {
        $value = $this->getValue($record);
        return $this->tierMap[$value->getRawValue()] ?? FacilityTier::Unknown;
    }

    public function getMeasure(): Measure
    {
        return Measure::Nominal;
    }
}
