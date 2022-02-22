<?php

declare(strict_types=1);

use Collecthor\DataInterfaces\ClosedVariableInterface;
use Collecthor\DataInterfaces\VariableSetInterface;
use prime\components\View;
use prime\helpers\Icon;
use prime\models\ar\Page;
use prime\widgets\Section;
use yii\helpers\Html;

/**
 * @var VariableSetInterface $dataVariables
 * @var VariableSetInterface $adminVariables
 * @var Page $page
 * @var View $this
 */

\prime\assets\IconBundle::register($this);
// Get some data here.
$response =  \prime\models\ar\SurveyResponse::find()->andWhere(['survey_id' => 3])->one();

$this->registerCss(<<<CSS
    .card {
        padding: 20px;
        margin: 10px;
        border: 1px solid black;
    }
    .card.data-survey {
        border-color: red;
        
        
    }
    
    .card .icon {
        float: right
    }


CSS
);


Section::begin()
    ->withHeader(\Yii::t('app', "Pick a variable for your new chart"));


/** @var \Collecthor\DataInterfaces\VariableInterface $variable */
foreach ($dataVariables->getVariables() as $variable) {
    echo Html::beginTag('div', [
        'data-name' => $variable->getName(),
        'class' => ['card', 'data-survey']
    ]);
    echo "{$variable->getTitle(\Yii::$app->language)} ({$variable->getName()})";
    if ($variable instanceof ClosedVariableInterface) {
        echo Html::beginTag('ul');
        foreach ($variable->getValueOptions() as $valueOption) {
            echo Html::tag('li', $valueOption->getDisplayValue());
        }
        echo Html::endTag('ul');
    }
    echo Icon::admin();
    echo Html::endTag('div');
}
foreach ($adminVariables->getVariables() as $variable) {
    echo Html::beginTag(
        'div',
        [
            'data-name' => $variable->getName(),
            'class' => ['card', 'admin-survey']
        ]
    );
    echo $variable->getTitle(\Yii::$app->language);
    if ($variable instanceof ClosedVariableInterface) {
        echo Html::beginTag('ul');
        foreach ($variable->getValueOptions() as $valueOption) {
            echo Html::tag('li', $valueOption->getDisplayValue());
        }
        echo Html::endTag('ul');
    }
    echo Icon::user();
    echo Html::endTag('div');
}
Section::end();
