<?php


namespace prime\traits;

use prime\interfaces\HeramsResponseInterface;
use prime\objects\HeramsSubject;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;

trait SurveyHelper
{
    public SurveyInterface $survey;

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
        switch ($code) {
            case 'causes':
                return [
                    HeramsSubject::LACK_EQUIPMENT => \Yii::t('app', 'Lack of medical equipment'),
                    HeramsSubject::LACK_FINANCES => \Yii::t('app', 'Lack of finances'),
                    HeramsSubject::LACK_STAFF => \Yii::t('app', 'Lack of health staff'),
                    HeramsSubject::LACK_TRAINING => \Yii::t('app', 'Lack of training of health staff'),
                    HeramsSubject::LACK_SUPPLIES => \Yii::t('app', 'Lack of medical supplies'),
                ];

            case 'availability':
                return [
                    HeramsSubject::FULLY_AVAILABLE => \Yii::t('app', 'Fully available'),
                    HeramsSubject::PARTIALLY_AVAILABLE => \Yii::t('app', 'Partially available'),
                    HeramsSubject::NOT_AVAILABLE => \Yii::t('app', 'Not available'),
                    HeramsSubject::NOT_PROVIDED => \Yii::t('app', 'Not normally provided'),
                    "" => \Yii::t('app', 'Unknown'),
                ];
            case 'fullyAvailable':
                return [
                    0 => 'False',
                    1 => 'True',
                ];
            case 'subjectAvailabilityBucket':
                return [
                    HeramsResponseInterface::BUCKET25 => \Yii::t('app', '< 25%'),
                    HeramsResponseInterface::BUCKET2550 => \Yii::t('app', '25 - 50%'),
                    HeramsResponseInterface::BUCKET5075 => \Yii::t('app', '50 - 75%'),
                    HeramsResponseInterface::BUCKET75100 => \Yii::t('app', '> 75%'),
                ];
            default:
                try {
                    $question = $this->findQuestionByCode($code);
                } catch (\Throwable $t) {
                    return [];
                }
                $answers = $question->getAnswers() ??
                    (
                    $question->getDimensions() > 0
                        ? $question->getQuestions(0)[0]->getAnswers() ?? []
                        : []
                    ) ?? [] ;

                assert(count($answers) > 0);
                $map = [];
                foreach ($answers as $answer) {
                    $code = $answer->getCode() === "" ? HeramsSubject::UNKNOWN_VALUE : $answer->getCode();
                    $map[$code] = trim(strtok($answer->getText(), ':('));
                }
                ksort($map);
                if (!isset($map[HeramsSubject::UNKNOWN_VALUE])) {
                    $map[HeramsSubject::UNKNOWN_VALUE] = \Yii::t('app', 'Unknown');
                }
                return $map;
        }
    }

    protected function getTitleFromCode(string $code): string
    {
        $codeOptions['availability'] = \Yii::t('app', 'Service availability');
        $codeOptions['subjectAvailabilityBucket'] = \Yii::t('app', 'The aggregate availability of services per HF');
        $codeOptions['fullyAvailable'] = \Yii::t('app', 'Is the service fully available');
        $codeOptions['causes'] = \Yii::t('app', 'Causes of unavailability');
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
        $text = strip_tags($text);
        $text = strtok($text, ':(');
        $text = trim($text, "\n:");
        return $text;
    }
}
