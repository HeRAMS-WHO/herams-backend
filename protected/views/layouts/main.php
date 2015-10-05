<?php
    use \yii\bootstrap\Html;
    use yii\widgets\Breadcrumbs;
    /* @var $this \yii\web\View */
    /* @var $content string */
    $this->beginPage();


    $this->registerAssetBundle(\app\assets\AppAsset::class);
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
    <body>
        <?php $this->beginBody(); ?>
        <?php
            echo $this->render('//menu');
            echo $this->render('//flash.php');
//            echo Html::tag('div', $this->render('/leftMenu.php'), []);

            echo Html::tag('div', $content, ['class' => 'container']);
        ?>
    <?php $this->endBody(); ?>
    </body>
</html>
<?php $this->endPage() ?>