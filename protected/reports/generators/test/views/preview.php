<?php

use app\components\Html;
use yii\helpers\ArrayHelper;

/**
 * @var \prime\models\UserData $userData
 * @var \yii\web\View $this
 */
new \Befound\Components\Map();

echo Html::textarea('description', ArrayHelper::getValue($userData->getData(), 'description'));
echo Html::checkboxList('checkboxTest', ArrayHelper::getValue($userData->getData(), 'checkboxTest'), ['test1' => 'test1', 'test2' => 'test2']);