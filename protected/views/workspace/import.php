<?php

use app\components\Form;
use app\components\ActiveForm;
use yii\bootstrap\Html;
use prime\helpers\Icon;

/**
 * @var \yii\web\View $this
 * @var \prime\models\forms\workspace\Import $model
 * @var \prime\models\ar\Project $project
 */

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Admin dashboard'),
    'url' => ['/admin']
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Projects'),
    'url' => ['/project']
];
$this->params['breadcrumbs'][] = [
    'label' => $project->title,
    'url' => ['project/update', 'id' => $project->id]
];
$this->params['breadcrumbs'][] = [
    'label' => 'Workspaces',
    'url' => ['project/workspaces', 'id' => $project->id]
];
$this->title = \Yii::t('app', 'Import');
//$this->params['breadcrumbs'][] = $this->title;


echo Html::beginTag('div', ['class' => 'topbar']);
echo Html::beginTag('div', ['class' => 'pull-left']);

echo Html::beginTag('div', ['class' => 'count']);
echo Icon::list();
echo Html::tag('span', \Yii::t('app', 'Workspaces'));
echo Html::tag('em', $project->workspaceCount);
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'count']);
echo Icon::contributors();
echo Html::tag('span', \Yii::t('app', 'Contributors'));
echo Html::tag('em', $project->contributorCount);
echo Html::endTag('div');

echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'btn-group pull-right']);
echo Html::a(Icon::project(), ['project/view', 'id' => $project->id], ['title' => \Yii::t('app', 'Project dashboard'), 'class' => 'btn btn-white btn-circle']);
echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => "content layout-{$this->context->layout} controller-{$this->context->id} action-{$this->context->action->id}"]);

?>

<div class="form-content form-bg">
    <h3><?=\Yii::t('app', 'Import Workspace')?></h3>
    <?php
    $form = ActiveForm::begin([
        "type" => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => [
            'showLabels' => true,
            'defaultPlaceholder' => false
        ]
    ]);

    echo \app\components\Form::widget([
        'form' => $form,
        'model' => $model,
        'columns' => 1,
        "attributes" => [
            'titleField' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->fieldOptions()
            ],
            'tokens' => [
                'type' => Form::INPUT_CHECKBOX_LIST,
                'items' => $model->tokenOptions()
            ]
        ]
    ]);
    echo \yii\bootstrap\ButtonGroup::widget([
        'options' => [
            'class' => [
                'pull-right'
            ],
        ],
        'buttons' => [
            Html::submitButton(\Yii::t('app', 'Import workspaces'), ['class' => 'btn btn-primary']),
        ]
    ]);
    $form->end();
    ?>
</div>

<?php
echo Html::endTag('div');
?>