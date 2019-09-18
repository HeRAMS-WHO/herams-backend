<?php
/* @var $this \yii\web\View */

use prime\assets\DashboardBundle;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $content string */
    $this->beginPage();

    $this->registerAssetBundle(DashboardBundle::class);
    $this->registerAssetBundle(\yii\web\YiiAsset::class);

?>
<html>

<head>
    <?= Html::csrfMetaTags() ?>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" integrity="sha256-l85OmPOjvil/SOvVt3HnSSjzF1TUMyT9eV0c2BzEGzU=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.5.2/flatpickr.min.css" integrity="sha256-TV6wP5ef/UY4bNFdA1h2i8ASc9HHcnl8ufwk94/HP4M=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.5.2/flatpickr.min.js" integrity="sha256-44TeE1bzEP4BfpL6Wb05CVgLDKN6OzOAI79XNMPR4Bs=" crossorigin="anonymous"></script>
<?php
    $this->head();
?>
</head>


<?php
echo Html::beginTag('body', $this->params['body'] ?? []);
$this->beginBody();

echo $this->render('//user-menu');
?>
<div class="title">
<?php
    echo '<!-- Breadcrumbs -->' . Breadcrumbs::widget([
        'homeLink' => [
            'label' => \Yii::t('app', 'World overview'),
            'url' => '/'
        ],
        'links' => $this->params['breadcrumbs'] ?? []
    ]);
echo Html::tag('span', $this->title, ['class' => 'header']);
?></div>
<?php
    echo $content;
    $this->endBody();
?>
</body>

</html>
<?php
    $this->endPage();