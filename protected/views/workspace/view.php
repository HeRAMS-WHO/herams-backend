<?php
declare(strict_types=1);

use prime\components\View;
use prime\helpers\Icon;
use prime\models\ar\Workspace;
use yii\bootstrap\Html;

/**
 * @var View $this
 * @var Workspace $model
 */

$this->title = $model->title;

echo Html::beginTag('div', ['class' => 'topbar']);
echo Html::beginTag('div', ['class' => 'pull-left']);
echo Html::beginTag('div', ['class' => 'count']);
echo Icon::list();
echo Html::tag('span', \Yii::t('app', 'Health Facilities'));
echo Html::tag('em', $model->facilityCount);
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'count']);
echo Icon::contributors();
echo Html::tag('span', \Yii::t('app', 'Contributors'));
echo Html::tag('em', $model->contributorCount);
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'count']);
echo Icon::recycling();
echo Html::tag('span', \Yii::t('app', 'Latest update'));
echo Html::tag('em', $model->latestUpdate);
echo Html::endTag('div');

echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'btn-group pull-right']);
echo Html::a(Icon::project(), ['project/view', 'id' => $model->project->id], ['title' => \Yii::t('app', 'Project dashboard'), 'class' => 'btn btn-white btn-circle pull-right']);
echo Html::endTag('div');

echo Html::endTag('div');

echo Html::beginTag('div', ['class' => "content layout-{$this->context->layout} controller-{$this->context->id} action-{$this->context->action->id}"]);
echo Html::tag('iframe', '', [
    'src' => $model->getSurveyUrl(),
    'class' => [],
    'style' => [
        'position' => 'absolute',
        'left' => 0,
        'top' => 0,
        'width' => '100%',
        'height' => '100%'
        //'height' => '800px'
    ]
]);
