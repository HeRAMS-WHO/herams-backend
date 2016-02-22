<?php

/**
 * @var \yii\web\View $this
 * @var \prime\models\forms\MarketplaceFilter $filter
 */

if(!(isset($this->params['hideFilter'])) || $this->params['hideFilter'] == false) {
    echo \yii\bootstrap\Collapse::widget(
        [
            'options' => [
                'style' => 'margin-bottom: 0px;'
            ],
            'items' => [
                // equivalent to the above
                'filter' => [
                    'label' => \Yii::t(
                        'app',
                        'Filter' . '<span class="" style="margin-left: 50px; font-style: italic;">' . $filter->getAppliedFiltersString($filter->activeAttributes()) . '</span>'
                    ),
                    'encode' => false,
                    'labelOptions' => [

                    ],
                    'content' => $this->render('filterForm', ['filter' => $filter]),
                    // open its content by default
                    'contentOptions' => ['class' => 'out'],
                    'options' => [
                        'style' => [
                            'margin-bottom' => '10px'
                        ]
                    ]
                ]
            ]
        ]
    );
}