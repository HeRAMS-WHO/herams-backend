<?php

/** @var QuestionInterface $question */

use SamIT\LimeSurvey\Interfaces\AnswerInterface;
use SamIT\LimeSurvey\Interfaces\QuestionInterface as QuestionInterface;

// Question is multiple choice or ranking.
// Assume answers are all the same.
$answers = $question->getQuestions(0)[0]->getAnswers();
if (empty($answers)) {
    return;
}


foreach($question->getQuestions(0) as $subQuestion) {
    if (($answers = $subQuestion->getAnswers()) !== null) {
        $items = \yii\helpers\ArrayHelper::map($answers, \iter\func\method('getCode'),
            static function(AnswerInterface $answer) {
                return strtok( $answer->getText(), ':(');
            }
        );
      //  $renderFilter($subQuestion, $group, $filterModel, $items);
    }
}