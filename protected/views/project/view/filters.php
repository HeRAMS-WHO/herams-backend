<?php

use function iter\map;
use prime\widgets\nestedselect\NestedSelect;
use SamIT\LimeSurvey\Interfaces\AnswerInterface;
use yii\bootstrap\Modal;
use yii\helpers\Html;

/* @var \yii\web\View $this */
/* @var \prime\models\ar\Tool $project */
/* @var \prime\models\forms\ResponseFilter $filterModel */

echo Html::beginForm(['project/view', 'id' => $project->id], 'get', [
        'autocomplete' => 'off',
        'class' => 'filters'
    ]);

    ?>
    <div class="basic">
        <?php

        echo NestedSelect::widget([
            'placeholder' => \Yii::t('app', 'All locations'),
            'attribute' => 'locations',
            'model' => $filterModel,
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
            'attribute' => 'types',
            'model' => $filterModel,
            'placeholder' => \Yii::t('app', 'All facility types'),
            'options' => [
                'class' => [
                    'filter',
                    'filter_type'
                ]
            ],
            'items' => $types
        ]);
        ?>

    </div>
    <div class="advanced">
        <button type="button" id="advanced" class="filter filter_advanced">Advanced filters</button>
        <div class="modal" id="advanced-modal">
            <button type="button" class="close"></button>
            <div class="filter filter_search">
                <input id="search-filter">
            </div>
            <?php
            $this->registerJs(<<<JS
    document.getElementById('search-filter').addEventListener('input', function(e) {
        let search = e.target.value.toLocaleUpperCase();
        document.querySelectorAll('#advanced-modal label.group').forEach(function(el) {
            el.parentNode.parentNode.classList.toggle('hidden', !el.textContent.toLocaleUpperCase().includes(search));
        })
    });
    document.getElementById('advanced').addEventListener('click', function(e) {
        document.body.setAttribute('data-modal', e.target.nextElementSibling.id);
        // e.target.nextElementSibling.classList.toggle('visible');
    }, {
        passive: true,
    });
    document.body.addEventListener('click', function(e) {
        if (e.target === document.body
            || e.target.matches('button.close')
        ) {
            document.body.removeAttribute('data-modal');    
        } else {
            
        }
        
    }, );

JS
            );
            /** @var \SamIT\LimeSurvey\Interfaces\SurveyInterface $survey */
            foreach($survey->getGroups() as $group) {
                foreach ($group->getQuestions() as $question) {
                    $answers = $question->getAnswers();
                    if ($answers === null) continue;
                    if ($question->getDimensions() > 0) continue;
                    $title =  explode(':', $question->getText(), 2)[0];
//                    echo Html::tag('h2', $title);
                    $name = Html::getInputName($filterModel, 'advanced');

                    $items = \yii\helpers\ArrayHelper::map(
                            $answers, \iter\fn\method('getCode'),
                            function(AnswerInterface $answer) {
                                return explode(':', $answer->getText(), 2)[0];
                            }
                    );
//                    echo Html::checkboxList("{$name}[{$question->getTitle()}]", null, $items);
                    echo NestedSelect::widget([
                            'expanded' => true,
                            'options' => [
                                'class' => [
                                    'inline'
                                ]
                            ],
                            'value' => $filterModel->advanced[$question->getTitle()] ?? [],
                            'name' => "{$name}[{$question->getTitle()}]",
                            'items' => [
                                $title => $items,
                            ]
                    ]);
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