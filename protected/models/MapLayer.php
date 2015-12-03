<?php

namespace prime\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\JsExpression;

class MapLayer extends Model{

    public $allAreas = false;
    public $allowPointSelect;
    public $data;
    public $nullColor;
    public $point = [
        'events' => [

        ]
    ];
    public $name;
    public $showInLegend = false;
    /**
     * How the data is joined
     * First element is the key in the map data
     * Second element (optional) is the key in the data, if not set, first element is used
     * @var array
     */
    public $joinBy = ['ISO_3_CODE'];
    public $type;

    public function addPointEventHandler($event, JsExpression $expression)
    {
        $this->point['events'][$event] = $expression;
    }

    public function init()
    {
        parent::init();
        $this->prepareData();
    }

    protected function prepareData()
    {
        $this->data = [];
    }

    public function renderSummary(Controller $controller, $id)
    {
        return 'You selected: ' . $id;
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        return array_filter($this->getAttributes(), function($value){return isset($value);});
    }
}