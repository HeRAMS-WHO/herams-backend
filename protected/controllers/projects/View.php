<?php


namespace prime\controllers\projects;


use prime\models\ar\Tool;
use prime\models\forms\ResponseFilter;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use SamIT\LimeSurvey\JsonRpc\Client;
use yii\base\Action;

class View extends Action
{

    public function run(
        Client $limeSurvey,
        int $id)
    {
        $this->controller->layout = 'css3-grid';
        $tool = Tool::findOne(['id' => $id]);
        $filter = new ResponseFilter();

        $elements = [];

        \Yii::beginProfile('getResponses');
        $responses = $limeSurvey->getResponses($tool->base_survey_eid);
        \Yii::endProfile('getResponses');

        /** @var  $filtered */
        $filtered = $filter->filter($responses);

        $survey = $limeSurvey->getSurvey($tool->base_survey_eid);

        $elements[] = [
            'type' => 'map',
            'data' => $this->getMapDataSet($filtered)
        ];

        foreach(['HFINF1', 'HFINF3', 'HFACC1'] as $code) {
            $question = $this->findQuestionByCode($survey, $code);
            if (isset($question)) {
                $elements[] = [
                    'type' => 'pie',
                    'question' => $question,
                    'data' => $this->getPieDataSet($question, $filtered)
                ];
            }
        }
        return $this->controller->render('view', [
            'elements' => $elements,
            'types' => $this->getTypes($survey, $responses)
        ]);
    }

    private function getTypes(SurveyInterface $survey): array {
        $question = $this->findQuestionByCode($survey, 'HF2');
        $answers = $question->getAnswers();
        assert(count($answers) > 0);

        $map = [];
        foreach($answers as $answer) {
            $map[$answer->getCode()] = trim(explode(':', $answer->getText())[0]);
        }
        return $map;
    }

    private function findQuestionByCode(SurveyInterface $survey, string $text): ?QuestionInterface
    {
        foreach($survey->getGroups() as $group) {
            foreach($group->getQuestions() as $question) {
                if ($question->getTitle() === $text) {
                    return $question;
                }

            }
        }
    }

    /**
     * @param QuestionInterface $question
     * @param \SamIT\LimeSurvey\Interfaces\ResponseInterface[] $responses
     * @return array
     */
    private function getPieDataSet(
        QuestionInterface $question,
        array $responses
    ): array {
        // Check question type.

        assert($question->getDimensions() === 0);
        $answers = $question->getAnswers();
        assert(count($answers) > 0);

        $map = [];
        foreach($answers as $answer) {
            $map[$answer->getCode()] = trim(explode(':', $answer->getText())[0]);
        }

        ksort($map);
        $map[''] = 'No answer given';
        $counts = [];

        foreach($map as $k => $v) {
            $counts[$v] = 0;
        }

        foreach($responses as $response) {
            $value = $response->getData()[$question->getTitle()] ?? '';
            $counts[$map[$value]]++;
        }


        return $counts;
    }

    private function getMapDataSet(array $responses)
    {
        $collections = [];
        /** @var \SamIT\LimeSurvey\Interfaces\ResponseInterface $response */
        foreach($responses as $response) {
            $data = $response->getData();
            $type = $data['HF2'];

            if (!isset($collections[$type])) {
                $collections[$data['HF2']] = [
                    "type" => "FeatureCollection",
                    'features' => [],
                    "title" => $type,
                ];
            }

            $point = [
                "type" => "Feature",
                "geometry" => [
                    "type" => "Point",
                    "coordinates" => [
                        (float) $data['GPS[SQ001]'],
                        (float) $data['GPS[SQ002]']
                    ],
                ],
                "properties" => [
                    'title' => $data['HF1'],
                ]

//                'subtitle' => '',
//                'items' => [
//                    'ownership',
//                    'building damage',
//                    'functionality'
//                ]
            ];
            $collections[$type]['features'][] = $point;
        }
        uksort($collections, function($a, $b) {
            if ($a === "" || $a === "-oth-") {
                return 1;
            } elseif ($b === "" || $b === "-oth-") {
                return -1;
            }
            return $a <=> $b;
        });
        return array_values($collections);
    }
}