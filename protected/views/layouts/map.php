<?php

declare(strict_types=1);

use prime\assets\MapLayoutBundle;
use prime\widgets\map\BackgroundMap;

/**
 * @var \prime\components\View $this
 * @var string $content
 */

$this->beginPage();

$this->registerAssetBundle(MapLayoutBundle::class);
?>
    <!doctype HTML>
    <html lang="<?= \Yii::$app->language; ?>">

    <head>
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <?= $this->head(); ?>

        <style>

        </style>
    </head>


    <body>

    <?php

    $this->beginBody();
echo BackgroundMap::widget();
echo $content;
$this->endBody();
?>
    </body>

    </html>
<?php
$this->endPage();
