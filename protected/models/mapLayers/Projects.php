<?php

namespace prime\models\mapLayers;

use app\queries\ProjectQuery;
use prime\models\ar\Project;
use prime\models\ar\ProjectCountry;
use prime\models\ar\Report;
use prime\models\Country;
use prime\models\MapLayer;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\JsExpression;
use yii\web\View;

class Projects extends MapLayer
{
    /** @var ProjectQuery */
    protected $projectQuery;

    public function __construct(ProjectQuery $projectQuery, $config = [])
    {
        $this->projectQuery = $projectQuery;
        parent::__construct($config);
    }

    public function getCountries()
    {
        $result = [];
        foreach($this->data as $e) {
            $result[$e['iso_3']] = Country::findOne($e['iso_3']);
        }
        return $result;
    }

    public function init()
    {
        $this->allowPointSelect = true;
        $this->joinBy = null;
        $this->name = \Yii::t('app', 'Projects');
        $this->showInLegend = true;
        $this->addPointEventHandler('select', new JsExpression("function(e){selectCountry(this, 'projects'); return false;}"));
        $this->addPointEventHandler('mouseOver', new JsExpression("function(e){hover(this, 'projects', true); return false;}"));
        $this->addPointEventHandler('mouseOut', new JsExpression("function(e){hover(this, 'projects', false); return false;}"));
        $this->type = 'mappoint';
        $this->marker = [
            'radius' => 7
        ];
        parent::init();
    }

    protected function prepareData()
    {
        $this->data = array_map(function($project) {
            /** @var Project $project */
            $latLong = $project->getLatLong();
            return [
                'name' => $project->title,
                'lat' => $latLong->getLatitude()->get(),
                'lon' => $latLong->getLongitude()->get(),
                'id' => $project->id,
                'iso_3' => $project->country_iso_3
            ];
        //}, Project::find()->notClosed()->all());
        }, $this->projectQuery->all());
    }
}