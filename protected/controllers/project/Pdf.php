<?php

namespace prime\controllers\project;

use herams\common\interfaces\AccessCheckInterface;
use herams\common\models\Page;
use herams\common\models\Permission;
use herams\common\models\Project;
use prime\exceptions\SurveyDoesNotExist;
use prime\models\forms\ResponseFilter;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\Action;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\ServerErrorHttpException;

class Pdf extends Action
{
    public function run(
        Request $request,
        AccessCheckInterface $accessCheck,
        int $id,
        int $page_id = null,
        int $parent_id = null,
        string $filter = null
    ) {
        $this->controller->layout = 'print';
        $project = Project::findOne([
            'id' => $id,
        ]);
        $accessCheck->requirePermission($project, Permission::PERMISSION_READ);
        try {
            $survey = $project->getSurvey();
        } catch (SurveyDoesNotExist $e) {
            throw new ServerErrorHttpException($e->getMessage());
        }

        if (isset($parent_id, $page_id)) {
            $parent = Page::findOne([
                'id' => $parent_id,
            ]);
            foreach ($parent->getChildPages() as $childPage) {
                if ($childPage->getid() === $page_id) {
                    $page = $childPage;
                    break;
                }
            }
            if (! isset($page)) {
                throw new NotFoundHttpException();
            }
        } elseif (isset($page_id)) {
            $page = Page::findOne([
                'id' => $page_id,
            ]);
            if (! isset($page) || $page->project_id !== $project->id) {
                throw new NotFoundHttpException();
            }
        }

        $responses = $project->getResponses();

        \Yii::beginProfile('ResponseFilterinit');

        $filterModel = new ResponseFilter($survey, $project->getMap());
        if (! empty($filter)) {
            $filterModel->fromQueryParam($filter);
        }
        $filterModel->load($request->queryParams);
        \Yii::endProfile('ResponseFilterinit');

        /** @var $filtered */

        $filtered = $filterModel->filterQuery($responses)->all();

        $params = [
            'types' => $this->getTypes($survey, $project),
            'data' => $filtered,
            'filterModel' => $filterModel,
            'project' => $project,
            'survey' => $survey,
        ];
        if (isset($page)) {
            $params['page'] = $page;
        }
        return $this->controller->render('print', $params);
    }

    private function getTypes(SurveyInterface $survey, Project $project): array
    {
        \Yii::beginProfile(__FUNCTION__);
        $question = $this->findQuestionByCode($survey, $project->getMap()->getType());

        if (! isset($question)) {
            return [];
        }

        $answers = $question->getAnswers();

        $map = [];
        foreach ($answers as $answer) {
            $map[$answer->getCode()] = trim(strtok($answer->getText(), ':('));
        }

        \Yii::endProfile(__FUNCTION__);
        return $map;
    }

    private function findQuestionByCode(SurveyInterface $survey, string $text): ?QuestionInterface
    {
        foreach ($survey->getGroups() as $group) {
            foreach ($group->getQuestions() as $question) {
                if ($question->getTitle() === $text) {
                    return $question;
                }
            }
        }
        return null;
    }
}
