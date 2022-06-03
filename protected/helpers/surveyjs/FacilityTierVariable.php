<?php

declare(strict_types=1);

namespace prime\helpers\surveyjs;

use Collecthor\DataInterfaces\ClosedVariableInterface;
use Collecthor\DataInterfaces\Measure;
use Collecthor\DataInterfaces\RecordInterface;
use Collecthor\DataInterfaces\StringValueInterface;
use Collecthor\DataInterfaces\ValueInterface;
use Collecthor\DataInterfaces\ValueSetInterface;
use prime\objects\enums\FacilityType;

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
        return $this->closedVariable->getName();
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

    public function getTier(RecordInterface $record): FacilityType
    {
        return $this->tierMap[$this->getValue($record)->getRawValue()];
    }

    public function getMeasure(): Measure
    {
        return Measure::Nominal;
    }
}
