<?php

declare(strict_types=1);

use herams\common\models\PermissionOld;
use kartik\grid\GridView;
use prime\components\View;
use prime\widgets\Section;
use yii\bootstrap\ButtonGroup;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/**
 * @var ActiveDataProvider $facilityProvider
 * @var View $this
 */

Section::begin();

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
                'visible' => app()->user->can(PermissionOld::PERMISSION_ADMIN),
            ],
        ],
    ]),
    'dataProvider' => $facilityProvider,
    //    'filterModel' => $projectSearch,
    'layout' => "{items}\n{pager}",
    'columns' => [
        'id',
        'code',
        'name',
        'alternative_name',
    ],
]);

Section::end();
