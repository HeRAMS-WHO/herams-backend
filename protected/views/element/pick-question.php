<?php

declare(strict_types=1);

use Collecthor\DataInterfaces\ClosedVariableInterface;
use Collecthor\DataInterfaces\VariableSetInterface;
use prime\components\View;
use prime\models\ar\Page;
use prime\widgets\Section;
use yii\helpers\Html;

/**
 * @var VariableSetInterface $variables
 * @var Page $page
 * @var View $this
 */

\prime\assets\IconBundle::register($this);
// Get some data here.
$response = \prime\models\ar\SurveyResponse::find()->andWhere([
    'survey_id' => 3,
])->one();

$this->registerCss(
    <<<CSS
    .card {
        padding: 20px;
        margin: 10px;
        border: 1px solid black;
        position: relative;
    }
     
    .card .icon {
        float: right
    }


CSS
);


Section::begin()
    ->withHeader(\Yii::t('app', "Pick a variable for your new chart"));


/** @var \Collecthor\DataInterfaces\VariableInterface $variable */
foreach ($variables->getVariables() as $variable) {
    echo Html::beginTag('div', [
        'data-name' => $variable->getName(),
        'class' => ['card', 'data-survey'],
    ]);
    echo "{$variable->getTitle(\Yii::$app->language)} ({$variable->getName()})";
    if ($variable instanceof ClosedVariableInterface) {
        echo Html::beginTag('ul');
        foreach ($variable->getValueOptions() as $valueOption) {
            echo Html::tag('li', $valueOption->getDisplayValue());
        }
        echo Html::endTag('ul');
    }
    echo Html::tag('span', \yii\helpers\StringHelper::basename(get_class($variable)), [
        'style' => [
            'position' => 'absolute',
            'right' => '10px',
            'bottom' => '10px',
        ],
    ]);

    echo \prime\widgets\ButtonGroup::widget([
        'buttons' => [
            [
                'type' => 'link',
                'link' => [
                    'create-for-survey-js',
                    'page_id' => $page->id,
                    'variable' => $variable->getName(),
                ],
                'label' => \Yii::t(
                    'app',
                    'Create element for this question'
                ),
            ],

        ],
    ]);
    echo Html::endTag('div');
}

Section::end();
