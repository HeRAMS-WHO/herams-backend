<?php

namespace prime\models\mapLayers;

use prime\models\MapLayer;
use yii\web\Controller;
use yii\web\JsExpression;

class EventGrades extends MapLayer
{
    protected $colorScale = [
        1 => 'rgba(0, 0, 255, 1)',
        2 => 'rgba(0, 105, 150, 1)',
        3 => 'rgba(0, 150, 105, 1)',
        4 => 'rgba(0, 255, 0, 1)'
    ];

    protected function addColorsToData()
    {
        foreach($this->data as &$data) {
            if(!isset($data['color'])) {
                $data['color'] = $this->colorScale[$data['value']];
            }
        }
    }

    public function init()
    {
        parent::init();
        $this->allowPointSelect = true;
        $this->joinBy = null;
        $this->name = \Yii::t('app', 'Event Grades');
        $this->showInLegend = true;
        $this->addPointEventHandler('select', new JsExpression("function(e){select(this, 'eventGrades'); return false;}"));
        $this->type = 'mappoint';
    }

    protected function prepareData()
    {
        $this->data = [
            [
                'name' => 'Event 1',
                'lat' => 0,
                'lon' => -90,
                'id' => 'Event 1',
                'value' => 1
            ],
            [
                'name' => 'Event 2',
                'lat' => 0,
                'lon' => -30,
                'id' => 'Event 2',
                'value' => 2
            ],
            [
                'name' => 'Event 3',
                'lat' => 0,
                'lon' => 30,
                'id' => 'Event 3',
                'value' => 3
            ],
            [
                'name' => 'Event 4',
                'lat' => 0,
                'lon' => 90,
                'id' => 'Event 4',
                'value' => 4
            ],
        ];
        $this->addColorsToData();
    }

    public function renderSummary(Controller $controller, $id)
    {
        return parent::renderSummary($controller, $id);
    }
}