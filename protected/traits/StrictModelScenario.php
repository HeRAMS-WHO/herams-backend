<?php

declare(strict_types=1);

namespace prime\traits;

/**
 * Enables strict scenario checks. This slows down setting a scenario (because it iterates over validators)
 * but it increases debuggability. The slowdown should not be relevant since this is not called often.
 */
trait StrictModelScenario
{
    public function setScenario($value): void
    {
        parent::setScenario($value);
        if (!isset($this->scenarios()[$value])) {
            throw new \InvalidArgumentException("Scenario '$value' is not valid");
        }
    }
}
