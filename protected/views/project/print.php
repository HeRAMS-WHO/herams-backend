<?php

/** @var View $this */
/** @var Project $project */
/** @var Page $page */

use prime\helpers\Icon;
use yii\helpers\Html;
use SamIT\LimeSurvey\Interfaces\AnswerInterface;
use SamIT\LimeSurvey\Interfaces\GroupInterface as GroupInterface;

$this->title = $project->getDisplayField();

$pages = [];

if (isset($page)) {
    $pages[] = $page;
} else {
    $projectPages = $project->pages;
    foreach ($projectPages as $key => $page) {
        $subpages = [];
        foreach ($page->getChildPages($survey) as $child) {
            $subpages[] = $child;
        }

        if (count($subpages) == 0) {
            $subpages[] = $page;
        }
        $pages = array_merge($pages, $subpages);
    }
}

$groups = $project->getSurvey()->getGroups();
usort($groups, function (GroupInterface $a, GroupInterface $b) {
    return $a->getIndex() <=> $b->getIndex();
});


$date = $filterModel->attributes['date'];
$filters = $filterModel->attributes['advanced'];
if (isset($date) || (is_array($filters) && count($filters) > 0)) {
    $filtersContent = Html::beginTag('div', ['class' => 'filters-list']);
    $filtersContent .= "<span class='list-title'>" . \Yii::t('app', 'Filters') . "</span> : ";
    if (isset($date)) {
        $filtersContent .= "<strong>" . \Yii::t('app', 'Date') . "</strong> {$date} ";
    }
    if (is_array($filters) && count($filters) > 0) {
        foreach ($groups as $group) {
            foreach ($group->getQuestions() as $question) {
                if (($answers = $question->getAnswers()) !== null
                    && $question->getDimensions() === 0
                ) {
                    $items = \yii\helpers\ArrayHelper::map(
                        $answers,
                        function (AnswerInterface $answer) {
                            return $answer->getCode();
                        },
                        function (AnswerInterface $answer) {
                            return strtok(strip_tags($answer->getText()), ':(');
                        }
                    );
                    if (array_key_exists($question->getTitle(), $filters)) {
                        $attribute = "adv_{$question->getTitle()}";
                        $filtersContent .= "<strong>{$filterModel->getAttributeLabel($attribute)} : </strong> ";
                        $answersList = [];
                        foreach ($filters[$question->getTitle()] as $filter) {
                            $answersList[] = $items[$filter];
                        }
                        $answersList = implode(" -- ", $answersList);
                        $filtersContent .= "<span class='values'>{$answersList}</span>";
                    }
                }
            }
        }
    }
    $filtersContent .= Html::endTag('div');
}
?>
<table>
    <thead>
        <tr>
            <td>
                <div class='title'>
                    <?= $project->getDisplayField(); ?>
                    <div class="filters topbar">
                        <div class="count">
                            <?php
                            echo Icon::healthFacility();
                            echo Html::tag('em', count($data));
                            echo \Yii::t('app', 'Health Facilities');
                            ?>
                        </div>
                        <div class="count">
                            <?php
                            echo Icon::contributors();
                            echo Html::tag('em', $project->contributorCount);
                            echo \Yii::t('app', 'Contributors');
                            ?>
                        </div>
                        <div class="count">
                            <?php
                            echo Icon::sync() . \Yii::t('app', 'Latest update');
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
            </td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <?php

                foreach ($pages as $key => $subpage) {
                    echo isset($filtersContent) ? $filtersContent : '';
                    echo Html::beginTag('div', ['class' => 'content']);
                    echo "<h2 class='page-title'>{$subpage->title}</h2>";

                    $elements = $subpage->getChildElements();
                    foreach ($elements as $element) {
                        Yii::beginProfile('Render element ' . $element->id);
                        echo "<!-- Begin chart {$element->id} -->";
                        $level = ob_get_level();
                        ob_start();
                        try {
                            echo $element->getWidget($survey, $data, $subpage)->run();
                            echo ob_get_clean();
                        } catch (Throwable $t) {
                            while (ob_get_level() > $level) {
                                ob_end_clean();
                            }
                        }
                        echo "<!-- End chart {$element->id} -->";
                        Yii::endProfile('Render element ' . $element->id);
                        unset($element);
                    }
                    unset($elements);
                    echo Html::endTag('div');
                    if ($key !== array_key_last($pages)) {
                        echo "<div class='page-break'></div>";
                    }
                    unset($subpage);
                }
                unset($pages);
                unset($filtersContent);
                ?>
            </td>
        </tr>
    </tbody>
</table>