<?php

namespace prime\widgets\report;

use app\components\Html;
use yii\base\Widget;
use yii\web\JsExpression;

class Distributions extends Widget
{
    public $number;
    public $title;
    public $distributions = [];
    public $view;

    public function buildSeries($distribution) {
        $result = [];
        $i = 0;
        foreach($distribution as $values) {
            $data = [];
            foreach($values as $x => $y) {
                $data[] = [$x, $y];
            }
            $result[] =
                [
                    'data' => $data,
                    'dataLabels' => [
                        'enabled' => false
                    ],
                    'animation' => false,
                    'color' => new JsExpression("Highcharts.getOptions().colors[{$i}]")
                ];
            $i++;
        }
        return $result;
    }

    public function run()
    {
        $result = Html::beginTag('div', ['class' => 'row']);

        //Title
        $result .= Html::tag('h4', $this->number . ' ' . $this->title, ['class' => 'col-xs-12']);

        //Columns
        $items = [];
        foreach($this->distributions as $subTitle => $distribution)
        {
            $title = $this->number . '.' . (count($items) + 1) . ' ' . $subTitle;
            $items[] = [
                'content' => $this->render('distributionGraph', ['title' => $title, 'distribution' => $distribution, 'view' => $this->view, 'series' => $this->buildSeries($distribution)])
            ];
        }

        $result .= Html::beginTag('div', ['class' => 'col-xs-12']);
        $result .= Columns::widget([
            'items' => $items,
            'columnsInRow' => 2
        ]);
        $result .= Html::endTag('div');

        $result .= Html::endTag('div');
        return $result;
    }


}