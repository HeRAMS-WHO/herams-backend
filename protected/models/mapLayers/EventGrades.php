<?php

namespace prime\models\mapLayers;

use Carbon\Carbon;
use prime\controllers\MarketplaceController;
use prime\interfaces\ResponseCollectionInterface;
use prime\models\Country;
use prime\models\MapLayer;
use yii\web\Controller;
use yii\web\JsExpression;
use yii\web\View;

class EventGrades extends MapLayer
{
    /** @var ResponseCollectionInterface */
    protected $responses;

    public function __construct(ResponseCollectionInterface $responses, $config = [])
    {
        $this->responses = $responses;
        parent::__construct($config);
    }

    protected function addColorsToData()
    {
        foreach($this->data as &$data) {
            if(!isset($data['color'])) {
                $data['color'] = $this->mapColor($data['value']);
            }
        }
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
        $this->name = \Yii::t('app', 'Event Grades');
        $this->showInLegend = true;
        $this->addPointEventHandler('select', new JsExpression("function(e){select(this, 'eventGrades'); return false;}"));
        $this->addPointEventHandler('mouseOver', new JsExpression("function(e){hover(this, 'eventGrades', true); return false;}"));
        $this->addPointEventHandler('mouseOut', new JsExpression("function(e){hover(this, 'eventGrades', false); return false;}"));
        $this->type = 'mappoint';
        $this->marker = [
            'lineWidth' => 1,
            'radius' => 7,
            'lineColor' => 'rgba(100, 100, 100, 1)'
        ];
        parent::init();
    }

    public static function mapColor($value)
    {
        $map = [
            'A00' => 'rgba(100, 100, 100, 0.8)',
            'A0' => 'rgba(255, 255, 255, 0)',
            'A1' => 'rgba(150, 150, 0, 1)',
            'A2' => 'rgba(150, 73, 0, 1)',
            'A3' => 'rgba(150, 0, 0, 1)'
        ];
        return $map[$value];
    }

    public static function gradeMap()
    {
        return [
            'A00' => \Yii::t('app' , 'Preparedness'),
            'A0' => \Yii::t('app' , 'Ungraded'),
            'A1' => \Yii::t('app' , 'Grade 1'),
            'A2' => \Yii::t('app' , 'Grade 2'),
            'A3' => \Yii::t('app' , 'Grade 3'),
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
        //$responses = app()->limeSurvey->getResponses($this->surveyId);
        foreach($this->responses as $response) {
            $responseData = $response->getData();
            if($responseData['UOID'] != '' && isset($responseData['GM02'])) {
                $responseDate = new Carbon($responseData['GM01']);
                if (!isset($tempData[$responseData['UOID']]) && $responseDate->lte($date)) {
                    $tempData[$responseData['UOID']] =
                        [
                            'iso_3' => $responseData['PRIMEID'],
                            'date' => $responseDate,
                            'value' => $responseData['GM02'],
                            'localityGeo' => $responseData['LocalityGEO'],
                            'localityId' => $responseData['LocalityID']
                        ];
                } else {
                    if($responseDate->lte($date) && $responseDate->gt($tempData[$responseData['UOID']]['date'])) {
                        $tempData[$responseData['UOID']] =
                            [
                                'iso_3' => $responseData['PRIMEID'],
                                'date' => $responseDate,
                                'value' => $responseData['GM02'],
                                'localityGeo' => $responseData['LocalityGEO'],
                                'localityId' => $responseData['LocalityID']
                            ];
                    }
                }
            }
        }
        //TODO add correct lat/long if those are set in the response
        $this->data = [];
        foreach($tempData as $id => $data) {
            if($data['value'] != 'A00' && $data['value'] != 'A0') {
                if (!empty($data['localityGeo'])) {
                    $latitude = 0;
                    $longitude = 0;
                } else {
                    $country = Country::findOne($data['iso_3']);
                    $latitude = $country->latitude;
                    $longitude = $country->longitude;
                }
                $this->data[] = [
                    //'name' => 'Event 1',
                    'lat' => $latitude,
                    'lon' => $longitude,
                    'id' => $id,
                    'value' => $data['value'],
                    'iso_3' => $data['iso_3']
                ];
            }
        }

        $this->addColorsToData();
    }

    public function renderLegend(View $view)
    {
        return "<table>" .
        "<tr><th style='padding: 5px; border-bottom: 1px solid black;'>" . \Yii::t('app', 'Event Grades') . "</th></tr>" .
        "<tr><td style='padding: 5px; font-weight: bold; background-color: " . $this->mapColor('A00') . "'>" . $this->mapGrade('A00') . "</td></tr>" .
        "<tr><td style='padding: 5px; font-weight: bold; background-color: " . $this->mapColor('A0') . "'>" . $this->mapGrade('A0') . "</td></tr>" .
        "<tr><td style='padding: 5px; font-weight: bold; color: white; background-color: " . $this->mapColor('A1') . "'>" . $this->mapGrade('A1') . "</td></tr>" .
        "<tr><td style='padding: 5px; font-weight: bold; color: white; background-color: " . $this->mapColor('A2') . "'>" . $this->mapGrade('A2') . "</td></tr>" .
        "<tr><td style='padding: 5px; font-weight: bold; color: white; background-color: " . $this->mapColor('A3') . "'>" . $this->mapGrade('A3') . "</td></tr>" .
        "</table>";
    }
}