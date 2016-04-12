<?php

namespace prime\models\mapLayers;

use app\components\Html;
use Befound\Components\DateTime;
use Carbon\Carbon;
use prime\objects\RGBColor;
use prime\controllers\MarketplaceController;
use prime\interfaces\ResponseCollectionInterface;
use prime\models\Country;
use prime\models\MapLayer;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use yii\web\Controller;
use yii\web\JsExpression;
use yii\web\View;

class CountryGrades extends MapLayer
{
    public $states = [
        'hover' => [
            'borderColor' => 'rgba(100, 100, 100, 1)',
            'borderWidth' => 2
        ]
    ];

    /** @var ResponseCollectionInterface */
    protected $responses;

    protected function addColorsToData()
    {
        foreach($this->data as &$data) {
            if(!isset($data['color'])) {
                $data['color'] = (string) $this->mapColor($data['value']);
            }
        }
    }

    public function __construct(ResponseCollectionInterface $responses, $config = [])
    {
        $this->responses = $responses;
        parent::__construct($config);
    }

    public function getCountries()
    {
        $result = [];
        foreach($this->data as $e) {
            $result[$e['id']] = Country::findOne($e['id']);
        }
        return $result;
    }

    public function init()
    {
        $this->allowPointSelect = true;
        $this->joinBy = ['ISO_3_CODE', 'id'];
        $this->name = \Yii::t('app', 'Graded Countries');
        $this->showInLegend = true;
        $this->addPointEventHandler('select', new JsExpression("function(e){selectCountry(this, 'countryGrades'); return false;}"));
        $this->addPointEventHandler('mouseOver', new JsExpression("function(e){hover(this, 'countryGrades', true); return false;}"));
        $this->addPointEventHandler('mouseOut', new JsExpression("function(e){hover(this, 'countryGrades', false); return false;}"));
        parent::init();
    }

    public static function colorMap() {
        return [
            'A00' => new RGBColor(100, 100, 100, 0.8),
            'A0' => new RGBColor(240, 240, 240, 1),
            'A1' => new RGBColor(255, 255, 0, 1),
            'A2' => new RGBColor(255, 127, 0, 1),
            'A3' => new RGBColor(255, 0, 0, 1),
            'A4' => new RGBColor(102, 0, 0, 1)
        ];
    }

    public static function mapColor($value)
    {
        return self::colorMap()[$value];
    }

    public static function gradeMap()
    {
        return [
            'A00' => \Yii::t('app' , 'Preparedness'),
            'A0' => \Yii::t('app' , 'Ungraded'),
            'A1' => \Yii::t('app' , 'Grade 1'),
            'A2' => \Yii::t('app' , 'Grade 2'),
            'A3' => \Yii::t('app' , 'Grade 3'),
            'A4' => \Yii::t('app' , 'Protracted'),
        ];
    }

    public static function mapGrade($value)
    {
        return self::gradeMap()[$value];
    }

    public static function mapGradingStage($value)
    {
        $map = [
            'A1' => \Yii::t('app' , 'First grading'),
            'A2' => \Yii::t('app' , 'Grade extension'),
            'A3' => \Yii::t('app' , 'Grade increase'),
            'A4' => \Yii::t('app' , 'Grade decrease'),
            'A5' => \Yii::t('app' , 'Grade end'),
        ];
        return $map[$value];
    }

    public static function valueMap()
    {
        return [
            'A00' => 0,
            'A0' => 1,
            'A1' => 2,
            'A2' => 3,
            'A3' => 4,
            'A4' => 0
        ];
    }

    public static function mapValue($value)
    {
        return self::valueMap()[$value];
    }

    protected function prepareData(Carbon $date = null)
    {
        if(!isset($date)) {
            $date = new Carbon();
        }

        //$tempData will be of shape $tempData[country_iso_3]['value' => ..., 'date' => ...]
        $tempData = [];
        /** @var ResponseInterface $response */
        foreach($this->responses as $response) {
            $responseData = $response->getData();
            if($responseData['PRIMEID'] != '') {
                $responseDate = new Carbon($responseData['GM01']);
                if (!isset($tempData[$responseData['PRIMEID']]) && $responseDate->lte($date)) {
                    $tempData[$responseData['PRIMEID']] = ['date' => $responseDate, 'value' => $responseData['GM02']];
                } else {
                    if($responseDate->lte($date) && $responseDate->gt($tempData[$responseData['PRIMEID']]['date'])) {
                        $tempData[$responseData['PRIMEID']] = ['date' => $responseDate, 'value' => $responseData['GM02']];
                    }
                }
            }
        }

        $this->data = [];
        foreach($tempData as $id => $data) {
            $this->data[] = [
                'id' => $id,
                'value' => $data['value'],
                'iso_3' => $id
            ];
        }

        $this->addColorsToData();
    }

    protected function renderLegend(View $view)
    {
        return "<table>" .
//            "<tr><th style='padding: 5px; border-bottom: 1px solid black;'>" . $this->name . "</th></tr>" .
            "<tr><td style='padding: 5px; font-weight: bold; background-color: " . $this->mapColor('A00') . "'>" . $this->mapGrade('A00') . "</td></tr>" .
            "<tr><td style='padding: 5px; font-weight: bold; background-color: " . $this->mapColor('A0') . "'>" . $this->mapGrade('A0') . "</td></tr>" .
            "<tr><td style='padding: 5px; font-weight: bold; background-color: " . $this->mapColor('A1') . "'>" . $this->mapGrade('A1') . "</td></tr>" .
            "<tr><td style='padding: 5px; font-weight: bold; color: white; background-color: " . $this->mapColor('A2') . "'>" . $this->mapGrade('A2') . "</td></tr>" .
            "<tr><td style='padding: 5px; font-weight: bold; color: white; background-color: " . $this->mapColor('A3') . "'>" . $this->mapGrade('A3') . "</td></tr>" .
        "</table>";
    }
}