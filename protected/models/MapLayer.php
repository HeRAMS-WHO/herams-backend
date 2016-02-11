<?php

namespace prime\models;

use app\components\Html;
use prime\factories\MapLayerFactory;
use prime\interfaces\ResponseCollectionInterface;
use yii\base\Model;
use yii\base\ViewContextInterface;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\JsExpression;
use yii\web\View;

class MapLayer extends Model implements ViewContextInterface, \JsonSerializable
{

    public $allAreas = false;
    public $allowPointSelect;
    public $data = [];
    public $events;
    public $nullColor;
    public $point = [
        'events' => [

        ]
    ];
    public $marker;
    public $name;
    public $showInLegend = false;
    public $legendsContainer = '#legends';
    public $visible = true;
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

    /**
     * @return Country[]
     */
    public function getCountries()
    {
        return [];
    }

    /**
     * @return string the view path that may be prefixed to a relative view name.
     */
    public function getViewPath()
    {
        return __DIR__ . '/mapLayers/views/';
    }

    public function init()
    {
        parent::init();
        $this->prepareData();
        $this->renderLegendContainer(app()->getView());
    }

    protected function prepareData() {}

    protected function renderLegend(View $view) {
        return '<table></table>';
    }

    public function renderLegendContainer(View $view) {
        if($this->showInLegend) {
            $legend = addslashes(Html::tag('div', $this->renderLegend($view), [
                'data-layer' => MapLayerFactory::getKey(static::class),
            ]));
            $view->registerJs("$('{$this->legendsContainer}').append('{$legend}');");
            $this->events['show'] = new JsExpression('function(e) {$("' . $this->legendsContainer . ' div[data-layer=\"' . MapLayerFactory::getKey(static::class) . '\"]").removeClass("disabled");}');
            $this->events['hide'] = new JsExpression('function(e) {$("' . $this->legendsContainer . ' div[data-layer=\"' . MapLayerFactory::getKey(static::class) . '\"]").addClass("disabled");}');
            if(!$this->visible) {
                $view->registerJs('$("' . $this->legendsContainer . ' div[data-layer=\"' . MapLayerFactory::getKey(static::class) . '\"]").addClass("disabled");');
            }
        }
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        return array_filter($this->getAttributes(), function($val) { return $val !== null; });
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}