<?php
use \yii\bootstrap\Html;
use yii\widgets\Breadcrumbs;
/* @var $this \yii\web\View */
/* @var $content string */
$this->beginPage();

$this->registerAssetBundle(\prime\assets\AppAsset::class);
?>

    <!DOCTYPE HTML>
    <html>
    <head>
        <?= Html::csrfMetaTags() ?>
        <?=Html::tag('link', null, [
            'rel' => 'shortcut icon',
            'href' => \yii\helpers\Url::to('@web/img/prime_logo.png'),
            'type' => 'img/x-icon'
        ]); ?>
        <title><?= Html::encode($this->title); ?></title>
        <?php $this->head(); ?>
    </head>
    <body style="margin-top: 0px;">
    <?php $this->beginBody(); ?>
    <?php
    echo Html::tag('div', $content, ['class' => 'container-fluid', 'style' => []]);
    ?>
    <?php $this->endBody(); ?>
    </body>
    </html>
<?php $this->endPage() ?>