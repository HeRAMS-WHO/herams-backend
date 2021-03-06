<?php
declare(strict_types=1);

use app\components\ActiveForm;
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Html;
use prime\helpers\Icon;

/**
 * @var \yii\web\View $this
 * @var \prime\models\forms\projects\Token $token
 * @var \prime\models\ar\Workspace $model
 */

$this->params['breadcrumbs'][] = [
    'label' => $model->project->title,
    'url' => ['project/workspaces', 'id' => $model->project->id]
];

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Update workspace token'),
    'url' => ['workspace/configure', 'id' => $model->id]
];

$this->title = Yii::t('app', 'Update workspace token');

echo Html::beginTag('div', ['class' => 'content no-tab']);



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
echo Html::a(Icon::project(), ['project/view', 'id' => $model->project->id], ['title' => \Yii::t('app', 'Project dashboard'), 'class' => 'btn btn-white btn-circle']);
echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => "content layout-{$this->context->layout} controller-{$this->context->id} action-{$this->context->action->id}"]);

?>
<div class="form-content form-bg">
    <h4><?=\Yii::t('app', 'Update workspace token')?></h4>
<?php
$form = ActiveForm::begin([
    'method' => 'PUT',
    "type" => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => [
        'showLabels' => true,
        'defaultPlaceholder' => false,
        'labelSpan' => 3
    ]
]);
foreach ($token->attributes() as $attribute) {
    echo Html::beginTag('div', ['class' => "row"]);
    if ($token->isAttributeSafe($attribute)) {
        echo $form->field($token, $attribute);
    } else {
        echo $form->field($token, $attribute)->textInput([
            'readonly' => true
        ]);
    }
    echo Html::endTag('div');
}

echo ButtonGroup::widget([
    'options' => [
        'class' => 'pull-right'
    ],
    'buttons' => [
        Html::submitButton(\Yii::t('app', 'Update token'), ['class' => 'btn btn-primary'])
    ]
]);
$form->end();
?>
</div>

<?php
echo Html::endTag('div');