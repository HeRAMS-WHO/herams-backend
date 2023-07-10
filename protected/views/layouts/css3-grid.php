<?php

declare(strict_types=1);

use prime\assets\DashboardBundle;
use prime\components\View;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\YiiAsset;
use yii\widgets\Breadcrumbs;

/**
 * @var View $this
 * @var string $content
 */

$this->beginPage();

$this->registerAssetBundle(DashboardBundle::class);
$this->registerAssetBundle(YiiAsset::class);

?>
<!DOCTYPE html>
<html "data"="true">

<head>
    <?= \yii\helpers\Html::tag('meta', '', [
        'content' => rtrim(Url::to(['/api'], true), '/'),
        'name' => 'api',
    ]) ?>

    <?= Html::csrfMetaTags() ?>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= Html::encode(app()->name . ' - ' . $this->title); ?></title>
    <link rel="icon" type="image/png" href="/img/herams_icon.png" />

<?php
    $this->head();
?>
</head>


<?php
echo Html::beginTag('body', $this->params['body'] ?? []);
$this->beginBody();

echo $this->render('//user-menu', [
    'class' => ['front'],
]);
?>
<div class="title">
<?php
    $links = [];
foreach ($this->getBreadcrumbCollection() as $breadcrumb) {
    $links[] = [
        'label' => $breadcrumb->getLabel(),
        'url' => $breadcrumb->getUrl(),
        'encode' => true,
    ];
}

echo '<!-- Breadcrumbs -->' . Breadcrumbs::widget([
    'itemTemplate' => "<li>{link}" . \prime\helpers\Icon::chevronRight() . " </li>\n",
    'activeItemTemplate' => "<li class=\"active\">{link}" . \prime\helpers\Icon::chevronRight() . "</li>\n",
    'homeLink' => [
        'label' => \Yii::t('app', 'World overview'),
        'url' => '/',
    ],
    'links' => $links,
]);
echo Html::tag('span', $this->title, [
    'class' => 'header',
]);
?></div>
<?php
echo $content;
$this->endBody();
?>
</body>

</html>
<?php
    $this->endPage();
