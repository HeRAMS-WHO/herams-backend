<?php

declare(strict_types=1);

namespace prime\helpers;

use Collecthor\DataInterfaces\VariableInterface;
use Collecthor\DataInterfaces\VariableSetInterface;
use prime\helpers\surveyjs\FacilityTierVariable;
use prime\interfaces\ColorMap;

class HeramsVariableSet implements VariableSetInterface
{
    private readonly FacilityTierVariable $facilityTypeVariable;

    public function __construct(
        private readonly VariableSetInterface $variables,
        public readonly ColorMap $colorMap
    ) {
        foreach ($variables->getVariables() as $variable) {
            if ($variable instanceof FacilityTierVariable) {
                $this->facilityTypeVariable = $variable;
            }
        }

        if (! isset($this->facilityTypeVariable)) {
            throw new \InvalidArgumentException("Variable set must contain a facility type question");
        }
    }

    public function getFacilityTierVariable(): FacilityTierVariable
    {
        return $this->facilityTypeVariable;
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
