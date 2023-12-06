<?php

declare(strict_types=1);

use prime\assets\AdminBundle;
use prime\assets\ReactAsset;
use prime\components\View;
use yii\helpers\Html;

/**
 * @var View $this
 * @var string $content
 */

$this->beginPage();

$this->registerAssetBundle(\prime\assets\AppAsset::class);
$this->registerAssetBundle(AdminBundle::class);

ReactAsset::register($this);
?>
<!DOCTYPE html>
<html lang="<?= \Yii::$app->language ?>">
    <head>
        <?= Html::csrfMetaTags() ?>
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title><?= Html::encode($this->title); ?></title>
        <link rel="icon" type="image/png" href="/img/herams_icon.png" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <style>
            body {
                display: block !important;
            }
        </style>
        <?php
        $this->head();
        ?>
    </head>
    <?php
        echo Html::beginTag('body', $this->params['body'] ?? []);
        $this->beginBody();
        echo $content;
        echo Html::endTag('div');

        $this->endBody();
        echo Html::endTag('body');
        ?>
</html>

<?php
$this->endPage();

