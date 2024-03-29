<?php

declare(strict_types=1);

use prime\assets\PdfBundle;
use yii\helpers\Html;

/**
 * @var \prime\components\View $this
 * @var string $content
 */
$this->beginPage();

$this->registerAssetBundle(PdfBundle::class);
$this->registerAssetBundle(\yii\web\YiiAsset::class);

?>
<html>

<head>
    <?= Html::csrfMetaTags() ?>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" integrity="sha256-l85OmPOjvil/SOvVt3HnSSjzF1TUMyT9eV0c2BzEGzU=" crossorigin="anonymous" />
    <title><?= Html::encode(app()->name . ' - ' . $this->title); ?></title>
    <link rel="icon" type="image/png" href="/img/herams_icon.png" />

<?php
    $this->head();
?>
</head>


<?php
echo Html::beginTag('body', $this->params['body'] ?? []);
$this->beginBody();
echo $content;
$this->endBody();
?>
</body>

</html>
<?php
    $this->endPage();
