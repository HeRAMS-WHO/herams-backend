<?php

use prime\helpers\Icon;
use yii\helpers\Html;
use yii\helpers\Url;
use prime\models\ar\Permission;

/* @var array $class */


echo Html::beginTag('div', ['class' => array_merge(['user-menu'], $class ?? [])]);
/** @var \prime\models\ar\User $user */
$user = \Yii::$app->user->identity;
?>
<?php
if (YII_DEBUG) {
    echo Html::tag('span', "DEBUG CURRENT LANGUAGE: " . \Yii::$app->language);
}
$lang = \Yii::$app->language;
if (strpos($lang, '-')) {
    $lang = explode('-', $lang)[0];
}
if (app()->user->can(Permission::PERMISSION_ADMIN)) {
    echo Html::a(Icon::level(), ['/admin']);
}
echo Html::a(Icon::home(), ['/'], ['class' => 'home']);
echo Html::a(Icon::admin(), ['/project/index'], ['class' => 'admin']);
echo Html::a(Icon::star(), ['/user/favorites']);
echo Html::a(Icon::user(), ['/user/account']);
echo Html::a(Icon::question().'<small>'.Icon::external_link().'</small>', Url::to('https://docs.herams.org/'), ['target' => '_blank']);
echo Html::a(Icon::signOutAlt(), ['/session/delete'], ['data-method' => 'delete']);
?>
</div>