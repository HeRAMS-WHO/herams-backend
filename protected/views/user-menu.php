<?php

use prime\helpers\Icon;
use yii\helpers\Html;
use yii\helpers\Url;

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
echo Html::a(Icon::home(), ['/'], ['class'=>'home']);
echo Html::a(Icon::admin(), ['/admin/dashboard'], ['class'=>'admin']);
echo Html::a(Icon::star(), ['/user/favorites']);
echo Html::a(Icon::user(), ['/user/account']);
echo Html::a(Icon::question(), Url::to('https://docs.herams.org/'), ['target' => '_blank']);
echo Html::a(Icon::signOutAlt(), ['/session/delete'], ['data-method' => 'delete']);
?>
</div>