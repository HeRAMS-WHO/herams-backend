<?php

namespace prime\models\mapLayers;

use app\components\Html;
use Carbon\Carbon;
use prime\controllers\MarketplaceController;
use prime\interfaces\ResponseCollectionInterface;
use prime\models\ar\Setting;
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

    public $color;

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
        $this->addPointEventHandler('select', new JsExpression("function(e){selectCountry(this, 'healthClusters'); return false;}"));
        $this->addPointEventHandler('mouseOver', new JsExpression("function(e){hover(this, 'healthClusters', true); return false;}"));
        $this->addPointEventHandler('mouseOut', new JsExpression("function(e){hover(this, 'healthClusters', false); return false;}"));
        $this->type = 'mappoint';
        $this->marker = [
            'lineWidth' => 1,
            'radius' => 5,
            'lineColor' => 'rgba(100, 100, 100, 1)',
            'symbol' => 'circle'
        ];
        $this->color = 'rgba(72, 208, 250, 1)';
        parent::init();
    }

    public static function phaseMap()
    {
        return [
            'A1' => \Yii::t('app', 'Activation'),
            'A2' => \Yii::t('app', 'Situation update'),
            'A4' => \Yii::t('app', 'Deactivation')
        ];
    }

    public static function mapPhase($value)
    {
        return self::phaseMap()[$value];
    }

    public static function structureMap()
    {
        return [
            'A1' => \Yii::t('app', 'National'),
            'A2' => \Yii::t('app', 'Subnational')
        ];
    }

    public static function mapType($value)
    {
        $map = [
            'A1' => \Yii::t('app', 'Formal Health Cluster'),
            'A2' => \Yii::t('app', 'Sectoral Coordination Mechanism')
        ];
        return $map[$value];
    }

    public static function valueMap()
    {
        return [
            'A1' => 1,
            'A2' => 1,
            'A4' => 0
        ];
    }

    public static function mapValue($value)
    {
        return self::valueMap()[$value];
    }

    protected function prepareData()
    {
        if (!isset($date)) {
            $date = new Carbon();
        }

        //$tempData will be of shape $tempData[country_iso_3]['value' => ..., 'date' => ...]
        $tempData = [];
        foreach ($this->responses as $response) {
            if ($response->getSurveyId() == Setting::get('healthClusterMappingSurvey')) {
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
        foreach ($tempData as $id => $data) {
            //Filter deactivated health clusters
            if ($data['value'] != 'A4') {
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

    public function renderLegend(View $view)
    {
        return "<table style='width: 100%; margin-bottom: 5px;'>" .
        "<tr><th style='padding: 5px; border-bottom: 1px solid black;'>" . \Yii::t('app', 'Health Clusters') . "</th></tr>" .
        "<tr><td style='padding: 5px;'>&nbsp;</td></tr>" .
        "<tr><td style='padding: 5px;'>&nbsp;</td></tr>" .
        "<tr><td style='padding: 5px;'>&nbsp;</td></tr>" .
        "<tr><td style='padding: 5px;'>&nbsp;</td></tr>" .
        "<tr><td style='padding: 5px;'>&nbsp;</td></tr>" .
        "</table>" .
        Html::button(\Yii::t('app', 'Global dashboard'), ['class' => 'btn btn-default', 'onclick' => new JsExpression("selectGlobal('healthClusters');")]);
    }
}