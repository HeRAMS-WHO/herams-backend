<?php

use app\components\Html;
use prime\models\ar\Setting;

/**
 * @var \prime\models\ar\Project $model
 * @var \yii\web\View $this
 * @var \yii\data\ArrayDataProvider $responsesDataProvider
 */

$this->params['subMenu']['items'] = [];

$this->params['subMenu']['items'][] = [
    'label' => Html::icon(Setting::get('icons.limeSurveyUpdate')),
    'url' => $model->surveyUrl,
    'visible' => $model->userCan(\prime\models\permissions\Permission::PERMISSION_WRITE) && $model->closed === null,
    'options' => [
        'class' => 'icon',
        'title' => \Yii::t('app', 'Update'),
    ],
];

if(isset($model->defaultGenerator)) {
    $this->params['subMenu']['items'][] = [
        'label' => Html::icon(Setting::get('icons.read')),
        'options' => [
            'class' => 'icon',
            'title' => \Yii::t('app', 'Preview report'),
        ],
        'url' => [
            '/reports/preview',
            'projectId' => $model->id,
            'reportGenerator' => $model->default_generator
        ],
        'visible' => $model->userCan(\prime\models\permissions\Permission::PERMISSION_WRITE) && ($model->getResponses()->size() > 0) && $model->closed === null
    ];
}

$this->params['subMenu']['items'][] = [
    'label' => Html::icon(Setting::get('icons.share')),
    'url' => ['/projects/share', 'id' => $model->id],
    'visible' => $model->userCan(\prime\models\permissions\Permission::PERMISSION_SHARE) && $model->closed === null,
    'options' => [
        'class' => 'icon',
        'title' => \Yii::t('app', 'Share'),
    ],
];

$this->params['subMenu']['items'][] = [
    'label' => Html::icon(Setting::get('icons.close')),
    'url' => ['/projects/close', 'id' => $model->id],
    'options' => [
        'class' => 'icon',
        'title' => \Yii::t('app', 'Close'),
    ],
    'linkOptions' => [
        'data-confirm' => \Yii::t('app', 'Are you sure you want to close project <strong>{modelName}</strong>?', ['modelName' => $model->title]),
        'data-method' => 'delete'
    ],
    'visible' => $model->userCan(\prime\models\permissions\Permission::PERMISSION_ADMIN) && $model->closed === null
];

$this->params['subMenu']['items'][] = [
    'label' => Html::icon(Setting::get('icons.open')),
    'url' => ['/projects/re-open', 'id' => $model->id],
    'options' => [
        'class' => 'icon',
        'title' => \Yii::t('app', 'Re-open'),
    ],
    'linkOptions' => [
        'data-confirm' => \Yii::t('app', 'Are you sure you want to re-open project <strong>{modelName}</strong>?', ['modelName' => $model->title]),
        'data-method' => 'put'
    ],
    'visible' => $model->userCan(\prime\models\permissions\Permission::PERMISSION_ADMIN) && $model->closed !== null
];

?>

<div class="col-xs-12">
    <div class="row">
        <div class="col-xs-10">
            <h1><?=$model->title?><?=$model->userCan(\prime\models\permissions\Permission::PERMISSION_WRITE) && $model->closed === null ? Html::a(Html::icon(\prime\models\ar\Setting::get('icons.update')), ['projects/update', 'id' => $model->id]) : ''?></h1>
        </div>
        <div class="col-xs-2">
            <?=Html::img($model->tool->imageUrl, ['style' => ['width' => '90%']])?>
        </div>

    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-7 col-md-9">
        </div>
        <div class="col-xs-12 col-sm-5 col-md-3">
            <?=isset($model->owner) ?
            \prime\widgets\User::widget([
                'user' => $model->owner
            ])
                :''
            ?>
        </div>
    </div>
</div>

<iframe src="<?=\yii\helpers\Url::to(['/projects/progress', 'id' => $model->id])?>" class="col-xs-12 resize" style="
height: 0px;
border: 0px;
padding-left: 0px;
padding-right: 0px;
padding-bottom: 10px;
"></iframe>

<div class="col-xs-12">
    <?php

    // Dynamically resize iframe.
    $this->registerAssetBundle(\prime\assets\ReportResizeAsset::class);
    echo \yii\bootstrap\Tabs::widget([
         'items' => [
             [
                 'label' => \Yii::t('app', 'Reports'),
                 'content' => $this->render('read/reports.php', ['tool' => $model->tool, 'model' => $model])
             ],
             [
                 'label' => \Yii::t('app', 'Responses'),
                 'content' => $this->render('read/responses.php', [
                     'tool' => $model->tool,
                     'model' => $model,
                     'responses' => $model->getResponses()
                 ])
             ]
         ]
    ]);
    ?>
</div>

