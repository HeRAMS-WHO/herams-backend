<?php

/** @var View $this */
/** @var Project $project */
/** @var Page $page */

use prime\interfaces\PageInterface;
use prime\helpers\Icon;
use yii\helpers\Html;

$this->title = $project->getDisplayField();
?>
<div class="filters">
    <div class="count">
        <?php
        echo Icon::healthFacility();
        echo Html::tag('em', count($data));
        echo ' ' . \Yii::t('app', 'Health Facilities');
        ?>
    </div>
    <div class="count">
        <?php
        echo Icon::contributors();
        echo Html::tag('em', $project->contributorCount);
        echo ' ' . \Yii::t('app', 'Contributors');
        ?>
    </div>
    <div class="count">
        <?php
        echo Icon::sync() . ' ' . \Yii::t('app', 'Latest update');
        /** @var HeramsResponseInterface $heramsResponse */
        $lastUpdate = null;
        foreach ($data as $heramsResponse) {
            $date = $heramsResponse->getDate();
            if (!isset($lastUpdate) || (isset($date) && $date->greaterThan($lastUpdate))) {
                $lastUpdate = $date;
            }
        }
        echo Html::tag('em', $lastUpdate ? $lastUpdate->diffForHumans() : \Yii::t('app', 'N/A'));
        ?>
    </div>
</div>
<?php
foreach ($project->pages as $page) {
    echo Html::beginTag('div', ['class' => 'content']);
    echo "<h2 class='page-title'>".$this->title.' - '.$page->title."</h2>";
    foreach ($page->getChildElements() as $element) {
        Yii::beginProfile('Render element ' . $element->id);
        echo "<!-- Begin chart {$element->id} -->";
        $level = ob_get_level();
        ob_start();
        try {
            echo $element->getWidget($survey, $data, $page)->run();
            echo ob_get_clean();
        } catch (Throwable $t) {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }
        }
        echo "<!-- End chart {$element->id} -->";
        Yii::endProfile('Render element ' . $element->id);
    }
    echo Html::endTag('div');
    echo "<div class='page-break'></div>";
}