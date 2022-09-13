<?php

declare(strict_types=1);

use prime\components\View;
use prime\helpers\CombinedHeramsFacilityRecord;
use prime\interfaces\HeramsFacilityRecordInterface;
use prime\models\ar\Facility;
use prime\values\FacilityId;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use function iter\flatten;
use function iter\map;

/**
 * @var \prime\models\forms\element\Chart $element
 * @var View $this
 * @var SurveyInterface $survey
 * @var \prime\helpers\HeramsVariableSet $variableSet
 * @var iterable<Facility> $facilities
 */

\prime\assets\IframeResizerContentWindowBundle::register($this);

$this->registerCss('.card-widget { height: 500px; }');
if (! $element->hasErrors()) {
    $data = flatten(map(static fn (
        Facility $facility
    ): HeramsFacilityRecordInterface => new CombinedHeramsFacilityRecord(
        $facility->getAdminRecord(),
        $facility->getDataRecord(),
        new FacilityId((string) $facility->id)
    ), $facilities));
    echo $element->renderWidget($variableSet, $this, $data);
} else {
    var_dump($element->errors);
}
// Remove the debug toolbar from this iframe.
if (class_exists('yii\debug\Module')) {
    $this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
}
