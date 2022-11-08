<?php

declare(strict_types=1);

use herams\common\domain\user\User;
use herams\common\models\Permission;
use prime\helpers\Icon;
use prime\repositories\UserNotificationRepository;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var View $this
 * @var array|null $class
 */

$this->registerCss(
    <<<CSS
.user-menu a .badge {
  position: absolute;
  margin-top: -8px;
  margin-left: -5px;
  display: inline-block;
  padding: 0.25em 0.4em;
  font-size: 50%;
  font-weight: 400;
  border-radius: 0.25rem;
  background: red;
  color: white; 
}
CSS
);


echo Html::beginTag('div', [
    'class' => array_merge(['user-menu'], $class ?? []),
]);
/** @var User $user */
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
echo Html::a(Icon::home(), ['/'], [
    'class' => 'home',
]);
echo Html::a(Icon::admin(), ['/project/index'], [
    'class' => 'admin',
]);
echo Html::a(Icon::star(), ['/user/favorites']);
echo Html::a(Icon::user(), ['/user/profile']);
$userNotificationService = \Yii::createObject(UserNotificationRepository::class);
if ($user && ($notificationCount = $userNotificationService->getNewNotificationCountForUser($user)) > 0) {
    echo Html::a(Icon::bell() . Html::tag('div', $notificationCount, [
        'class' => ['badge'],
    ]), ['/user/notifications']);
}
echo Html::a(Icon::question() . '<small>' . Icon::external_link() . '</small>', Url::to('https://docs.herams.org/'), [
    'target' => '_blank',
]);
echo Html::a(Icon::signOutAlt(), ['/session/delete'], [
    'data-method' => 'delete',
]);
?>
</div>
