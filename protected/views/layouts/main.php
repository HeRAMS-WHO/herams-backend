<?php
    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    /* @var $this \yii\web\View */
    /* @var $content string */
    $this->beginPage();

    $this->registerAssetBundle(\app\assets\AppAsset::class);
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title><?= Html::encode($this->title); ?></title>
        <?php $this->head(); ?>
    </head>
    <body>
        <?php $this->beginBody(); ?>
        <?php
            echo $this->render('/menu');
            echo Html::tag('div', $this->render('/leftMenu.php'), []);
            echo Html::tag('div', $content, []);
        ?>
    <?php $this->endBody(); ?>
    </body>
</html>
<?php $this->endPage() ?>