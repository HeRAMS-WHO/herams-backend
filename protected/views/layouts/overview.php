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
            'href' => \yii\helpers\Url::to('@web/img/favicon.png'),
            'type' => 'img/x-icon'
        ]); ?>
        <title><?=Html::encode($this->title ?: app()->name); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->head(); ?>
    </head>
    <body>
        <?php $this->beginBody(); ?>
        <?php

            $defaultContainerOptions = ['class' => 'container'];

            echo Html::tag(
                'div',
                $content,
                isset($this->params['containerOptions'])
                    ? \yii\helpers\ArrayHelper::merge($defaultContainerOptions, $this->params['containerOptions'])
                    : $defaultContainerOptions
            );
        ?>
    <?php $this->endBody(); ?>
    </body>
</html>
<?php $this->endPage() ?>
