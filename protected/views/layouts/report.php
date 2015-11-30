<?php

use \yii\bootstrap\Html;

/**
 * @var $this \yii\web\View
 * @var $content string
 */
$this->beginPage();
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
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->head(); ?>
    </head>
    <body>
        <?php $this->beginBody(); ?>
        <?php echo $content; ?>
        <?php $this->endBody(); ?>
    </body>
</html>
<?php $this->endPage() ?>