<?php

declare(strict_types=1);

namespace prime\helpers;

use Collecthor\DataInterfaces\RecordInterface;
use Collecthor\DataInterfaces\VariableSetInterface;

class VariableSetAnnotator
{
    public function __construct(
        private VariableSetInterface $variableSet
    ) {
    }

    /**
     * @param iterable<RecordInterface> $records
     */
    public function getDisplayValues(iterable $records, null|string $locale = null): iterable
    {
        foreach ($records as $record) {
            $row = [];
            foreach ($this->variableSet->getVariables() as $variable) {
                $row[$variable->getTitle($locale)] = $variable->getDisplayValue($record, $locale)->getRawValue();
            }
            yield $row;
        }
    }

    /**
     * @param iterable<RecordInterface> $records
     */
    public function getCodedValues(iterable $records): iterable
    {
        foreach ($records as $record) {
            $row = [];
            foreach ($this->variableSet->getVariables() as $variable) {
                $row[$variable->getName()] = $variable->getValue($record)->getRawValue();
            }
            yield $row;
        }
    }
}
