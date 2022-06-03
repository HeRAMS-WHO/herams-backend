<?php

declare(strict_types=1);

use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use prime\helpers\Icon;
use prime\models\search\SurveySearch;
use prime\models\survey\SurveyForList;
use prime\widgets\IdColumn;
use prime\widgets\Section;
use yii\data\DataProviderInterface;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var DataProviderInterface $surveyProvider
 * @var SurveySearch $surveySearchModel
 * @var View $this
 */

$this->title = \Yii::t('app', 'Surveys');

Section::begin()
    ->withActions(actions: [
        [
            'label' => \Yii::t('app', 'Create survey'),
            'link' => ['survey/create'],
            'style' => 'primary',
            'icon' => Icon::add(),
        ],
    ])
    ->withHeader($this->title);

echo GridView::widget([
    'dataProvider' => $surveyProvider,
    'filterModel' => $surveySearchModel,
    'columns' => [
        [
            'class' => IdColumn::class,
        ],
        [
            'attribute' => 'title',
        ],
        [
            'class' => ActionColumn::class,
            'buttons' => [
                'update' => static function ($url, SurveyForList $model, $key) {
                    return Html::a(
                        Icon::pencilAlt(),
                        [
                            'survey/update',
                            'id' => (string) $model->getId(),
                        ],
                        [
                            'title' => \Yii::t('app', 'Update'),
                        ]
                    );
                },
            ],
        ],
    ],
]);

Section::end();
