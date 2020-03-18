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
        echo Icon::healthFacility() . ' ' . \Yii::t('app', 'Health Facilities');
        echo Html::tag('em', count($data));
        ?>
    </div>
    <div class="count">
        <?php
        echo Icon::contributors() . ' ' . \Yii::t('app', 'Contributors');
        echo Html::tag('em', $project->contributorCount);
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
echo Html::beginTag('div', ['class' => 'content']);

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
        \Yii::error($t);
        echo Html::tag(
            'div',
            "Rendering this element caused an error: <strong>{$t->getMessage()}</strong>. The most common reason for the error is an invalid question code in its configuration. You can edit the element " . Html::a('here', ['/element/update', 'id' => $element->id]) . '.',
            [
                'class' => 'element',
            ]
        );
    }
    echo "<!-- End chart {$element->id} -->";
    Yii::endProfile('Render element ' . $element->id);
}

echo Html::endTag('div');