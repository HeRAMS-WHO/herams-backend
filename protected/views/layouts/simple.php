<?php

use yii\bootstrap\Html;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */
$this->beginPage();
$this->registerAssetBundle(\prime\assets\AppAsset::class);
?>
<!DOCTYPE html>
<!-- Layout: <?=__FILE__ ?> -->
<html lang="en">
<head>
    <meta charset="utf-8">
    <?= Html::csrfMetaTags() ?>
    <?=Html::tag('link', null, [
        'rel' => 'shortcut icon',
        'href' => \yii\helpers\Url::to('@web/img/favicon.png'),
        'type' => 'img/x-icon'
    ]);
    ?>
    <title><?=Html::encode($this->title ?: app()->name); ?></title>


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody(); ?>

<!--[if lt IE 7]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<div class="mainWrapper">
    <div class="background-top"></div>
    <div class="container-fluid header">
        <div class="row">
            <div class="col-sm-6 col-lg-8">
                <?php
                echo Breadcrumbs::widget([
                    'homeLink' => [
                        'label' => \Yii::t('app', 'World overview'),
                        'url' => '/'
                    ],
                    'links' => $this->params['breadcrumbs'] ?? []
                ]);
                ?>
            </div>
            <?php
                if (!Yii::$app->user->isGuest) {
                    ?>
                    <div class="col-sm-6 col-lg-4 user-profile">
                        <div>
                            <div class="username">
                                <?php

                                echo Html::encode((Yii::$app->user->identity->firstName).' '. (Yii::$app->user->identity->lastName));
                                ?>
                            </div>
                            <div class="email"><?php echo Html::encode(Yii::$app->user->identity->email); ?></div>
                        </div>

                        <?php
                            if(!isset($this->params['hideMenu']) || $this->params['hideMenu'] == false) {
                                echo $this->render('//menu');
                            }
                        ?>
                    </div>
                            <?php
                        }
                    ?>
        </div>

    </div>
        <div style="position: relative">
            <div class="title"><?php echo $this->title ?? ''; ?></div>
            <div class="datagrid">
            <div class="container">
            <?php echo $this->render('//subMenu'); ?>
            <?php

            $defaultContainerOptions = [
                'class' => 'row'
            ];

            echo Html::tag(
                'div',
                $content,
                isset($this->params['containerOptions'])
                    ? \yii\helpers\ArrayHelper::merge($defaultContainerOptions, $this->params['containerOptions'])
                    : $defaultContainerOptions
            );
            ?>
            </div>
            </div>
        </div>
    </div>
    <div id="popover-content" class="hidden-popover">
        <div class="log-popover">
            <ul>
                <li>Profile</li>
                <li>Logout</li>
            </ul>
        </div>
    </div>

    <?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage() ?>
