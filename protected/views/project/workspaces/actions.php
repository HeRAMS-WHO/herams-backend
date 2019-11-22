<?php

use kartik\grid\ActionColumn;
use prime\helpers\Icon;
 use prime\models\ar\Workspace;
 use prime\models\permissions\Permission;
 use yii\bootstrap\Html;
 use yii\helpers\Url;

return [
    'class' => ActionColumn::class,
    'width' => '150px',
    'controller' => 'workspace',
    'template' => '{refresh} {update} {share} {delete} {download} {limesurvey}',
    'buttons' => [
        'refresh' => function($url, Workspace $model, $key) {
            $result = '';
            if (false && \Yii::$app->user->can(Permission::PERMISSION_ADMIN, $model)) {
                $result = Html::a(Icon::sync(), $url, [
                    'title' => \Yii::t('app', 'Refresh data from limesurvey')
                ]);
            }
            return $result;
        },
        'limesurvey' => function($url, Workspace $model, $key) {
            $result = '';
            if (false && \Yii::$app->user->can(Permission::PERMISSION_WRITE, $model)) {
                $result = Html::a(
                    Icon::pencilAlt(),
                    $url,
                    [
                        'title' => \Yii::t('app', 'Data update')
                    ]
                );
            }
            return $result;
        },
        'update' => function($url, Workspace $model, $key) {
            $result = '';
            if (\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $model)) {
                $result = Html::a(
                    Icon::edit(),
                    $url,
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
            if (false && \Yii::$app->user->can(Permission::PERMISSION_SHARE, $model)) {
                $result = Html::a(
                    Icon::share(),
                    $url,
                    [
                        'title' => \Yii::t('app', 'Share')
                    ]
                );
            }
            return $result;
        },
        'delete' => function($url, Workspace $model, $key) {
            if (false &&  \Yii::$app->user->can(Permission::PERMISSION_DELETE, $model)) {
                return Html::a(
                    Icon::delete(),
                    $url,
                    [
                        'data-method' => 'delete',
                        'data-confirm' => \Yii::t('app', 'Are you sure you wish to remove this workspace from the system?')
                    ]
                );
            }
        },
        'download' => function($url, Workspace $model, $key) {
            if (false && \Yii::$app->user->can(Permission::PERMISSION_READ, $model)) {
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
                \prime\assets\BootBoxAsset::register($this);
                $this->registerJs(<<<JS
var handler = function(e){
    console.log('Clicked');
    e.preventDefault();
    e.stopPropagation();
    let textUrl = $(this).data('text');
    let codeUrl = $(this).data('code');
    iziToast.question({
        close: true,
        displayMode: 'once',
        overlay: true,
        position: 'center',
        title: "Download data in CSV format",
        message: "Do you prefer answer as text or as code?",
        buttons: [
            [
                
            ]
        ]    
    });
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