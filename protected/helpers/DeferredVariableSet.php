<?php

declare(strict_types=1);

namespace prime\helpers;

use Collecthor\DataInterfaces\VariableInterface;
use Collecthor\DataInterfaces\VariableSetInterface;
use InvalidArgumentException;

class DeferredVariableSet implements VariableSetInterface
{
    private VariableSetInterface $variableSet;

    public function __construct(private \Closure $creator)
    {
    }

    private function getVariableSet(): VariableSetInterface
    {
        if (! isset($this->variableSet)) {
            $this->variableSet = ($this->creator)();
        }
        return $this->variableSet;
    }

    public function getVariableNames(): iterable
    {
        return $this->getVariableSet()->getVariableNames();
    }

    public function getVariable(string $name): VariableInterface
    {
        return $this->getVariableSet()->getVariable($name);
    }

    public function getVariables(): iterable
    {
        return $this->getVariableSet()->getVariables();
    }
}
