<?php

use prime\assets\MapLayoutBundle;
use prime\widgets\map\Map;

/* @var $this \yii\web\View */

/* @var $content string */
$this->beginPage();

$this->registerAssetBundle(MapLayoutBundle::class);
?>
    <!doctype HTML>
    <html lang="<?=\Yii::$app->language; ?>">

    <head>
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <?= $this->head();?>

        <style>

        </style>
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