<?php

use app\components\Html;

/**
 * @var \prime\models\ar\Project $model
 * @var \yii\web\View $this
 * @var \yii\data\ArrayDataProvider $responsesDataProvider
 */

$this->params['subMenu']['items'] = [];

$this->params['subMenu']['items'][] = [
    'label' => Html::icon('cog'),
    'url' => $model->surveyUrl,
    'visible' => $model->userCan(\prime\models\permissions\Permission::PERMISSION_WRITE),
    'options' => [
        'class' => 'icon',
        'title' => \Yii::t('app', 'Update'),
    ],
];

if(isset($model->defaultGenerator)) {
    $this->params['subMenu']['items'][] = [
        'label' => Html::icon('eye-open'),
        'options' => [
            'class' => 'icon',
            'title' => \Yii::t('app', 'Preview report'),
        ],
        'url' => [
            '/reports/preview',
            'projectId' => $model->id,
            'reportGenerator' => $model->default_generator
        ],
        'visible' => $model->userCan(\prime\models\permissions\Permission::PERMISSION_WRITE) && ($model->getResponses()->size() > 0)
    ];
}

$this->params['subMenu']['items'][] = [
    'label' => Html::icon('share'),
    'url' => ['/projects/share', 'id' => $model->id],
    'visible' => $model->userCan(\prime\models\permissions\Permission::PERMISSION_SHARE),
    'options' => [
        'class' => 'icon',
        'title' => \Yii::t('app', 'Share'),
    ],
];

$this->params['subMenu']['items'][] = [
    'label' => Html::icon('stop'),
    'url' => ['/projects/close', 'id' => $model->id],
    'options' => [
        'class' => 'icon',
        'title' => \Yii::t('app', 'Close'),
    ],
    'linkOptions' => [
        'data-confirm' => \Yii::t('app', 'Are you sure you want to close project <strong>{modelName}</strong>?', ['modelName' => $model->title]),
        'data-method' => 'delete'
    ],
    'visible' => $model->userCan(\prime\models\permissions\Permission::PERMISSION_WRITE)
];
//$this->params['subMenu']['items'][] = [
//    'label' => Html::icon('zoom-in'),
//    'url' => ['/projects/explore', 'id' => $model->id],
//    'options' => [
//        'class' => 'icon',
//        'title' => \Yii::t('app', 'Explore'),
//    ],
//];

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

<!--<div class="">-->
    <iframe src="<?=\yii\helpers\Url::to(['/projects/progress', 'id' => $model->id])?>" class="col-xs-12" style="
    height: 500px;
    border: 0px;
    /*margin-left: -15px; */
    padding-left: 0px;
    /*margin-right: -15px; */
    padding-right: 0px;
    padding-bottom: 10px;
    "></iframe>
<!--</div>-->

<div class="col-xs-12">
    <?php

    // Dynamically resize iframe.
    $this->registerAssetBundle(\prime\assets\ResizeAsset::class);
    $this->registerJs('
        var $iframe = $("iframe");
        var resizer = function(e) {
            console.log("resizer");
            console.log(e);
        $iframe.height($iframe.contents().find("body").height()); };
        $iframe.on("load", function() {
            var $body = $iframe.contents().find("body");
            $body.on("mresize", resizer);
            console.log($body);
            $body.trigger("mresize");
        });

    ', $this::POS_READY);
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

