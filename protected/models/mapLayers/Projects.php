<?php

namespace prime\models\mapLayers;

use prime\models\ar\ProjectCountry;
use prime\models\MapLayer;
use yii\web\JsExpression;

class Projects extends MapLayer
{
    public function init()
    {
        parent::init();
        $this->allowPointSelect = true;
        $this->joinBy = ['ISO_3_CODE', 'iso_3'];
        $this->name = \Yii::t('app', 'Projects');
        $this->showInLegend = true;
        $this->addPointEventHandler('select', new JsExpression('function(e){console.debug(this);select(this); return false;}'));
    }

    protected function prepareData()
    {
        $this->data = array_map(function($country) {
             return ['iso_3' => $country];
        }, ProjectCountry::find()->select('country_iso_3')->distinct()->column());
    }


}