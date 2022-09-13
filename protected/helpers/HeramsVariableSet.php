<?php

declare(strict_types=1);

namespace prime\helpers;

use Collecthor\DataInterfaces\VariableInterface;
use Collecthor\DataInterfaces\VariableSetInterface;
use prime\helpers\surveyjs\FacilityTierVariable;
use prime\interfaces\ColorMap;

class HeramsVariableSet implements VariableSetInterface
{
    public function __construct(
        private readonly VariableSetInterface $variables,
        public readonly ColorMap $colorMap
    ) {
    }

    public function getVariableNames(): iterable
    {
        return $this->variables->getVariableNames();
    }

    public function getVariable(string $name): VariableInterface
    {
        return $this->variables->getVariable($name);
    }

    /**
     * @return iterable<VariableInterface>
     */
    public function getVariables(): iterable
    {
        return $this->variables->getVariables();
    }
}
