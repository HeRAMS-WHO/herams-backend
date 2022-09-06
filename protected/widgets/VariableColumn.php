<?php

declare(strict_types=1);

namespace prime\widgets;

use Collecthor\DataInterfaces\VariableInterface;

class VariableColumn extends \yii\grid\DataColumn
{
    public VariableInterface $variable;

    protected function getHeaderCellLabel()
    {
        return $this->variable->getTitle(\Yii::$app->language) . "({$this->variable->getName()})";
    }

    protected function renderDataCellContent($model, $key, $index): string
    {
        return $this->variable->getDisplayValue($model, \Yii::$app->language)->getRawValue();
    }

    public static function configForVariables(VariableInterface ...$variables): iterable
    {
        foreach ($variables as $variable) {
            yield [
                'class' => VariableColumn::class,
                'variable' => $variable,
                'attribute' => $variable->getName(),
            ];
        }
    }
}
