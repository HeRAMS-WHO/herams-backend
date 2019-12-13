<?php

use prime\models\ar\Project;
use prime\widgets\menu\SideMenu;
use yii\helpers\Html;
SideMenu::begin([
    'footer' => $this->render('//footer', ['projects' => Project::find()->all()])
]);

echo Html::a('Projects', ['/project/index']);
if (\Yii::$app->user->can('admin')) {
    echo Html::a('Users', ['/user/index']);
}
echo Html::a(\Yii::t('app', 'Backend administration'), ['/admin/limesurvey']);
SideMenu::end();