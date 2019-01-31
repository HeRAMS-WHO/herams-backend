<?php


namespace prime\traits;


use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;

trait SurveyHelper
{
    /** @var SurveyInterface */
    public $survey;
    /** @var string */
    public $code;

    private function findQuestionByCode(string $text): QuestionInterface
    {
        $survey = $this->survey;
        foreach($survey->getGroups() as $group) {
            foreach($group->getQuestions() as $question) {
                if ($question->getTitle() === $text) {
                    return $question;
                }

            }
        }
        throw new \InvalidArgumentException("Question code $text not found");
    }

    private function getAnswers(string $code = null)
    {
        $code = $code ?? $this->code;
        $question = $this->findQuestionByCode($code);
        $answers = $question->getAnswers() ?? $question->getQuestions(0)[0]->getAnswers();

        assert(count($answers) > 0);
        if (!is_array($answers)) {
            var_dump($answers); die();
        }
        $map = [];
        foreach($answers as $answer) {
            $map[$answer->getCode()] = trim(explode(':', $answer->getText())[0]);
        }

        ksort($map);
        $map[''] = 'No answer given';
        return $map;
    }

    protected function getTitle(): string
    {
        try {
            $question = $this->findQuestionByCode($this->code);
            return trim(explode(':', $question->getText())[0], "\n:");
        } catch (\InvalidArgumentException $e) {
            return ucfirst($this->code);
        }
    }
}