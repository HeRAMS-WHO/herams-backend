<?php


namespace prime\controllers\project;


use prime\interfaces\PageInterface;
use prime\models\ar\Page;
use prime\models\ar\Project;
use prime\models\forms\ResponseFilter;
use prime\objects\HeramsResponse;
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
        int $page_id = null,
        int $parent_id = null
    ) {
        $this->controller->layout = 'css3-grid';
        $project = Project::findOne(['id' => $id]);
        $survey = $limeSurvey->getSurvey($project->base_survey_eid);

        if (isset($parent_id, $page_id)) {
            /** @var PageInterface $parent */
            $parent = Page::findOne(['id' => $parent_id]);
            foreach ($parent->getChildPages($survey) as $childPage) {
                if ($childPage->getid() === $page_id) {
                    $page = $childPage;
                    break;
                }
            }
            if (!isset($page)) {
                throw new NotFoundHttpException();
            }
        } elseif (isset($page_id)) {
            $page = Page::findOne(['id' => $page_id]);
            if (!isset($page) || $page->tool_id !== $project->id) {
                throw new NotFoundHttpException($page->tool_id);
            }
        } elseif (!empty($project->pages)) {
            $page = $project->pages[0];
        } else {
            throw new NotFoundHttpException('No reporting has been set up for this project');
        }




        \Yii::beginProfile('getResponses');
        $responses = [];
        $map = $project->getMap();
        foreach($project->getResponses() as $response) {
            try {
                $responses[] = new HeramsResponse($response, $map);
            } catch (\InvalidArgumentException $e) {

            }
        }
        \Yii::endProfile('getResponses');


        $filter = new ResponseFilter($responses, $survey);
        $filter->load($request->queryParams);
        $elements = [];


        /** @var  $filtered */
        $filtered = $filter->filter($responses);

        // Check if page uses subjects.
        if (false) {
            \Yii::beginProfile('transpose');
            $finalResponses = [];
            foreach($filtered as $response) {
                foreach($response->getSubjects() as $subject) {
                    $finalResponses[] = $subject;
                }
            }
            \Yii::endProfile('transpose');
        } else {
            $finalResponses = $filtered;
        }


        return $this->controller->render('view', [
            'elements' => $elements,
            'types' => $this->getTypes($survey),
            'data' => $filtered,
            'filterModel' => $filter,
            'project' => $project,
            'page' => $page,
            'survey' => $survey
        ]);
    }

    private function getResponses(Client $limeSurvey, int $surveyId, int $retries = 10)
    {
        $attempts = 0;
        while ($attempts <= $retries)
        {
            try {
                $result = $limeSurvey->getResponses($surveyId);
                return $result;
            } catch (\Throwable $t) {
                if ($attempts === $retries) {
                    throw $t;
                    throw new \Exception('Failed to get responses after ' . $retries . ' attempts', 0, $t);
                }
            }
            $attempts++;
        }

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