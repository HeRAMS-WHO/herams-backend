<?php

declare(strict_types=1);

namespace prime\widgets;

class GridView extends \kartik\grid\GridView
{
    public $export = false;

    public $toggleData = false;

    public $dataColumnClass = DataColumn::class;

    /**
     * This implementation adds support for expanding iterable definitions
     * @throws \yii\base\InvalidConfigException
     */
    protected function initColumns(): void
    {
        if (empty($this->columns)) {
            $this->guessColumns();
        }
        $resolvedColumns = [];
        foreach ($this->columns as $column) {
            if (is_string($column) || is_array($column)) {
                $column = $this->initColumn($column);
                if ($column->visible) {
                    $resolvedColumns[] = $column;
                }
            } elseif (is_iterable($column)) {
                foreach ($column as $expanded) {
                    $resolvedColumns[] = $this->initColumn($expanded);
                }
            }
        }
        $this->columns = $resolvedColumns;
    }

    private function initColumn(string|array $config): \yii\grid\DataColumn
    {
        if (is_string($config)) {
            $column = $this->createDataColumn($config);
        } else {
            $column = \Yii::createObject([
                'class' => $this->dataColumnClass ?: DataColumn::class,
                'grid' => $this,
                ...$config,
            ]);
        }
        return $column;
    }
}
