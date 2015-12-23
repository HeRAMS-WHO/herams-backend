<?php
$countries = \yii\helpers\ArrayHelper::map($countries, 'iso_3', 'name');
asort($countries);
foreach($countries as $iso_3 => $country) {
    echo $country . '<br>';
}