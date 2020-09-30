<?php

use app\components\ActiveForm;
use app\components\Form;
use prime\widgets\FormButtonsWidget;
use prime\helpers\Icon;
use yii\helpers\Html;
use prime\models\ar\Project;
use prime\widgets\menu\WorkspacePageMenu;

/**
 * @var \prime\models\ar\Workspace $workspace
 * @var \prime\models\forms\Share $model
 */

 echo WorkspacePageMenu::widget([
    'workspace' => $workspace,
    'collapsible' => false,
    'footer' => $this->render('//footer', ['projects' => Project::find()->all()]),
    'params' => Yii::$app->request->queryParams,
    'currentPage' => $this->context->action->id
 ]);

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
 $this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Workspace {workspace}', [
        'workspace' => $workspace->title,
    ]),
    'url' => ['workspace/view', 'id' => $workspace->id]
 ];
 $this->title = \Yii::t('app', 'Share workspace {workspace}', ['workspace' => $workspace->title]);
//$this->params['breadcrumbs'][] = $this->title;


 echo Html::beginTag('div', ['class' => 'topbar']);
 echo Html::beginTag('div', ['class' => 'pull-left']);
 echo Html::beginTag('div', ['class' => 'count']);
 echo Icon::list();
 echo Html::tag('span', \Yii::t('app', 'Health Facilities'));
 echo Html::tag('em', $workspace->facilityCount);
 echo Html::endTag('div');

 echo Html::beginTag('div', ['class' => 'count']);
 echo Icon::contributors();
 echo Html::tag('span', \Yii::t('app', 'Contributors'));
 echo Html::tag('em', $workspace->contributorCount);
 echo Html::endTag('div');

 echo Html::beginTag('div', ['class' => 'count']);
 echo Icon::sync();
 echo Html::tag('span', \Yii::t('app', 'Latest update'));
 echo Html::tag('em', $workspace->latestUpdate);
 echo Html::endTag('div');

 echo Html::endTag('div');

 echo Html::beginTag('div', ['class' => 'btn-group pull-right']);
 echo Html::a(Icon::project(), ['project/view', 'id' => $workspace->project->id], ['title' => \Yii::t('app', 'Project dashboard'), 'class' => 'btn btn-white btn-circle pull-right']);
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