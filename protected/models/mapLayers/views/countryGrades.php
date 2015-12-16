<?php

/**
 * @var \yii\web\View $this
 * @var \prime\models\Country $country
 * @var \SamIT\LimeSurvey\Interfaces\ResponseInterface[] $countryResponses
 * @var \prime\models\mapLayers\CountryGrades $mapLayer
 */

$lastResonse = $countryResponses[count($countryResponses) - 1]['response'];
$mapLayer = $this->context;
?>

<div class="row">
    <div class="col-xs-7">
        <h4><?=$country->name?></h4>
    </div>
    <div class="col-xs-5">
        <div class="row row-eq-height">
            <div class="col-xs-8">
                <?=\Yii::t('app', 'Grading status')?><br>
                <?=$lastResonse->getData()['GM00']?>
            </div>
            <div class="col-xs-4" style="margin-right: 15px; background-color: <?=$mapLayer->mapToColor($lastResonse->getData()['GM02'])?>"><?=$lastResonse->getData()['GM02']?></div>
        </div>

    </div>
</div>