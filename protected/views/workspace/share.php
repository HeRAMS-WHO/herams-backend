<?php

use app\components\ActiveForm;
use app\components\Form;
use prime\models\ar\Permission;
use prime\widgets\FormButtonsWidget;
use prime\helpers\Icon;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \prime\models\ar\Workspace $workspace
 * @var \prime\models\forms\Share $model
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
    'label' => \Yii::t('app', 'Workspaces for {project}', [
        'project' => $workspace->project->title
    ]),
    'url' => ['project/workspaces', 'id' => $workspace->project->id]
];
$this->title = \Yii::t('app', 'Share workspace {workspace}', ['workspace' => $workspace->title]);
$this->params['breadcrumbs'][] = $this->title;


echo Html::beginTag('div', ['class' => 'topbar']);
echo Html::beginTag('div', ['class' => 'pull-left']);
echo Html::beginTag('div', ['class' => 'count']);
echo Icon::healthFacility();
echo Html::tag('span', \Yii::t('app', 'Health Facilities'));
echo Html::tag('em', $workspace->facilityCount);
echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'btn-group pull-right']);
echo Html::a(Icon::project(), ['project/view', 'id' => $workspace->project->id], ['title' => \Yii::t('app', 'Project dashboard'), 'class' => 'btn btn-white btn-circle']);
echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => "content layout-{$this->context->layout} controller-{$this->context->id} action-{$this->context->action->id}"]);

?>

<div class="form-content form-bg">
    <?php
    echo Html::tag('h3', \Yii::t('app', 'Add permissions'));
    $form = ActiveForm::begin([
        'method' => 'POST',
        "type" => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => [
            'showLabels' => true,
            'defaultPlaceholder' => false,
            'labelSpan' => 3
        ]
    ]);

    echo $model->renderForm($form);
    echo Form::widget([
        'form' => $form,
        'model' => $model,
        'attributes' => [
            FormButtonsWidget::embed([
                'options' => [
                    'class' => [
                        'pull-right'
                    ],
                ],
                'buttons' => [
                    ['label' => \Yii::t('app', 'Add'), 'options' => ['class' => ['btn', 'btn-primary']]]
                ]
            ])
        ]
    ]);
    $form->end();
    ?>
</div>
<div class="form-content form-bg full-width">
    <h3><?= \Yii::t('app', 'View user permissions') ?></h3>
    <?php
    echo $model->renderTable();
    ?>
</div>

<?php

echo Html::endTag('div');
?>