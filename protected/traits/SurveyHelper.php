<?php


namespace prime\traits;


use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;

trait SurveyHelper
{
    /** @var SurveyInterface */
    public $survey;

    private function findQuestionByCode(string $code): QuestionInterface
    {
        $survey = $this->survey;
        foreach ($survey->getGroups() as $group) {
            foreach ($group->getQuestions() as $question) {
                if ($question->getTitle() === $code) {
                    return $question;
                }

            }
        }
        throw new \InvalidArgumentException("Question code $code not found");
    }

    private function getAnswers(string $code)
    {
        $question = $this->findQuestionByCode($code);

        $answers = $question->getAnswers() ??
            (
                $question->getDimensions() > 0
                ? $question->getQuestions(0)[0]->getAnswers() ?? []
                : []
            ) ?? [] ;

        assert(count($answers) > 0);
        $map = [];
        foreach ($answers as $answer) {
            $map[$answer->getCode()] = trim(explode(':', $answer->getText())[0]);
        }
        ksort($map);
        if (!isset($map[""])) {
            $map[""] = \Yii::t('app', 'Unknown');
        }
        return $map;
    }

    protected function getTitleFromCode(string $code): string
    {
        $codeOptions['availability'] = \Yii::t('app', 'Service availability');
        $codeOptions['availability'] = \Yii::t('app', 'The aggregate availability of services per HF');
        $codeOptions['fullyAvailable'] = \Yii::t('app', 'Is the service fully available');
        $codeOptions['causes'] = \Yii::t('app','Causes of unavailability');
        if (isset($codeOptions[$code])) {
            return $codeOptions[$code];
        }
        try {
            $question = $this->findQuestionByCode($code);
            return $this->normalizeQuestionText($question->getText());

        } catch (\InvalidArgumentException $e) {
            return $this->normalizeQuestionText($code);
        }
    }

    protected function normalizeQuestionText(string $text): string
    {
        return trim(explode(':', $text)[0], "\n:");
    }
}