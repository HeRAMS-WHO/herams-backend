<?php

namespace prime\models\mapLayers;

use prime\models\ar\ProjectCountry;
use prime\models\ar\Report;
use prime\models\MapLayer;
use yii\web\JsExpression;

class Reports extends MapLayer
{
    public function init()
    {
        parent::init();
        $this->allowPointSelect = true;
        $this->joinBy = ['ISO_3_CODE', 'iso_3'];
        $this->name = \Yii::t('app', 'Reports');
        $this->showInLegend = true;
        $this->addPointEventHandler('select', new JsExpression("function(e){select(this, 'reports'); return false;}"));
    }

    protected function prepareData()
    {
        $this->data = array_map(function($country) {
             return ['iso_3' => $country, 'id' => $country];
        }, ProjectCountry::find()->innerJoinWith(['project', 'project.reports'])->select('country_iso_3')->column());
    }


}