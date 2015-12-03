<?php

namespace prime\models\mapLayers;

use prime\models\Country;
use prime\models\MapLayer;
use yii\web\Controller;
use yii\web\JsExpression;

class CountryGrades extends MapLayer
{
    public $states = [
        'hover' => [
            'borderColor' => 'rgba(100, 100, 100, 1)',
            'borderWidth' => 2
        ]
    ];

    protected $colorScale = [
        1 => 'rgba(255, 255, 0, 1)',
        2 => 'rgba(255, 127, 0, 1)',
        3 => 'rgba(255, 0, 0, 1)'
    ];

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
        parent::init();
        $this->allowPointSelect = true;
        $this->joinBy = ['ISO_3_CODE', 'id'];
        $this->name = \Yii::t('app', 'Country Grades');
        $this->showInLegend = true;
        $this->addPointEventHandler('select', new JsExpression("function(e){select(this, 'countryGrades'); return false;}"));
    }

    protected function prepareData()
    {
        $this->data = [
            [
                'id' => 'NLD',
                'value' => 1,
            ],
            [
                'id' => 'ISR',
                'value' => 3
            ],
            [
                'id' => 'ROU',
                'value' => 3
            ],
            [
                'id' => 'TUN',
                'value' => 2
            ],
            [
                'id' => 'AFG',
                'value' => 1
            ]
        ];
        $this->addColorsToData();
    }

    public function renderSummary(Controller $controller, $id)
    {
        $country = Country::findOne($id);
        return $controller->render('summaries/reports', [
            'country' => $country
        ]);
    }


}