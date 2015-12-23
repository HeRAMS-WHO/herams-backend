<?php

namespace prime\models\mapLayers;

use Befound\Components\DateTime;
use Carbon\Carbon;
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
                $data['color'] = $this->mapColor($data['value']);
            }
        }
    }

    public function __construct(ResponseCollectionInterface $responses, $config = [])
    {
        $this->responses = $responses;
        parent::__construct($config);
    }

    public function init()
    {
        $this->allowPointSelect = true;
        $this->joinBy = ['ISO_3_CODE', 'id'];
        $this->name = \Yii::t('app', 'Country Grades');
        $this->showInLegend = true;
        $this->addPointEventHandler('select', new JsExpression("function(e){select(this, 'countryGrades'); return false;}"));
        parent::init();
    }

    public function mapColor($value)
    {
        $map = [
            'A00' => 'rgba(100, 100, 100, 0.8)',
            'A0' => 'rgba(255, 255, 255, 0)',
            'A1' => 'rgba(255, 255, 0, 1)',
            'A2' => 'rgba(255, 127, 0, 1)',
            'A3' => 'rgba(255, 0, 0, 1)'
        ];

        return $map[$value];
    }

    public function gradeMap()
    {
        return [
            'A00' => \Yii::t('app' , 'Preparedness'),
            'A0' => \Yii::t('app' , 'Grade 0'),
            'A1' => \Yii::t('app' , 'Grade 1'),
            'A2' => \Yii::t('app' , 'Grade 2'),
            'A3' => \Yii::t('app' , 'Grade 3'),
        ];
    }

    public function mapGrade($value)
    {
        return $this->gradeMap()[$value];
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

    public function valueMap()
    {
        return [
            'A00' => 0,
            'A0' => 1,
            'A1' => 2,
            'A2' => 3,
            'A3' => 4,
        ];
    }

    public function mapValue($value)
    {
        return $this->valueMap()[$value];
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
            if($responseData['PRIMEID'] != '' && isset($responseData['GM02'])) {
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
                'value' => $data['value']
            ];
        }

        $this->addColorsToData();
    }

    public function renderLegend(View $view)
    {
        return "<table>" .
            "<tr><th style='padding: 5px; border-bottom: 1px solid black;'>" . \Yii::t('app', 'Country Grades') . "</th></tr>" .
            "<tr><td style='padding: 5px; font-weight: bold; background-color: " . $this->mapColor('A00') . "'>" . $this->mapGrade('A00') . "</td></tr>" .
            "<tr><td style='padding: 5px; font-weight: bold; background-color: " . $this->mapColor('A0') . "'>" . $this->mapGrade('A0') . "</td></tr>" .
            "<tr><td style='padding: 5px; font-weight: bold; background-color: " . $this->mapColor('A1') . "'>" . $this->mapGrade('A1') . "</td></tr>" .
            "<tr><td style='padding: 5px; font-weight: bold; color: white; background-color: " . $this->mapColor('A2') . "'>" . $this->mapGrade('A2') . "</td></tr>" .
            "<tr><td style='padding: 5px; font-weight: bold; color: white; background-color: " . $this->mapColor('A3') . "'>" . $this->mapGrade('A3') . "</td></tr>" .
        "</table>";
    }


    public function renderSummary(View $view, $id)
    {
        $country = Country::findOne($id);

        /** @var ResponseInterface $response */
        $countryResponses = [];
        $eventResponses = [];
        foreach($this->responses as $response) {
            $responseData = $response->getData();
            if($responseData['PRIMEID'] != '' && $responseData['PRIMEID'] == $id) {
                if($response->getSurveyId() == MarketplaceController::$surveyIds['countryGrades']) {
                    $countryResponses[] = $response;
                } elseif($response->getSurveyId() == MarketplaceController::$surveyIds['eventGrades']) {
                    $eventIdentifier = 'UOID';
                    if(!isset($eventResponses[$responseData[$eventIdentifier]])) {
                        $eventResponses[$responseData[$eventIdentifier]] = $response;
                    } else {
                        $date = new Carbon($responseData['GM01']);
                        if($date->gt(new Carbon($eventResponses[$responseData[$eventIdentifier]]->getData()['GM01']))) {
                            $eventResponses[$responseData[$eventIdentifier]] = $response;
                        }
                    }
                }
            }
        }

        usort($countryResponses, function($a, $b){
            $aD = new Carbon($a->getData()['GM01']);
            $bD = new Carbon($b->getData()['GM01']);
            if($aD->eq($bD)) {
                return ($a->getId() > $b->getId()) ? 1 : -1;
            }
            return ($aD->gt($bD)) ? 1 : -1;
        });

        return $view->render('countryGrades', [
            'country' => $country,
            'countryResponses' => $countryResponses,
            'eventResponses' => $eventResponses
        ], $this);
    }


}