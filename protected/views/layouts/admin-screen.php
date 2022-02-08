<?php

declare(strict_types=1);

use prime\assets\AdminBundle;
use prime\components\View;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/**
 * @var View $this
 * @var string $content
 */

$this->beginPage();

$this->registerAssetBundle(\prime\assets\AppAsset::class);
$this->registerAssetBundle(AdminBundle::class);

?>
<!DOCTYPE html>
<html>

<head>
    <?= Html::csrfMetaTags() ?>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" integrity="sha256-l85OmPOjvil/SOvVt3HnSSjzF1TUMyT9eV0c2BzEGzU=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.5.2/flatpickr.min.css" integrity="sha256-TV6wP5ef/UY4bNFdA1h2i8ASc9HHcnl8ufwk94/HP4M=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.5.2/flatpickr.min.js" integrity="sha256-44TeE1bzEP4BfpL6Wb05CVgLDKN6OzOAI79XNMPR4Bs=" crossorigin="anonymous"></script>
    <title><?= Html::encode(app()->name . ' - ' . $this->title); ?></title>
    <link rel="icon" type="image/png" href="/img/herams_icon.png" />

    <?php
    $this->head();
    ?>
</head>


<?php
echo Html::beginTag('body', $this->params['body'] ?? []);
$this->beginBody();

echo Html::beginTag('header', ['class' => 'admin-header']);

echo $this->render('//user-menu', [
    'class' => ['admin']
]);
$links = [];
foreach ($this->getBreadcrumbCollection() as $breadcrumb) {
    $links[] = ArrayHelper::merge(
        $breadcrumb->getHtmlOptions(),
        [
            'label' => $breadcrumb->getLabel(),
            'url' => $breadcrumb->getUrl(),
            'encode' => $breadcrumb->getEncode(),
        ]
    );
}
if ($this->autoAddTitleToBreadcrumbs) {
    $links[] = $this->title;
}


echo '<!-- Breadcrumbs -->' . Breadcrumbs::widget([
        'itemTemplate' => "<li>{link}" . \prime\helpers\Icon::chevronRight() . " </li>\n",
        'activeItemTemplate' => "<li class=\"active\">{link}" . \prime\helpers\Icon::chevronRight() . "</li>\n",
        'homeLink' => [
            'label' => \Yii::t('app', 'Administration'),
            'url' => '/project/index'
        ],
        'links' => $links,
    ]);
echo Html::tag('span', $this->title, ['class' => 'page-title']);

echo Html::endTag('header');

echo Html::beginTag('div', ['class' => "main layout-{$this->context->layout} controller-{$this->context->id} action-{$this->context->action->id}"]);
echo $content;
echo Html::endTag('div');

$this->endBody();
echo Html::endTag('body');
?>

</html>

<?php
$this->endPage();
