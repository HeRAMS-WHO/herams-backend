<?php

use rmrevin\yii\fontawesome\CdnFreeAssetBundle;
/* @var $this \yii\web\View */

/* @var $content string */
$this->beginPage();

//$this->registerCssFile('/css/dashboard.css?' . time());
$this->registerAssetBundle(CdnFreeAssetBundle::class);
$this->registerCssFile("https://fonts.googleapis.com/css?family=Source+Sans+Pro");

?>
    <!DOCTYPE HTML>
    <html>

    <head>
        <?= $this->head();?>

        <?= \yii\helpers\Html::tag('title', $this->title); ?>
    </head>

    <body>
    <?php

    $this->beginBody();
    echo $content;
    $this->endBody();
    ?>
    </body>

    </html>
<?php
$this->endPage();