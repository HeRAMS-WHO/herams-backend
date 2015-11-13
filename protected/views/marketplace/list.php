<?php

use \app\components\Html;

/**
 * @var $reportsDataProvider \yii\data\ActiveDataProvider
 */

$this->params['subMenu']['items'] = [
    [
        'label' => \Yii::t('app', 'Map'),
        'url' => ['/marketplace/map'],
    ]
];
?>
<div class="col-xs-12">
    <?php

    echo \kartik\grid\GridView::widget([
        'caption' => Yii::t('app', 'Reports'),
        'layout' => "{items}\n{pager}",
        'dataProvider' => $reportsDataProvider,
        'columns' => [

        ]
    ]);
    ?>
</div>
