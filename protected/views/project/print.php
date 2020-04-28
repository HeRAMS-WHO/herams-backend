<?php

/** @var View $this */
/** @var Project $project */
/** @var Page $page */

use prime\helpers\Icon;
use yii\helpers\Html;
use SamIT\LimeSurvey\Interfaces\GroupInterface as GroupInterface;

$this->params['body'] = [
    'class' => ['print']
];

$this->title = $project->getDisplayField();
?>
<div class='title'>
    <?= $project->getDisplayField(); ?>
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
</div>
<?php
$date = $filterModel->attributes['date'];
$filters = $filterModel->attributes['advanced'];
if ($date != null || (is_array($filters) && count($filters) > 0)) {
    echo Html::beginTag('div', ['class' => 'filters-list']);
    echo "<span class='list-title'>Filters</span> : ";
    if ($date != null) {
        echo "<strong>Date</strong> ".$date." ";
    }
    if (is_array($filters) && count($filters) > 0) {
        $groups = $project->getSurvey()->getGroups();
        usort($groups, function (GroupInterface $a, GroupInterface $b) {
            return $a->getIndex() <=> $b->getIndex();
        });
        foreach ($groups as $group) {
            foreach ($group->getQuestions() as $question) {
                if (($answers = $question->getAnswers()) !== null
                    && $question->getDimensions() === 0) {
                    if (array_key_exists($question->getTitle(), $filters)) {
                        $attribute = "adv_{$question->getTitle()}";
                        echo "<strong>".$filterModel->getAttributeLabel($attribute)." : </strong> ";
                        $labels = $filterModel->advancedOptions($question->getTitle());
                        $answers = [];
                        foreach ($filters[$question->getTitle()] as $filter) {
                            $answers[] = $labels[$filter];
                        }
                        echo "<span class='values'>".implode(" / ", $answers)."</span>";
                    }
                }
            }
        }
    }
    echo Html::endTag('div');
}
    
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