<?php
/** @var \prime\models\ar\Page $page */
/** @var \prime\models\ar\Project $project */

use app\components\Form;
use kartik\form\ActiveForm;
use kartik\helpers\Html;
use function iter\chain;
use function iter\toArrayWithKeys;

$this->params['breadcrumbs'][] = [
    'label' => $project->title,
    'url' => ['project/update', 'id' => $project->id]
];

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Dashboard settings'),
    'url' => ['project/pages', 'id' => $project->id]
];

$this->title = \Yii::t('app', 'Create page');

echo Html::beginTag('div', ['class' => "content no-tab"]);
?>
<div class="form-content form-bg">
    <h3><?=\Yii::t('app', 'Create Page')?></h3>
    <?php


    $form = ActiveForm::begin([
        'id' => 'update-page',
        "type" => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => [
            'showLabels' => true,
            'defaultPlaceholder' => false,
            'labelSpan' => 3
        ]
    ]);

    echo Form::widget([
        'form' => $form,
        'model' => $page,
        'columns' => 1,
        "attributes" => [
            'title' => [
                'type' => Form::INPUT_TEXT,
            ],
            'parent_id' => [
                'attribute' => 'parent_id' ,
                'type' => Form::INPUT_DROPDOWN_LIST,

                'items' => toArrayWithKeys(chain(['' => 'No parent'], $page->parentOptions()))
            ],
            'sort' => [
               'type' => Form::INPUT_TEXT,
            ],
            [
                'type' => Form::INPUT_RAW,
                'value' => \yii\bootstrap\ButtonGroup::widget([
                    'buttons' => [
                        Html::submitButton(\Yii::t('app', 'Create page'), ['class' => 'btn btn-primary'])
                    ]
                ])
            ]
        ]
    ]);
    $form->end();

    ?>
</div>

<?php
echo Html::endTag('div');
?>