<?php

declare(strict_types=1);

namespace prime\widgets;

use Carbon\Carbon;
use prime\assets\TimeElementBundle;
use yii\grid\DataColumn;
use yii\helpers\Html;

class DateTimeColumn extends DataColumn
{
    public function init()
    {
        parent::init();
        $this->format = 'datetime';
    }

    protected function renderDataCellContent($model, $key, $index)
    {
        TimeElementBundle::register($this->grid->view);
        $value = $this->getDataCellValue($model, $key, $index);
        if (empty($value)) {
            return \Yii::t('app', 'never');
        }
        $dateTime = new Carbon($value);

        return Html::tag('time-ago', $value, ['datetime' => $dateTime->format(Carbon::ISO8601)]);
    }
}
