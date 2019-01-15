<?php


namespace prime\controllers\projects;


use prime\models\ar\Page;
use prime\models\ar\Tool;
use prime\models\forms\ResponseFilter;
use prime\objects\HeramsResponse;
use prime\widgets\chart\Chart;
use prime\widgets\map\Map;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use SamIT\LimeSurvey\JsonRpc\Client;
use yii\base\Action;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class View extends Action
{


    public function run(
        Client $limeSurvey,
        Request $request,
        int $id,
        int $page_id = null
    ) {
        $this->controller->layout = 'css3-grid';
        $tool = Tool::findOne(['id' => $id]);
        if (isset($page_id)) {
            $page = Page::findOne(['id' => $page_id]);
        } else {
            $page = $tool->pages[0];
        }
        if ($page->tool_id !== $tool->id) {
            throw new NotFoundHttpException($page->tool_id);
        }

        \Yii::beginProfile('getResponses');
        $responses = [];
        $map = $tool->getMap();
        foreach($limeSurvey->getResponses($tool->base_survey_eid) as $response) {
            try {
                $responses[] = new HeramsResponse($response, $map);
            } catch (\InvalidArgumentException $e) {

            }
        }
        \Yii::endProfile('getResponses');

        $survey = $limeSurvey->getSurvey($tool->base_survey_eid);
        $filter = new ResponseFilter($responses, $survey);
        $filter->load($request->queryParams);
        $elements = [];


        /** @var  $filtered */
        $filtered = $filter->filter($responses);
        return $this->controller->render('view', [
            'elements' => $elements,
            'types' => $this->getTypes($survey),
            'data' => $filtered,
            'filterModel' => $filter,
            'project' => $tool,
            'page' => $page,
            'survey' => $survey
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



}