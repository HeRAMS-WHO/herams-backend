<?php

namespace prime\models\mapLayers;

use Carbon\Carbon;
use prime\interfaces\ResponseCollectionInterface;
use prime\models\Country;
use prime\models\MapLayer;
use yii\web\Controller;
use yii\web\JsExpression;
use yii\web\View;

class EventGrades extends MapLayer
{
    protected $colorScale = [
        'A00' => 'rgba(100, 100, 100, 0.8)',
        'A0' => 'rgba(0, 0, 255, 1)',
        'A1' => 'rgba(0, 105, 150, 1)',
        'A2' => 'rgba(0, 150, 105, 1)',
        'A3' => 'rgba(0, 255, 0, 1)'
    ];
    /** @var ResponseCollectionInterface */
    protected $responses;

    protected $surveyId = 473297;

    public function __construct(ResponseCollectionInterface $responses, $config = [])
    {
        $this->responses = $responses;
        parent::__construct($config);
    }

    protected function addColorsToData()
    {
        foreach($this->data as &$data) {
            if(!isset($data['color'])) {
                $data['color'] = $this->colorScale[$data['value']];
            }
        }
    }

    public function init()
    {
        $this->allowPointSelect = true;
        $this->joinBy = null;
        $this->name = \Yii::t('app', 'Event Grades');
        $this->showInLegend = true;
        $this->addPointEventHandler('select', new JsExpression("function(e){select(this, 'eventGrades'); return false;}"));
        $this->type = 'mappoint';
        parent::init();
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
            if($responseData['PRIMEID'] != '' && isset($responseData['GM02'])) {
                $responseDate = new Carbon($responseData['GM01']);
                if (!isset($tempData[$responseData['PRIMEID']]) && $responseDate->lte($date)) {
                    $tempData[$responseData['PRIMEID']] =
                        [
                            'date' => $responseDate,
                            'value' => $responseData['GM02'],
                            'localityGeo' => $responseData['LocalityGEO'],
                            'localityId' => $responseData['LocalityID']
                        ];
                } else {
                    if($responseDate->lte($date) && $responseDate->gt($tempData[$responseData['PRIMEID']]['date'])) {
                        $tempData[$responseData['PRIMEID']] =
                            [
                                'date' => $responseDate,
                                'value' => $responseData['GM02'],
                                'localityGeo' => $responseData['LocalityGEO'],
                                'localityId' => $responseData['LocalityID']
                            ];
                    }
                }
            }
        }

        $this->data = [];
        foreach($tempData as $id => $data) {
            if(!empty($data['localityGeo'])) {
                $latitude = 0;
                $longitude = 0;
            } else {
                $country = Country::findOne($id);
                $latitude = $country->latitude;
                $longitude = $country->longitude;
            }
            $this->data[] = [
                //'name' => 'Event 1',
                'lat' => $latitude,
                'lon' => $longitude,
                'id' => $id,
                'value' => $data['value']
            ];
        }

        $this->addColorsToData();

//        $this->data = [
//            [
//                'name' => 'Event 1',
//                'lat' => 0,
//                'lon' => -90,
//                'id' => 'Event 1',
//                'value' => 1
//            ],
//            [
//                'name' => 'Event 2',
//                'lat' => 0,
//                'lon' => -30,
//                'id' => 'Event 2',
//                'value' => 2
//            ],
//            [
//                'name' => 'Event 3',
//                'lat' => 0,
//                'lon' => 30,
//                'id' => 'Event 3',
//                'value' => 3
//            ],
//            [
//                'name' => 'Event 4',
//                'lat' => 0,
//                'lon' => 90,
//                'id' => 'Event 4',
//                'value' => 4
//            ],
//        ];
//        $this->addColorsToData();
    }

    public function renderLegend(View $view)
    {
        return "<table>" .
        "<tr><th style='padding: 5px; border-bottom: 1px solid black;'>" . \Yii::t('app', 'Event Grades') . "</th></tr>" .
        "<tr><td style='padding: 5px; font-weight: bold; background-color: " . $this->colorScale['A00'] . "'>" . \Yii::t('app', 'Preparedness') . "</td></tr>" .
        "<tr><td style='padding: 5px; font-weight: bold; color: white; background-color: " . $this->colorScale['A0'] . "'>" . \Yii::t('app', 'Ungraded') . "</td></tr>" .
        "<tr><td style='padding: 5px; font-weight: bold; color: white; background-color: " . $this->colorScale['A1'] . "'>" . \Yii::t('app', 'Grade 1') . "</td></tr>" .
        "<tr><td style='padding: 5px; font-weight: bold; color: white; background-color: " . $this->colorScale['A2'] . "'>" . \Yii::t('app', 'Grade 2') . "</td></tr>" .
        "<tr><td style='padding: 5px; font-weight: bold; color: white; background-color: " . $this->colorScale['A3'] . "'>" . \Yii::t('app', 'Grade 3') . "</td></tr>" .
        "</table>";
    }

    public function renderSummary(View $view, $id)
    {
        return parent::renderSummary($view, $id);
    }
}