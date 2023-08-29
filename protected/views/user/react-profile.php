<?php

declare(strict_types=1);

use herams\common\domain\user\User;
use prime\widgets\menu\UserTabMenu;
use prime\widgets\Section;
use yii\helpers\Html;
use yii\web\View;
use prime\assets\ReactAsset;

ReactAsset::register($this);

/**
 * @var View $this
 * @var User $model
 */

$this->beginBlock('tabs');
echo UserTabMenu::widget([
    'user' => $model,
]);
$this->endBlock();

$this->title = Yii::t('app', 'Profile');

$model->setOnlyFields(['id','email','name','language','newsletter_subscription']);

$userEncoded = $model->toBase64();

Section::begin()
    ->withHeader($this->title);
?>
<!-- Mount point for the React component -->
    <div id="Profile" data-user="<?= Html::encode($userEncoded) ?>">
    </div>


<?php
Section::end();
