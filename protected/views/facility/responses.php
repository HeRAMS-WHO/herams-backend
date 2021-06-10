<?php
declare(strict_types=1);

use kartik\grid\GridView;
use prime\interfaces\ResponseForList;
use prime\models\ar\Permission;
use prime\models\ar\Response;
use yii\bootstrap\ButtonGroup;
use yii\helpers\Url;

/**
 * @var \yii\data\ActiveDataProvider $responseProvider
 * @var \prime\components\View $this
 */

$this->title = \Yii::t('app', 'Responses');

\prime\widgets\Section::begin();
echo GridView::widget([

    'caption' => ButtonGroup::widget([
        'options' => [
            'class' => 'pull-right',
        ],
        'buttons' => [
            [
                'label' => \Yii::t('app', 'Update situation for facility'),
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
        [
            'attribute' => 'id',
            'class' => \prime\widgets\DrilldownColumn::class,
            'icon' => \prime\helpers\Icon::pencilAlt(),
            'link' => fn(ResponseForList $response) => ['response/update', 'id' => $response->getId()]
        ],
        'dateOfUpdate'
    ]
]);

\prime\widgets\Section::end();
