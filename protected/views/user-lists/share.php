<?php

use \app\components\Form;
use \app\components\ActiveForm;
use app\components\Html;

/**
 * @var \prime\models\ar\UserList $userList
 * @var \prime\models\forms\Share $model
 */

$this->params['subMenu'] = [
    'items' => [
        [
            'label' => Html::submitButton(\Yii::t('app', 'Share'), ['form' => 'share-userList', 'class' => 'btn btn-primary'])
        ],
    ]
];
?>
<h1><?=\Yii::t('app', 'Share {userListName}', ['userListName' => $userList->name])?></h1>
<div class="col-xs-12">
    <?php
    $form = ActiveForm::begin([
        'id' => 'share-userList',
        'method' => 'POST',
        "type" => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => [
            'showLabels' => true,
            'defaultPlaceholder' => false
        ]
    ]);

    echo $model->renderForm($form);

    $form->end();
    ?>
    <h2><?=\Yii::t('app', 'Already shared with')?></h2>
    <?php
    echo $model->renderTable('/user-lists/share-delete');
    ?>
</div>

