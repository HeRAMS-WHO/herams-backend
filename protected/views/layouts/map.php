<?php

use prime\widgets\map\Map;
use rmrevin\yii\fontawesome\CdnFreeAssetBundle;

/* @var $this \yii\web\View */

/* @var $content string */
$this->beginPage();

//$this->registerCssFile('/css/dashboard.css?' . time());
$this->registerAssetBundle(CdnFreeAssetBundle::class);
//$this->registerCssFile("https://fonts.googleapis.com/css?family=Source+Sans+Pro");
$this->registerAssetBundle(\prime\assets\NewAppAsset::class);
?>
    <!doctype HTML>
    <html lang="<?=\Yii::$app->language; ?>">

    <head>
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <?= $this->head();?>
    </head>


    <body>

    <?php

    $this->beginBody();
    echo $this->render('//flash.php');
    echo Map::widget();
    echo $content;
    $this->endBody();
    ?>
    </body>

    </html>
<?php
$this->endPage();
