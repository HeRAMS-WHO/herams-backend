<?php

namespace prime\models\mapLayers;

use Carbon\Carbon;
use prime\controllers\MarketplaceController;
use prime\interfaces\ResponseCollectionInterface;
use prime\models\Country;
use prime\models\MapLayer;
use prime\objects\ResponseCollection;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use yii\web\Controller;
use yii\web\JsExpression;
use yii\web\View;

class HealthClusters extends MapLayer
{
    /** @var ResponseCollectionInterface */
    protected $responses;

    public function __construct(ResponseCollectionInterface $responses, $config = [])
    {
        $this->responses = $responses;
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
        $this->name = \Yii::t('app', 'Health Clusters');
        $this->showInLegend = true;
        $this->addPointEventHandler('select', new JsExpression("function(e){select(this, 'healthClusters'); return false;}"));
        $this->type = 'mappoint';
        $this->marker = [
            'lineWidth' => 1,
            'radius' => 7,
            'lineColor' => 'rgba(100, 100, 100, 1)'
        ];
        parent::init();
    }

    public function phaseMap()
    {
        return [
            'A1' => \Yii::t('app', 'Activation'),
            'A2' => \Yii::t('app', 'Situation update'),
            'A4' => \Yii::t('app', 'Deactivation')
        ];
    }

    public function mapPhase($value)
    {
        return $this->phaseMap()[$value];
    }

    public function mapType($value)
    {
        $map = [
            'A1' => \Yii::t('app', 'Formal Health Cluster'),
            'A2' => \Yii::t('app', 'Sectoral Coordination Mechanism')
        ];
        return $map[$value];
    }

    public function valueMap()
    {
        return [
            'A1' => 1,
            'A2' => 1,
            'A4' => 0
        ];
    }

    public function mapValue($value)
    {
        return $this->valueMap()[$value];
    }

    protected function prepareData()
    {
        if(!isset($date)) {
            $date = new Carbon();
        }

        //$tempData will be of shape $tempData[country_iso_3]['value' => ..., 'date' => ...]
        $tempData = [];
        foreach($this->responses as $response) {
            if($response->getSurveyId() == MarketplaceController::$surveyIds['healthClusters']) {
                $responseData = $response->getData();
                if ($responseData['UOID'] != '') {
                    $responseDate = new Carbon($responseData['CM03']);
                    if (!isset($tempData[$responseData['UOID']]) && $responseDate->lte($date)) {
                        $tempData[$responseData['UOID']] =
                            [
                                'iso_3' => $responseData['PRIMEID'],
                                'date' => $responseDate,
                                'localityGeo' => $responseData['LocalityGEO'],
                                'localityId' => $responseData['LocalityID'],
                                'value' => $responseData['CM01']
                            ];
                    } else {
                        if ($responseDate->lte($date) && $responseDate->gt($tempData[$responseData['UOID']]['date'])) {
                            $tempData[$responseData['UOID']] =
                                [
                                    'iso_3' => $responseData['PRIMEID'],
                                    'date' => $responseDate,
                                    'localityGeo' => $responseData['LocalityGEO'],
                                    'localityId' => $responseData['LocalityID'],
                                    'value' => $responseData['CM01']
                                ];
                        }
                    }
                }
            }
        }

        //TODO add correct lat/long if those are set in the response
        $this->data = [];
        foreach($tempData as $id => $data) {
            //Filter deactivated health clusters
            if($data['value'] != 'A4') {
                if (!empty($data['localityGeo'])) {
                    $latLong = explode(';', $data['localityGeo']);
                    $latitude = floatval($latLong[0]);
                    $longitude = floatval($latLong[1]);
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
    }

    public function renderSummary(View $view, $id)
    {
        /** @var ResponseInterface $response */
        $healthClusterResponses = [];
        foreach($this->responses as $response) {
            $responseData = $response->getData();
            if($responseData['UOID'] != '' && $responseData['UOID'] == $id) {
                if($response->getSurveyId() == MarketplaceController::$surveyIds['healthClusters']) {
                    $healthClusterResponses[] = $response;
                }
            }
        }

        usort($healthClusterResponses, function($a, $b){
            $aD = new Carbon($a->getData()['CM03']);
            $bD = new Carbon($b->getData()['CM03']);
            if($aD->eq($bD)) {
                return ($a->getId() > $b->getId()) ? 1 : -1;
            }
            return ($aD->gt($bD)) ? 1 : -1;
        });

        $country = Country::findOne($healthClusterResponses[0]->getData()['PRIMEID']);

        return $view->render('healthClusters', [
            'country' => $country,
            'healthClusterResponses' => $healthClusterResponses
        ], $this);
    }
}