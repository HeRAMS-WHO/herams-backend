<?php

use function iter\map;
use prime\widgets\nestedselect\NestedSelect;
use yii\helpers\Html;

/* @var \yii\web\View $this */
/* @var \prime\models\ar\Tool $project */
/* @var \prime\models\forms\ResponseFilter $filterModel */

echo Html::beginForm(['projects/view', 'id' => $project->id], 'get', [
        'autocomplete' => 'off',
        'class' => 'filters'
    ]);

    ?>
    <div class="basic">
        <?php

        echo NestedSelect::widget([
            'placeholder' => 'Location',
            'selection' => $filterModel->locations,
            'name' => Html::getInputName($filterModel, 'locations'),
            'options' => [
                'class' => [
                    'filter',
                    'filter_where'
                ]
            ],
            'items' => $filterModel->nestedLocationOptions()
        ]);

        echo Html::tag('span', Html::activeTextInput($filterModel, 'date', [
            'autocomplete' => 'off',
            'size' => 8,
        ]), [
            'class' => 'filter filter_when',
        ]);
        $id = \yii\helpers\Json::encode('#' . Html::getInputId($filterModel, 'date'));
        $this->registerJs("flatpickr($id);");

        echo NestedSelect::widget([
            'name' => Html::getInputName($filterModel, 'types'),
            'placeholder' => 'Type',
            'options' => [
                'class' => [
                    'filter',
                    'filter_type'
                ]
            ],
            'selection' => $filterModel->types,
            'items' => $types
        ]);
        ?>

    </div>
    <div class="advanced">
        <button id="advanced" class="filter filter_advanced">Advanced filters</button>
        <div class="modal">
            <?php
            $this->registerJs(<<<JS
    document.getElementById('advanced').addEventListener('click', function(e) {
        e.preventDefault();
        e.target.nextElementSibling.classList.toggle('visible');
    });

JS
            );
            /** @var \SamIT\LimeSurvey\Interfaces\SurveyInterface $survey */
            foreach($survey->getGroups() as $group) {
                foreach ($group->getQuestions() as $question) {
                    $answers = $question->getAnswers();
                    if ($answers === null) continue;
                    if ($question->getDimensions() > 0) continue;
                    echo Html::tag('h2', explode(':', $question->getText(), 2)[0]);
                    $name = Html::getInputName($filterModel, 'advanced');
                    $items = \yii\helpers\ArrayHelper::map($answers, \iter\fn\method('getCode'), \iter\fn\method('getText'));
                    echo Html::checkboxList("{$name}[{$question->getTitle()}]", null, $items);
                }
            }
            ?>

        </div>
    </div>
    <div class="buttons">
        <button type="reset"><i class="fas fa-times"></i> Clear all</button>
        <button type="submit"><i class="fas fa-check"></i> Apply all</button>
    </div>

<?php
    echo Html::endForm();