<?php

namespace prime\models\mapLayers;

use prime\models\MapLayer;
use yii\web\Controller;
use yii\web\JsExpression;

class HealthClusters extends MapLayer
{
    public function init()
    {
        parent::init();
        $this->allowPointSelect = true;
        $this->joinBy = null;
        $this->name = \Yii::t('app', 'Health Clusters');
        $this->showInLegend = true;
        $this->addPointEventHandler('select', new JsExpression("function(e){select(this, 'healthClusters'); return false;}"));
        $this->type = 'mappoint';
    }

    protected function prepareData()
    {
        $this->data = [
            [
                'name' => 'HC 1',
                'lat' => 20,
                'lon' => -90,
                'id' => 'HC 1',
                'value' => 1
            ],
            [
                'name' => 'HC 2',
                'lat' => 20,
                'lon' => -30,
                'id' => 'HC 2',
                'value' => 2
            ],
            [
                'name' => 'HC 3',
                'lat' => 20,
                'lon' => 30,
                'id' => 'HC 3',
                'value' => 3
            ],
            [
                'name' => 'HC 4',
                'lat' => 20,
                'lon' => 90,
                'id' => 'HC 4',
                'value' => 4
            ],
        ];
    }

    public function renderSummary(Controller $controller, $id)
    {
        return parent::renderSummary($controller, $id);
    }
}