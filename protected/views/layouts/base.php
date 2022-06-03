<?php

declare(strict_types=1);

use prime\components\View;
use yii\helpers\Html;

/**
 * @var View $this
 * @var string $content
 */

$this->beginPage();

$this->registerCssFile("https://fonts.googleapis.com/css?family=Source+Sans+Pro");

?>
    <!DOCTYPE HTML>
    <html>

    <head>
        <?= $this->head(); ?>

        <?= Html::tag('title', $this->title); ?>
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
