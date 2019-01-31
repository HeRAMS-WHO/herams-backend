<div class="col-xs-12">
    <?php
    /** @var \prime\models\ar\Page $page */
    use app\components\Form;
    use kartik\form\ActiveForm;
    use kartik\helpers\Html;

    $form = ActiveForm::begin([
        'id' => 'update-tool',
        'method' => 'PUT',
        "type" => ActiveForm::TYPE_HORIZONTAL,
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

                'items' => array_merge(['' => 'No parent'], $page->parentOptions())
            ],
            [
                'type' => Form::INPUT_RAW,
                'value' => \yii\bootstrap\ButtonGroup::widget([
                    'buttons' => [
                        Html::submitButton(\Yii::t('app', 'Update project'), ['class' => 'btn btn-primary'])
                    ]
                ])
            ]
        ]
    ]);
    $form->end();

    ?>
</div>