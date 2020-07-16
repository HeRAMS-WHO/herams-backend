<?php

/** @var \prime\models\forms\ResponseFilter $filterModel */
/** @var \prime\models\ar\Project $project */

use app\components\Form;
use kartik\select2\Select2;
use app\components\ActiveForm;
use SamIT\LimeSurvey\Interfaces\AnswerInterface;
use SamIT\LimeSurvey\Interfaces\GroupInterface as GroupInterface;
use yii\helpers\Html;
use yii\helpers\Json as Json;
use function iter\chain;

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

    foreach ($groups as $group) {
        foreach ($group->getQuestions() as $question) {
            if (($answers = $question->getAnswers()) !== null
                && $question->getDimensions() === 0) {
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
    echo Html::beginTag('div', ['class' => 'filterlist']);
    
    echo Html::beginTag('div', ['class' => 'group']);
        echo Html::beginTag('div', ['class' => 'group-title']);
            echo "Date";
        echo Html::endTag('div');
        echo Html::beginTag('div', ['class' => 'row']);
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
                        'class' => 'filter filter_when',
                    ]
                ])
            ]);
            echo Html::endTag('div');
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
            echo Html::submitButton(\Yii::t('app', 'Apply'), ['class' => 'btn btn-primary']);
            echo Html::a(\Yii::t('app', 'Clear'), [
            'project/view',
            'id' => $project->id,
            'page_id' => \Yii::$app->request->getQueryParam('page_id'),
            'parent_id' => \Yii::$app->request->getQueryParam('parent_id')
            ], ['class' => 'btn btn-clear']);
            $form->end();
