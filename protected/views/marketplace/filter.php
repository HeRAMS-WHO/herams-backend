<?php

/**
 * @var \yii\web\View $this
 * @var \prime\models\MarketplaceFilter $filter
 */

?>
<div class="col-xs-12">
    <?php
    echo \yii\bootstrap\Collapse::widget(
        [
            'options' => [
                'style' => 'margin-bottom: 0px;'
            ],
            'items' => [
                // equivalent to the above
                'filter' => [
                    'label' => \Yii::t('app', 'Filter'),
                    'labelOptions' => [
                        'data-label' => 'filter'
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
    ?>
</div>