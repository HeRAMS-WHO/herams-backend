<?php

use app\components\Form;
use app\components\ActiveForm;
use yii\bootstrap\Html;
use prime\helpers\Icon;

/**
 * @var  \prime\components\View $this
 * @var \prime\models\ar\Workspace $model
 */
assert($this instanceof \prime\components\View);
assert($model instanceof \prime\models\ar\Workspace);

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Admin dashboard'),
    'url' => ['/admin']
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Projects'),
    'url' => ['/project']
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Workspaces for {project}', [
        'project' => $model->project->title
    ]),
    'url' => ['project/workspaces', 'id' => $model->project->id]
];
$this->title = \Yii::t('app', 'Update workspace {workspace}', ['workspace' => $model->title]);
$this->params['breadcrumbs'][] = $this->title;



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
echo Icon::sync();
echo Html::tag('span', \Yii::t('app', 'Latest update'));
echo Html::tag('em', $model->latestUpdate);
echo Html::endTag('div');

echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'btn-group pull-right']);
echo Html::a(Icon::project(), ['project/view', 'id' => $model->project->id], ['title' => \Yii::t('app', 'Project dashboard'), 'class' => 'btn btn-white btn-circle']);
echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => "content layout-{$this->context->layout} controller-{$this->context->id} action-{$this->context->action->id}"]);

?>

<div class="form-content form-bg">
    <h3><?= \Yii::t('app', 'Update Workspace') ?></h3>
    <?php
    $form = ActiveForm::begin([
        'id' => 'update-project',
        'method' => 'PUT',
        "type" => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => [
            'showLabels' => true,
            'defaultPlaceholder' => false,
            'labelSpan' => 3
        ]
    ]);

    echo \app\components\Form::widget([
        'form' => $form,
        'model' => $model,
        'columns' => 1,
        "attributes" => [
            'token' => [
                'type' => Form::INPUT_STATIC
            ],
            'title' => [
                'type' => Form::INPUT_TEXT,
            ],
        ]
    ]);
    echo \yii\bootstrap\ButtonGroup::widget([
        'options' => [
            'class' => [
                'pull-right'
            ],
        ],
        'buttons' => [
            Html::a(\Yii::t('app', 'Edit token'), ['workspace/configure', 'id' => $model->id], [
                'class' => ['btn btn-default']
            ]),
            Html::submitButton(\Yii::t('app', 'Save'), ['form' => 'update-project', 'class' => 'btn btn-primary']),

        ]
    ]);
    $form->end();
    ?>
</div>

<?php
echo Html::endTag('div');
?>