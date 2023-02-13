<?php
declare(strict_types=1);

namespace herams\common\helpers;

use Collecthor\DataInterfaces\VariableInterface;
use Collecthor\DataInterfaces\VariableSetInterface;
use Collecthor\SurveyjsParser\FlattenResponseInterface;
use prime\models\project\ProjectLocales;

class FlattenResponseHelper implements FlattenResponseInterface
{
    /** @var array<string, VariableInterface> */
    private readonly array $variableIndexMap;

    public function __construct(
        VariableSetInterface $variables,
        private ProjectLocales $projectLocales,
        private ?string $locale = null,
        private bool $answersAsText = true,
        bool $keysAsText = false,



    ) {
        $map = [];
        foreach($variables->getVariables() as $variable) {
            $map[$keysAsText ? $variable->getTitle($this->locale) : $variable->getName()] = $variable;
        }
        $this->variableIndexMap = $map;
    }

    public function flattenAll(iterable $records): iterable
    {

        foreach ($records as $record) {
            $flattened = [];
            foreach ($this->variableIndexMap as $key => $variable)
            {
                $value = $this->answersAsText ? $variable->getDisplayValue($record, $this->locale) : $variable->getValue($record);
                $rawValue = $value->getRawValue();
                if (is_array($rawValue) && !array_is_list($rawValue)) {
                    // We should make # an illegal character for use in question names, otherwise we could have collisions.
                    foreach($this->projectLocales->getLocales() as $locale) {
                        $flattened["{$key}#{$locale->locale}"] = $rawValue[$locale->locale] ?? "";
                    }
                } elseif (is_scalar($rawValue)) {
                    $flattened[$key] = $rawValue;
                } else {
                    throw new \Exception('Got non scalar non dictionary value');
                }
            }
            yield $flattened;
        }
    }
}
