<?php
use \yii\bootstrap\Html;
use yii\widgets\Breadcrumbs;
/* @var $this \yii\web\View */
/* @var $content string */
$this->beginPage();
$this->registerAssetBundle(\prime\assets\AppAsset::class);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <?= Html::csrfMetaTags() ?>
    <?=Html::tag('link', null, [
        'rel' => 'shortcut icon',
        'href' => \yii\helpers\Url::to('@web/img/favicon.png'),
        'type' => 'img/x-icon'
    ]); ?>
    <title><?=Html::encode($this->title ?: app()->name); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->head(); ?>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/fontawesome.css" integrity="sha384-q3jl8XQu1OpdLgGFvNRnPdj5VIlCvgsDQTQB6owSOHWlAurxul7f+JpUOVdAiJ5P" crossorigin="anonymous">
</head>
<body>
<?php $this->beginBody(); ?>

<!--[if lt IE 7]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<div class="mainWrapper">
    <div class="main">
        <div class="background-top"></div>
        <div class="content">
            <div class="header container-fluid">
                <div class="row">
                    <div class="col-sm-6 col-lg-8">
                        <div class="breadcrumbs"><a href="/">&lt; Back home</a></div>
                        <div class="title"><span><?php if (isset($this->params['sectionTitle'])) echo $this->params['sectionTitle']; ?></span></div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="user-profile">
                            <div>
                                <div class="username"><?php echo Html::encode(Yii::$app->user->identity->firstName.' '.Yii::$app->user->identity->lastName); ?></div>
                                <div class="email"><?php echo Html::encode(Yii::$app->user->identity->email); ?></div>
                            </div>
                            <i class="fas fa-user-circle"></i>
                            <i class="fas fa-angle-down" id="log"></i>
                            <?php
                                if(!isset($this->params['hideMenu']) || $this->params['hideMenu'] == false) {
                                    echo $this->render('//menu');
                                }
                            ?>
                            <div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="datagrid container-fluid">
                <?php echo $this->render('//subMenu'); ?>
                <div class="row spacer"><div class="col-12"></div></div>
                <div class="col-12">
                    <div class="main-content-area">
                        <?php
                        //if(!isset($this->params['hideMenu']) || $this->params['hideMenu'] == false) {
                        //    echo $this->render('//menu');
                        //}
                        echo $this->render('//flash.php');

                        $defaultContainerOptions = ['class' => 'container'];

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
                <div class="row spacer"><div class="col-12"></div></div>
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
