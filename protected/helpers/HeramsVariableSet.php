<?php

declare(strict_types=1);

namespace prime\helpers;

use Collecthor\DataInterfaces\VariableInterface;
use Collecthor\DataInterfaces\VariableSetInterface;
use herams\common\helpers\surveyjs\FacilityTierVariable;
use prime\interfaces\ColorMap;

class HeramsVariableSet implements VariableSetInterface
{
    private readonly null|FacilityTierVariable $facilityTierVariable;

    public function __construct(
        private readonly VariableSetInterface $variables,
        public readonly ColorMap $colorMap
    ) {
        foreach ($this->variables->getVariables() as $variable) {
            if ($variable instanceof FacilityTierVariable) {
                $this->facilityTierVariable = $variable;
                break;
            }
        }
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

    public function getFacilityTierVariable(): null|FacilityTierVariable
    {
        return $this->facilityTierVariable ?? null;
    }
}
