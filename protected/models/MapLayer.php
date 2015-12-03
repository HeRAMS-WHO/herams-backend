<?php

namespace prime\models;

use app\components\Html;
use prime\factories\MapLayerFactory;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\JsExpression;
use yii\web\View;

class MapLayer extends Model{

    public $allAreas = false;
    public $allowPointSelect;
    public $data;
    public $events;
    public $nullColor;
    public $point = [
        'events' => [

        ]
    ];
    public $name;
    public $showInLegend = false;
    public $legendsContainer = '#legends';
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
        $this->renderLegendContainer(app()->getView());
    }

    protected function prepareData()
    {
        $this->data = [];
    }

    public function renderLegend(View $view) {
        return '';
    }

    public function renderLegendContainer(View $view) {
        if($this->showInLegend) {
            $legend = addslashes(Html::tag('div', $this->renderLegend($view), ['data-layer' => MapLayerFactory::getKey(static::class), 'style' => ['display' => 'inline-block', 'margin-left' => '5px', 'margin-right' => '5px']]));
            $view->registerJs("$('{$this->legendsContainer}').append('{$legend}');");
            $this->events['show'] = new JsExpression('function(e) {$("' . $this->legendsContainer . ' div[data-layer=\"' . MapLayerFactory::getKey(static::class) . '\"]").show();}');
            $this->events['hide'] = new JsExpression('function(e) {$("' . $this->legendsContainer . ' div[data-layer=\"' . MapLayerFactory::getKey(static::class) . '\"]").hide();}');
        }
    }

    public function renderSummary(View $view, $id)
    {
        return 'You selected: ' . $id;
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        return array_filter($this->getAttributes(), function($value){return isset($value);});
    }
}