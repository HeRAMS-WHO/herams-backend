<?php
declare(strict_types=1);

use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use prime\helpers\Icon;
use prime\models\ar\AccessRequest;
use prime\models\search\AccessRequest as AccessRequestSearch;
use prime\widgets\FavoriteColumn\FavoriteColumn;
use prime\widgets\Section;
use yii\data\DataProviderInterface;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var DataProviderInterface $userAccessRequestDataprovider
 * @var AccessRequestSearch $openAccessRequestsSearchModel
 * @var DataProviderInterface $openAccessRequestsDataprovider
 */

Section::begin()
    ->withHeader(\Yii::t('app', 'Your outstanding access requests'), ['style' => ['display' => 'block']]);

echo GridView::widget([
    'dataProvider' => $userAccessRequestDataprovider,
    'columns' => [
        'title',
        [
            'label' => \Yii::t('app', 'Target'),
            'value' => 'target.title',
        ],
    ]
]);

Section::end();

Section::begin()
    ->withHeader(\Yii::t('app', 'Access requests to respond to'));

echo GridView::widget([
    'dataProvider' => $openAccessRequestsDataprovider,
    'filterModel' => $openAccessRequestsSearchModel,
    'columns' => [
        'createdByUser.name',
        [
            'label' => \Yii::t('app', 'Target type'),
            'value' => fn(AccessRequest $model) => $model->targetClassOptions()[$model->target_class],
        ],
        [
            'label' => \Yii::t('app', 'Target title'),
            'value' => 'target.title',
        ],
        'created_at:dateTime',
        [
            'class' => FavoriteColumn::class,
            'enableClick' => false,
            'filter' => [
                true => \Yii::t('app', 'Favorites only'),
                false => \Yii::t('app', 'Non-favorites only'),
            ],
            'value' => static fn(AccessRequest $model) => $model->target,
        ],
        [
            'class' => ActionColumn::class,
            'buttons' => [
                'view' => static function ($url, AccessRequest $model, $key) {
                    return Html::a(
                        Icon::chevronRight(),
                        ['access-request/respond', 'id' => $model->id],
                        [
                            'title' => \Yii::t('app', 'Respond'),
                        ]
                    );
                }
            ],
        ]
    ]
]);

Section::end();
