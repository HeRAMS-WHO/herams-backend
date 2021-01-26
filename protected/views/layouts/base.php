<?php
declare(strict_types=1);

/**
 * @var \prime\components\View $this
 * @var string $content
 */

$this->beginPage();

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