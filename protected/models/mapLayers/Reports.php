<?php

namespace prime\models\mapLayers;

use prime\models\ar\ProjectCountry;
use prime\models\ar\Report;
use prime\models\Country;
use prime\models\MapLayer;
use prime\models\search\Project;
use yii\web\Controller;
use yii\web\JsExpression;
use yii\web\View;

class Reports extends MapLayer
{
    public function init()
    {
        $this->allowPointSelect = true;
        $this->joinBy = ['ISO_3_CODE', 'iso_3'];
        $this->name = \Yii::t('app', 'Reports');
        $this->showInLegend = true;
        $this->addPointEventHandler('select', new JsExpression("function(e){select(this, 'reports'); return false;}"));
        parent::init();
    }

    protected function prepareData()
    {
        $this->data = array_map(function($country) {
             return ['iso_3' => $country, 'id' => $country];
        }, Project::find()->innerJoinWith(['reports'])->select('country_iso_3')->column());
    }

    public function renderSummary(Controller $controller, $id)
    {
        $country = Country::findOne($id);
        return $controller->render('summaries/reports', [
            'country' => $country
        ]);
    }


}