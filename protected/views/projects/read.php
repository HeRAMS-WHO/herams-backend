<?php

use app\components\Html;

/**
 * @var \prime\models\Project $model
 * @var \yii\web\View $this
 * @var \yii\data\ArrayDataProvider $responsesDataProvider
 */

$this->params['subMenu']['items'] = [];

$this->params['subMenu']['items'][] = [
    'label' => \Yii::t('app', 'share'),
    'url' => ['projects/share', 'id' => $model->id],
    'visible' => $model->userCan(\prime\models\permissions\Permission::PERMISSION_SHARE)
];

if(isset($model->defaultGenerator)) {
    $this->params['subMenu']['items'][] = [
        'label' => \Yii::t('app', 'preview report'),
        'url' => [
            'reports/preview',
            'projectId' => $model->id,
            'reportGenerator' => $model->default_generator
        ],
        'visible' => $model->userCan(\prime\models\permissions\Permission::PERMISSION_WRITE)
    ];
}

?>

<div class="col-xs-12">
    <div class="row">
        <div class="col-xs-9">
            <h1><?=$model->title?><?=$model->userCan(\prime\models\permissions\Permission::PERMISSION_WRITE) ? Html::a(Html::icon('pencil'), ['projects/update', 'id' => $model->id]) : ''?></h1>
        </div>
        <div class="col-xs-3">
            <?=Html::img($model->tool->imageUrl, ['style' => ['width' => '100%']])?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-9">
            Type of crisis:<br>
            End Date:
        </div>
        <div class="col-xs-12 col-md-3">
            <?=\prime\widgets\User::widget([
                'user' => $model->owner
            ])?>
        </div>
    </div>
</div>

<div class="col-xs-12">
    <iframe src="<?=\yii\helpers\Url::to(['/projects/progress', 'id' => $model->id])?>" seamless="seamless"></iframe>
</div>

<div class="col-xs-12">
    <?php
    echo \yii\bootstrap\Tabs::widget([
         'items' => [
             [
                 'label' => \Yii::t('app', 'Reports'),
                 'content' => $this->render('read/reports.php', ['model' => $model])
             ],
             [
                 'label' => \Yii::t('app', 'Responses'),
                 'content' => $this->render('read/responses.php', ['model' => $model])
             ]
         ]
    ]);
    ?>
</div>

