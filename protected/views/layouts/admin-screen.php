<?php

declare(strict_types=1);

use prime\assets\AdminBundle;
use prime\components\View;
use prime\objects\Breadcrumb;
use prime\widgets\Breadcrumbs;
use yii\helpers\Html;

/**
 * @var View $this
 * @var string $content
 */

$this->beginPage();

$this->registerAssetBundle(\prime\assets\AppAsset::class);
$this->registerAssetBundle(AdminBundle::class);
?>
<!DOCTYPE html>
<html lang="<?= \Yii::$app->language ?>">

<head>
    <?= Html::csrfMetaTags() ?>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= Html::encode($this->title); ?></title>
    <link rel="icon" type="image/png" href="/img/herams_icon.png" />

    <?php
    $this->head();
?>
</head>


<?php
echo Html::beginTag('body', $this->params['body'] ?? []);
$this->beginBody();

echo Html::beginTag('header', [
    'class' => 'admin-header',
]);

echo $this->render('//user-menu', [
    'class' => ['admin'],
]);

$breadCrumbs = $this->getBreadcrumbCollection();
if (isset($this->title)) {
    //$breadCrumbs->add(new Breadcrumb($this->title));
    $breadCrumbs_title = Html::a($this->title, '', []);
    $breadCrumbs->add(new Breadcrumb($breadCrumbs_title));
}
Breadcrumbs::begin()
    ->forCollection($breadCrumbs)
    ->end();
echo Html::tag('span', $this->params['subject'] ?? $this->title, [
    'class' => 'page-title',
]);

echo Html::endTag('header');

echo Html::beginTag('div', [
    'class' => "main layout-{$this->context->layout} controller-{$this->context->id} action-{$this->context->action->id}",
]);

echo $content;
echo Html::endTag('div');

$this->endBody();
echo Html::endTag('body');
?>

</html>

<?php
$this->endPage();
