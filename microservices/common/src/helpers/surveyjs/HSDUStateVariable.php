<?php

declare(strict_types=1);

namespace herams\common\helpers\surveyjs;

use Collecthor\DataInterfaces\ClosedVariableInterface;
use Collecthor\DataInterfaces\Measure;
use Collecthor\DataInterfaces\RecordInterface;
use Collecthor\DataInterfaces\StringValueInterface;
use Collecthor\SurveyjsParser\Traits\GetRawConfiguration;
use Collecthor\SurveyjsParser\Values\StringValue;
use herams\common\domain\facility\HSDUStateEnum;

final class HSDUStateVariable implements ClosedVariableInterface
{
    use GetRawConfiguration;

    public function __construct(
        private ClosedVariableInterface $closedVariable,
        private array $hsduStateMap,
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
        return $this->closedVariable->getName() . "_hsdu_state";
    }

    public function getTitle(?string $locale = null): string
    {
        return \Yii::t('app', 'HSDU State', language: $locale);
    }

    public function getValue(RecordInterface $record): HSDUStateEnum
    {
        $value = $this->closedVariable->getValue($record);
        return $this->hsduStateMap[$value->getRawValue()] ?? HSDUStateEnum::Unknown;
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
