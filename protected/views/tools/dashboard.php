<?php

use app\components\Html;
use prime\models\ar\Setting;
use prime\models\permissions\Permission;

/**
 * @var \prime\models\ar\Tool $model
 * @var \yii\web\View $this
 * @var \yii\data\ArrayDataProvider $responsesDataProvider
 */

$this->params['subMenu']['items'] = [
    [
        'visible' => app()->user->can('share', ['model' => \prime\models\ar\Tool::class, 'modelId' => $model->primaryKey]),
        'url' => ['tools/share', 'id' => $model->id],
        'label' => Html::icon(Setting::get('icons.share')),
        'options' => [
            'class' => 'icon',
            'title' => \Yii::t('app', 'Share tool'),
        ]
    ]
];

$this->params['subMenu']['items'][] = [
    'label' => Html::icon(Setting::get('icons.explore', 'tint')),
    'url' => ['tools/explore', 'id' => $model->id],
    'options' => [
        'class' => 'icon',
        'title' => \Yii::t('app', 'Explore data'),
    ],
];


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
    \yii\widgets\Pjax::begin();
    echo \yii\bootstrap\Tabs::widget([
         'items' => [
             [
                 'label' => \Yii::t('app', 'Projects'),
                 'content' => $this->render('dashboard/projects.php', [
                     'tool' => $model,
                     'projectSearch' => $projectSearch,
                     'projectsDataProvider' => $projectsDataProvider
                 ])
             ],
             [
                 'label' => \Yii::t('app', 'Responses'),
                 'url' => ['tools/responses', 'id' => $model->id]
             ],
            [
                'label' => \Yii::t('app', 'Reports'),
                'content' => $this->render('dashboard/reports.php', ['tool' => $model])
            ],

    ]
    ]);
    \yii\widgets\Pjax::end();
    ?>
</div>

