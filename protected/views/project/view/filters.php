<?php

use prime\models\forms\ResponseFilter as ResponseFilter;
use prime\widgets\nestedselect\NestedSelect;
use SamIT\LimeSurvey\Interfaces\AnswerInterface;
use SamIT\LimeSurvey\Interfaces\GroupInterface as GroupInterface;
use SamIT\LimeSurvey\Interfaces\QuestionInterface as QuestionInterface;
use yii\helpers\Html;
use yii\helpers\Json as Json;
use yii\helpers\Url;

/* @var \yii\web\View $this */
/* @var \prime\models\ar\Project $project */
/* @var ResponseFilter $filterModel */

echo Html::beginForm(['project/view', 'id' => $project->id,
    'page_id' => \Yii::$app->request->getQueryParam('page_id'),
    'parent_id' => \Yii::$app->request->getQueryParam('parent_id')
], 'get', [
    'autocomplete' => 'off',
    'class' => 'filters'
]);

    ?>
    <div class="basic">
        <?php
        echo \kartik\select2\Select2::widget([
//            'placeholder' => \Yii::t('app', 'All locations'),
            'attribute' => 'locations',
            'model' => $filterModel,
            'options' => [
                'multiple' => true,
                'class' => [
                    'filter',
                    'filter_where'
                ]
            ],
            'data' => $filterModel->nestedLocationOptions()
        ]);

        echo Html::tag('span', Html::activeTextInput($filterModel, 'date', [
            'autocomplete' => 'off',
            'size' => 8,
        ]), [
            'class' => 'filter filter_when',
        ]);
        $id = Json::encode('#' . Html::getInputId($filterModel, 'date'));
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
        <div class="filter-modal" id="advanced-modal">
            <button type="button" class="close"></button>
            <div class="filter filter_search">
                <input id="search-filter">
                <ul class="hint">
                    <li>You may search for multiple terms, only results that contain all terms are shown</li>
                    <li>Search also uses the group name, for example try typing "Trauma"</li>
                    <li>After closing this screen you must click <b>Apply filters</b> to see the changes</li>
                </ul>
            </div>
            <?php

            $this->registerJs(<<<JS
    document.getElementById('search-filter').addEventListener('input', function(e) {
        let tokens = e.target.value.toLocaleUpperCase().split(' ').filter(x => x);
        document.querySelectorAll('#advanced-modal label.group').forEach(function(el) {
            let hidden = !tokens.every((token) => el.getAttribute('data-keywords').toLocaleUpperCase().includes(token));
            el.parentNode.parentNode.classList.toggle('hidden', hidden);
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
            $groups = $survey->getGroups();
            usort($groups, function(GroupInterface $a, GroupInterface $b) {
                return $a->getIndex() <=> $b->getIndex();
            });
            $renderFilter = function(
                QuestionInterface $question,
                GroupInterface $group,
                ResponseFilter $filterModel,
                array $items
            ) {
                $title =  explode(':', strip_tags($question->getText()), 2)[0];
                $name = Html::getInputName($filterModel, 'advanced');
                echo NestedSelect::widget([
                    'expanded' => true,
                    'options' => [
                        'class' => [
                            'inline'
                        ]
                    ],
                    'value' => $filterModel->advanced[$question->getTitle()] ?? [],
                    'name' => "{$name}[{$question->getTitle()}]",
                    'groupLabelOptions' => [
                        'data-keywords' => implode(' ', [$group->getTitle(), $title])
                    ],
                    'items' => [
                        $title => $items,
                    ]
                ]);
            };
            foreach($survey->getGroups() as $group) {
                foreach ($group->getQuestions() as $question) {
                    if (($answers = $question->getAnswers()) !== null
                        && $question->getDimensions() === 0) {
                        $items = \yii\helpers\ArrayHelper::map(
                            $answers, \iter\fn\method('getCode'),
                            function(AnswerInterface $answer) {
                                return explode(':', strip_tags($answer->getText()), 2)[0];
                            }
                        );
                        $renderFilter($question, $group, $filterModel, $items);
                    } elseif ($question->getDimensions() === 1) {
                        echo $this->render('multiplechoicefilter', [
                            'question' => $question,

                        ]);
                        continue;
                    }


                }
            }
            ?>

        </div>
    </div>
    <div class="buttons">
        <button type="button" id="clear"><i class="fas fa-times"></i> Clear all</button>
        <script>
            document.getElementById('clear').addEventListener('click', function() {
                window.location.href = <?= Json::encode(Url::to([
                    'project/view',
                    'id' => $project->id,
                    'page_id' => \Yii::$app->request->getQueryParam('page_id'),
                    'parent_id' => \Yii::$app->request->getQueryParam('parent_id')
                ])) ?>;
            })
        </script>
        <button type="submit"><i class="fas fa-check"></i> Apply all</button>
    </div>
    <div class="filter count"><?= count($data); ?></div>

<?php
    echo Html::endForm();