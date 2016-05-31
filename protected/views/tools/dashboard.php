<?php

use app\components\Html;
use \prime\models\permissions\Permission;
use prime\models\ar\Setting;

/**
 * @var \prime\models\ar\Project $model
 * @var \yii\web\View $this
 * @var \yii\data\ArrayDataProvider $responsesDataProvider
 */

$this->params['subMenu']['items'] = [];

if(isset($model->defaultGenerator)) {
    $this->params['subMenu']['items'][] = [
        'label' => Html::icon(Setting::get('icons.preview')),
        'options' => [
            'class' => 'icon',
            'title' => \Yii::t('app', 'Preview report'),
        ],
        'url' => [
            '/reports/preview',
            'projectId' => $model->id,
            'reportGenerator' => $model->default_generator
        ],
        'visible' => $model->userCan(Permission::PERMISSION_WRITE, app()->user->identity) && ($model->getResponses()->size() > 0)
    ];
}


?>

<div class="col-xs-12">
    <div class="row">
        <div class="col-xs-10">
            <h1><?=$model->title?></h1>
        </div>
        <div class="col-xs-2">
            <?=Html::img($model->imageUrl, ['style' => ['width' => '90%']])?>
        </div>

    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-7 col-md-9">
        </div>
        <div class="col-xs-12 col-sm-5 col-md-3">
        </div>
    </div>
</div>
<?php
    if (isset($model->progress_type)) {
        echo Html::tag('iframe', '', [
            'src' => \yii\helpers\Url::to(['/tools/progress', 'id' => $model->id]),
            'class' => ['col-xs-12', 'resize'],
            'style' => [
                'height' => 0,
                'border' => 0,
                'padding-left' => 0,
                'padding-right' => 0,
                'padding-bottom' => 0
            ]
        ]);
    }
?>


<div class="col-xs-12">
    <?php

    // Dynamically resize iframe.
    $this->registerAssetBundle(\prime\assets\ReportResizeAsset::class);
    echo \yii\bootstrap\Tabs::widget([
         'items' => [
             [
                 'label' => \Yii::t('app', 'Reports'),
                 'content' => $this->render('dashboard/reports.php', ['tool' => $model])
             ],
             [
                 'label' => \Yii::t('app', 'Responses'),
                 'content' => $this->render('dashboard/responses.php', [
                     'tool' => $model,
                     'model' => $model,
                     'responses' => $model->getResponses()
                 ])
             ]
         ]
    ]);
    ?>
</div>

