 <?php

 use prime\helpers\Icon;
 use prime\models\ar\Workspace;
 use prime\models\permissions\Permission;
 use yii\bootstrap\Html;
 use yii\helpers\Url;
return [
    'class' => \kartik\grid\ActionColumn::class,
    'width' => '150px',
    'template' => '{update} {share} {remove} {download} {limesurvey}',
    'buttons' => [
        'limesurvey' => function($url, Workspace $model, $key) {
            $result = '';
            if (\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $model)
                || \Yii::$app->user->can(Permission::PERMISSION_WRITE, $model->project)
            ) {
                $result = Html::a(
                    Icon::pencilAlt(),
                    ['/workspace/limesurvey', 'id' => $model->id],
                    [
                        'title' => \Yii::t('app', 'Data update')
                    ]
                );
            }
            return $result;
        },
        'update' => function($url, Workspace $model, $key) {
            $result = '';
            if (\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $model)
                || \Yii::$app->user->can(Permission::PERMISSION_WRITE, $model->project)
            ) {
                $result = Html::a(
                    Icon::edit(),
                    ['/workspace/update', 'id' => $model->id],
                    [
                        'title' => \Yii::t('app', 'Update')
                    ]
                );
            }
            return $result;
        },
        'share' => function($url, Workspace $model, $key) {
            $result = '';
            /** @var Workspace $model */
            if (\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $model->project)) {
                $result = Html::a(
                    Icon::share(),
                    ['/workspace/share', 'id' => $model->id],
                    [
                        'title' => \Yii::t('app', 'Share')
                    ]
                );
            }
            return $result;
        },
        'remove' => function($url, Workspace $model, $key) {
            if (app()->user->can(Permission::PERMISSION_ADMIN, $model)
                || app()->user->can(Permission::PERMISSION_WRITE, $model->project)
            ) {
                return Html::a(
                    Icon::delete(),
                    ['workspace/delete', 'id' => $model->id],
                    [
                        'data-method' => 'delete',
                        'data-confirm' => \Yii::t('app', 'Are you sure you wish to remove this workspace from the system?')
                    ]
                );
            }
        },
        'download' => function($url, Workspace $model, $key) {
            if (app()->user->can(Permission::PERMISSION_ADMIN, $model)
                || app()->user->can(Permission::PERMISSION_WRITE, $model->project)
            ) {
                $result = Html::a(
                    Icon::download(),
                    "#",
                    [
                        'data' => [
                            'code' => Url::to(['/workspace/download', 'id' => $model->id]),
                            'text' => Url::to(['/workspace/download', 'id' => $model->id, 'text' => true])
                        ],
                        'class' => 'download-data',
                        'title' => \Yii::t('app', 'Download'),
                    ]
                );
                $this->registerJs(<<<JS
var handler = function(e){
    console.log('Clicked');
    e.preventDefault();
    e.stopPropagation();
    let textUrl = $(this).data('text');
    let codeUrl = $(this).data('code');
    bootbox.dialog({
        message: "Do you prefer answer as text or as code?",
        title: "Download data in CSV format",
        onEscape: function() {
        },
        buttons: {
            text: {
                label: "Text",
                callback: function() {
                    window.location.href = textUrl;
                }
            },
            code: {
                label: "Code",
                callback: function() {
                    window.location.href = codeUrl;
                }
            },
        }
    
    });
};
$('.download-data').on('click', handler);
JS
                );
                return $result;
            }
        }
    ]
];