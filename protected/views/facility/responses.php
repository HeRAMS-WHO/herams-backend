<?php
declare(strict_types=1);

use kartik\grid\GridView;
use prime\models\ar\Permission;
use yii\bootstrap\ButtonGroup;
use yii\helpers\Url;

/**
 * @var \yii\data\ActiveDataProvider $facilityProvider
 * @var \prime\components\View $this
 */

$this->title = \Yii::t('app', 'Responses');

echo GridView::widget([

    'caption' => ButtonGroup::widget([
        'options' => [
            'class' => 'pull-right',
        ],
        'buttons' => [
            [
                'label' => \Yii::t('app', 'Create facility'),
                'tagName' => 'a',
                'options' => [
                    'href' => Url::to(['create']),
                    'class' => 'btn-default',
                ],
                'visible' => app()->user->can(Permission::PERMISSION_ADMIN)
            ],
        ]
    ]),
    'dataProvider' => $responseProvider,
//    'filterModel' => $projectSearch,
    'layout' => "{items}\n{pager}",
    'columns' => [
        'id',
        'survey_id',
        'extracted_type',
        'latitude',
        'longitude',
        'extracted_date'
    ]
]);
