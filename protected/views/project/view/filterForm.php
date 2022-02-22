<?php

declare(strict_types=1);

use app\components\ActiveForm;
use app\components\Form;
use kartik\select2\Select2;
use SamIT\LimeSurvey\Interfaces\AnswerInterface;
use SamIT\LimeSurvey\Interfaces\GroupInterface as GroupInterface;
use yii\helpers\Html;
use yii\helpers\Json as Json;

/**
 * @var \prime\models\forms\ResponseFilter $filterModel
 * @var \prime\models\ar\Project $project
 * @var \prime\components\View $this
 *
 */


$form = ActiveForm::begin([
        'method' => 'GET',
        "type" => ActiveForm::TYPE_HORIZONTAL,
    ]);
    /** @var \SamIT\LimeSurvey\Interfaces\SurveyInterface $survey */
    $groups = $project->getSurvey()->getGroups();
    usort($groups, function (GroupInterface $a, GroupInterface $b) {
        return $a->getIndex() <=> $b->getIndex();
    });

    $filters = [];

    $matcher = new \yii\web\JsExpression(<<<JS
    function(params, data) {
        if (typeof params.term === 'undefined' || params.term.length === 0) {
            return data;
        }
        if (typeof data.text === 'undefined') {
            return null;
        }
        let tokens = params.term.toLowerCase().split(' ').map(s => s.trim());
        
        let stringMatcher = (subject) => {
            let data = subject.text.toLowerCase();
            for (let i = tokens.length - 1; i >= 0; i--) {
                // Match a token.
                if (tokens[i].length === 0) {
                    continue;
                }
                
                if (!data.includes(tokens[i])) {
                    return false;
                }
            }
            return true;
        };
        
        // Check if we have children
        if (data.children) {
            // filter the children instead.
            let filtered = data.children.filter(stringMatcher);
            if (filtered.length > 0) {
                let result = {};
                Object.assign(result, data);
                result.children = filtered;
                return result;
            }
        } else if (stringMatcher(data)) {
            return data;
        } 
        
        return null;
    }
JS
    );



    $date = $filterModel->date;
    $filtersList = $filterModel->advanced;
    if (isset($date) || (is_array($filtersList) && count($filtersList) > 0)) {
        echo Html::beginTag('div', ['class' => 'selected-filters-list']);
        echo "<span class='list-title'>" . \Yii::t('app', 'Filters') . " :</span>";
        if (isset($date)) {
            echo "<span class='label'>" . \Yii::t('app', 'Date') . " : </span> <span class='value'>{$date}</span> ";
        }
        if (is_array($filtersList) && count($filtersList) > 0) {
            foreach ($groups as $group) {
                foreach ($group->getQuestions() as $question) {
                    if (
                        ($answers = $question->getAnswers()) !== null
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

                        if (array_key_exists($question->getTitle(), $filtersList)) {
                            $attribute = "adv_{$question->getTitle()}";
                            echo "<span class='label'>{$filterModel->getAttributeLabel($attribute)} :</span>";
                            $answersList = [];
                            foreach ($filtersList[$question->getTitle()] as $filter) {
                                $answersList[] = $items[$filter];
                            }
                            $answersList = implode(" -- ", $answersList);
                            echo "<span class='value'>{$answersList}</span>";
                        }
                    }
                }
            }
        }
        echo Html::a('X', [
            'project/view',
            'id' => $project->id,
            'page_id' => \Yii::$app->request->getQueryParam('page_id'),
            'parent_id' => \Yii::$app->request->getQueryParam('parent_id')
        ], ['class' => 'btn btn-close']);
        echo Html::endTag('div');
    }

    foreach ($groups as $group) {
        foreach ($group->getQuestions() as $question) {
            if (
                ($answers = $question->getAnswers()) !== null
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

                $name = \yii\helpers\Html::getInputName($filterModel, 'advanced');
                $attribute = "adv_{$question->getTitle()}";
                $filters[$group->getTitle()][$attribute] = [
                    'type' => Form::INPUT_WIDGET,
                    'widgetClass' => Select2::class,

                    'fieldConfig' => [
                        'labelOptions' => [
                            'title' => implode(': ', [
                                trim(html_entity_decode($group->getTitle())),
                                $filterModel->getAttributeLabel($attribute)
                            ]),
                            'data-keywords' => implode(' ', [
                                trim(html_entity_decode($group->getTitle())),
                                $filterModel->getAttributeLabel($attribute)
                            ])
                        ],
                    ],
                    'options' => [
                        'name' => "{$attribute}[]",
                        'options' => [
                            'multiple' => true,


                        ],
                        'pluginOptions' => [
                            'matcher' => $matcher
                        ],

                        'data' => $filterModel->advancedOptions($question->getTitle()),


                    ]
                ];
            } elseif ($question->getAnswers() !== null && $question->getDimensions() === 1) {
                continue;
                echo $this->render('multiplechoicefilter', [
                    'question' => $question,
                ]);
                continue;
            }
        }
    }

    $id = Json::encode('#' . Html::getInputId($filterModel, 'date'));
    $this->registerJs("flatpickr($id,{maxDate:'today'});");
    $this->registerJs("document.getElementById('search-filter').addEventListener('keydown', function(e) {
    if (e.keyIdentifier == 'U+000A' || e.keyIdentifier == 'Enter' || e.keyCode == 13) {
        e.preventDefault();
        return false;
    }
    }, true);");




    echo Html::beginTag('div', ['class' => 'filterlist']);

    echo Html::beginTag('div', ['class' => 'group']);
        echo Html::beginTag('div', ['class' => 'group-title']);
            echo "Date";
        echo Html::endTag('div');
            echo Form::widget([
                'form' => $form,
                'model' => $filterModel,
                'columns' => 2,
                "attributes" => \iter\toArrayWithKeys([
                    'date' => [
                        'options' => [
                            'autocomplete' => 'off',
                            'size' => 8
                        ],
                        'fieldConfig' => [
                            'labelOptions' => [
                                'title' => implode(': ', [
                                    trim(html_entity_decode($filterModel->getAttributeLabel('date'))),
                                    $filterModel->getAttributeLabel('date')
                                ]),
                                'data-keywords' => implode(' ', [
                                    trim(html_entity_decode($filterModel->getAttributeLabel('date'))),
                                    $filterModel->getAttributeLabel('date')
                                ])
                            ],
                        ],
                        'class' => 'filter filter_when',
                    ]
                ])
            ]);
            echo Html::endTag('div');

            foreach ($filters as $groupTitle => $questionGroup) {
                echo Html::beginTag('div', ['class' => 'group']);
                    echo Html::beginTag('div', ['class' => 'group-title']);
                echo $groupTitle;
                    echo Html::endTag('div');
                    echo Form::widget([
                'form' => $form,
                'model' => $filterModel,
                'columns' => 2,
                "attributes" => \iter\toArrayWithKeys($questionGroup)
                    ]);
                echo Html::endTag('div');
            }
            echo Html::endTag('div');
            echo Html::beginTag('div', ['class' => 'actions']);
                echo Html::submitButton(\Yii::t('app', 'Apply'), ['class' => 'btn btn-primary']);
                echo Html::a(\Yii::t('app', 'Clear'), [
                'project/view',
                'id' => $project->id,
                'page_id' => \Yii::$app->request->getQueryParam('page_id'),
                'parent_id' => \Yii::$app->request->getQueryParam('parent_id')
                ], ['class' => 'btn btn-clear']);
                echo Html::endTag('div');
                $form->end();
