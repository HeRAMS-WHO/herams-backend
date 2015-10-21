<?php

use app\components\Html;

/**
 * @var \prime\models\Project $model
 * @var \yii\web\View $this
 * @var \yii\data\ArrayDataProvider $responsesDataProvider
 */

$this->params['subMenu']['items'] = [];

if($model->userCan(\prime\models\permissions\Permission::PERMISSION_SHARE)) {
    $this->params['subMenu']['items'][] = [
        'label' => \Yii::t('app', 'share'),
        'url' => ['projects/share', 'id' => $model->id]
    ];
}

if($model->userCan(\prime\models\permissions\Permission::PERMISSION_WRITE)) {
    $this->params['subMenu']['items'][] = [
        'label' => \Yii::t('app', 'update'),
        'url' => ['projects/update', 'id' => $model->id]
    ];

    foreach($model->tool->getGenerators() as $generator => $class) {
        $this->params['subMenu']['items'][] = [
            'label' => \Yii::t('app', 'generate {generator}', ['generator' => $generator]),
            'url' => [
                'reports/preview',
                'projectId' => $model->id,
                'reportGenerator' => $generator
            ]
        ];
    }
}

?>

<div class="col-xs-12">
    <div class="row">
        <div class="col-xs-10">
            <h1><?=$model->title?></h1>
        </div>
        <div class="col-xs-2">
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
    <?=$model->progressWidget->run()?>
</div>

<div class="col-xs-12">
    <h1><?=\Yii::t('app', 'Responses')?></h1>
    <?=\kartik\grid\GridView::widget([
        'dataProvider' => $responseCollection
    ])?>
</div>

