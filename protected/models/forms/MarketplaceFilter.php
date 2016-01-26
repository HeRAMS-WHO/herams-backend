<?php

namespace prime\models;

use Befound\Components\DateTime;
use prime\models\mapLayers\HealthClusters;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\validators\DateValidator;
use yii\validators\RangeValidator;

class MarketplaceFilter extends Model{

    public $countries;
    public $regions;
    public $endDate;
    public $structure;

    public function rules()
    {
        return [
            [['regions'], RangeValidator::class, 'range' => array_keys(Country::regionOptions()), 'allowArray' => true],
            [['countries'], RangeValidator::class, 'range' => ArrayHelper::getColumn(Country::findAll(), 'iso_3'), 'allowArray' => true],
            [['endDate'], DateValidator::class,'format' => 'php:' . DateTime::MYSQL_DATETIME],
            [['structure'], RangeValidator::class, 'range' => array_keys(HealthClusters::structureMap())]
        ];
    }



}