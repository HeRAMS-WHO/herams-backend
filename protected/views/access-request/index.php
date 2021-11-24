<?php

declare(strict_types=1);

use kartik\grid\ActionColumn;
use kartik\grid\BooleanColumn;
use kartik\grid\GridView;
use prime\helpers\Icon;
use prime\interfaces\RequestableInterface;
use prime\models\ar\AccessRequest;
use prime\models\search\AccessRequest as AccessRequestSearch;
use prime\widgets\FavoriteColumn\FavoriteColumn;
use prime\widgets\Section;
use yii\data\DataProviderInterface;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var AccessRequestSearch $closedAccessRequestsSearchModel
 * @var DataProviderInterface $closedAccessRequestsDataprovider
 * @var AccessRequestSearch $openAccessRequestsSearchModel
 * @var DataProviderInterface $openAccessRequestsDataprovider
 */

$this->title = \Yii::t('app', 'Access requests');

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
            'format' => 'raw',
            'value' => function (AccessRequest $request) {
                $target = $request->target;
                if ($target instanceof RequestableInterface) {
                    return Html::a($target->getTitle(), $target->getRoute());
                } else {
                    return Html::encode($target->getDisplayField());
                }
            }

        ],
        [
            'label' => \Yii::t('app', 'Project'),
            'value' => 'target.projectTitle',
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

Section::begin()
    ->withHeader(\Yii::t('app', 'Access requests history'));

$iconCheck = Icon::check();
$iconClose = Icon::close();

echo GridView::widget([
    'dataProvider' => $closedAccessRequestsDataprovider,
    'filterModel' => $closedAccessRequestsSearchModel,
    'columns' => [
        [
            'label' => \Yii::t('app', 'Created by'),
            'value' => 'createdByUser.name',
        ],
        'subject',
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
            'class' => BooleanColumn::class,
            'value' => 'accepted',
            'label' => \Yii::t('app', 'Granted'),
            'trueIcon' => $iconCheck,
            'falseIcon' => $iconClose,
        ],
        'response',
        'responded_at:dateTime',
    ]
]);

Section::end();
