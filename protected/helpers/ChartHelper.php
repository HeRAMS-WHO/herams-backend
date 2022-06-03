<?php
declare(strict_types=1);

namespace prime\helpers;

use Collecthor\DataInterfaces\ClosedVariableInterface;
use Collecthor\DataInterfaces\ValueOptionInterface;
use Collecthor\DataInterfaces\VariableInterface;
use Collecthor\DataInterfaces\VariableSetInterface;
use prime\interfaces\HeramsFacilityRecordInterface;
use yii\base\InvalidArgumentException;
use function iter\flatten;
use function iter\map;
use function iter\toArray;

class ChartHelper
{
    private function getGroup(
        HeramsFacilityRecordInterface $record,
        VariableInterface $groupingVariable
    ): string|null
    {
        $value = $groupingVariable->getValue($record);
        if ($value instanceof ValueOptionInterface) {
            return $value->getDisplayValue();
        }
        return $value->getRawValue();
    }

    private function initializeGroup(string|null $group, string $locale, array $colorMap, VariableInterface ...$variables): array
    {
        $points = [];
        foreach ($variables as $variable) {
            if ($variable instanceof ClosedVariableInterface) {
                foreach ($variable->getValueOptions() as $valueOption) {
                    $key = $valueOption->getRawValue();
                    $points[$key] = [
                        'key' => $key,
                        'group' => $group,
                        'label' => $valueOption->getDisplayValue($locale),
                        'value' => rand(1, 1000),
                        'color' => $colorMap[$key] ?? 'rgb(' . implode(',',
                                [mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)]) . ')',
                    ];
                }
            }
        }
        return $points;
    }

    public function createDataArray(
        VariableSetInterface $variableSet,
        string|null $groupingVariableName,
        array $variableNames,
        array $colorMap,
        string $locale,
        iterable $data

    ): array
    {

        $variables = [];
        foreach($variableNames as $variableName) {
            $variables[] = $variableSet->getVariable($variableName);
        }

        $points = [];
        // Get all categories for the chart.
        if (isset($groupingVariableName)) {
            $groupingVariable = $variableSet->getVariable($groupingVariableName);
            if (!$groupingVariable instanceof ClosedVariableInterface) {
                throw new InvalidArgumentException('Grouping variable must be closed');
            }

            $groups = toArray(map(fn(ValueOptionInterface $option) => $option->getDisplayValue(), $groupingVariable->getValueOptions()));
            foreach($groups as $group) {
                $points[$group] = $this->initializeGroup($group, $locale, $colorMap, ...$variables);
            }
        } else {
            $points[null] = $this->initializeGroup(null, $locale, $colorMap, ...$variables);
        }



        // Iterate over data.
        $n = 0;
        foreach($data as $record) {
            $group = isset($groupingVariable) ? $this->getGroup($record, $groupingVariable) : null;

            // A record is counted towards the 'n' if it contains any value
            $relevant = false;
            foreach ($variables as $variable) {
                $value = $variable->getValue($record)->getRawValue();

                if (isset($points[$group][$value])) {
                    $points[$group][$value]['value']++;
                    $relevant = true;
                }
            }
            if ($relevant) {
                $n++;
            }

        }

        return [
            'data' => toArray(flatten($points, 1)),
            'n' => $n
        ];

    }
}
