<?php

use app\components\Html;

/**
 * @var \prime\models\UserData $userData
 * @var \yii\web\View $this
 */
new \Befound\Components\Map();

echo Html::textarea('description', $userData->getData()['description']);
echo Html::checkboxList('checkboxTest', $userData->getData()->asArray()['checkboxTest'], ['test1' => 'test1', 'test2' => 'test2']);