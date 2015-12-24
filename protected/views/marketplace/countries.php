<?php

use app\components\Html;
?>
<style>
    .country-list-item {
        padding: 3px 5px 3px 5px;
        cursor: pointer;
        border-radius: 2px;
    }

    .country-list-item:hover {
        background-color: rgb(220, 220, 220);
    }
</style>
<?php
$countries = \yii\helpers\ArrayHelper::map($countries, 'iso_3', 'name');
asort($countries);
foreach($countries as $iso_3 => $country) {
    echo Html::tag('div', $country, ['class' => 'country-list-item', 'data-iso3' => $iso_3]);
}