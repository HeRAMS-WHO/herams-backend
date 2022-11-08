<?php

declare(strict_types=1);

use prime\helpers\Icon;
use prime\models\search\SurveySearch;
use prime\widgets\Section;
use yii\data\DataProviderInterface;
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

echo \prime\widgets\AgGrid\AgGrid::widget([
    'route' => ['/api/survey/index'],
    'columns' => [
        [
            'headerName' => \Yii::t('app', 'Title'),
            'cellRenderer' => new \yii\web\JsExpression(<<<JS
                params => {
                    if (params.data == null) {
                        const a = document.createElement('span');
                        a.textContent = params.value;
                        return a; 
                    }
                    const a = document.createElement('a');
                    a.textContent = params.value;
                    a.href = '/survey/{id}/update'.replace('{id}', params.data.id);
                    return a;
                    
                }
            JS),
            'field' => 'title',
            //            'filter' => 'agNumberColumnFilter',
        ],
        [

            'headerName' => \Yii::t('app', 'Id'),
            'field' => 'id',
            'filter' => 'agNumberColumnFilter',
        ],
    ],

]);
Section::end();
