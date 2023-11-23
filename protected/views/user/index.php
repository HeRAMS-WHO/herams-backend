<?php

declare(strict_types=1);

use herams\common\models\PermissionOld;
use prime\assets\ReactAsset;
use prime\models\search\User;
use prime\widgets\menu\TabMenu;
use prime\widgets\Section;
use yii\data\ActiveDataProvider;
use yii\web\View;

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var User $searchModel
 */
$this->title = \Yii::t('app', 'Users');
ReactAsset::register($this);
$this->beginBlock('tabs');
echo TabMenu::widget([
    'tabs' => [
        [
            'permission' => PermissionOld::PERMISSION_ADMIN,
            'url' => ['admin/dashboard'],
            'title' => \Yii::t('app', 'Dashboard'),
        ],
        [
            'permission' => PermissionOld::PERMISSION_ADMIN,
            'url' => ['user/index'],
            'title' => \Yii::t('app', 'Users'),
        ],
        [
            'permission' => PermissionOld::PERMISSION_ADMIN,
            'url' => ['admin/share'],
            'title' => \Yii::t('app', 'Global permissions'),
        ],
    ],
]);
$this->endBlock();

Section::begin()
    ->withHeader(\Yii::t('app', 'Users'));

?>

<div id="UserIndex"></div>
<?php
Section::end();
