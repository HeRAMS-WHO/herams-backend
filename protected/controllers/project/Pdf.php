<?php

namespace prime\controllers\project;

use prime\exceptions\SurveyDoesNotExist;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\forms\ResponseFilter;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\ServerErrorHttpException;
use yii\web\User;

class Pdf extends Action
{


    public function run(
        Request $request,
        User $user,
        int $id,
        int $page_id = null,
        int $parent_id = null,
        string $filter = null
    ) {
        $this->controller->layout = 'print';
        $project = Project::findOne(['id'  => $id]);
        if (!isset($project)) {
            throw new NotFoundHttpException();
        }
        if (!$user->can(Permission::PERMISSION_READ, $project)) {
            throw new ForbiddenHttpException();
        }
        try {
            $survey = $project->getSurvey();
        } catch (SurveyDoesNotExist $e) {
            throw new ServerErrorHttpException($e->getMessage());
        }

        $responses = $project->getResponses();

        \Yii::beginProfile('ResponseFilterinit');

        $filterModel = new ResponseFilter($survey, $project->getMap());
        if (!empty($filter)) {
            $filterModel->fromQueryParam($filter);
        }
        $filterModel->load($request->queryParams);
        \Yii::endProfile('ResponseFilterinit');

        /** @var  $filtered */


        $filtered = $filterModel->filterQuery($responses)->all();

        return $this->controller->render('print', [
            'types' => $this->getTypes($survey, $project),
            'data' => $filtered,
            'filterModel' => $filterModel,
            'project' => $project,
            'survey' => $survey
        ]);
    }

    private function getTypes(SurveyInterface $survey, Project $project): array
    {
        \Yii::beginProfile(__FUNCTION__);
        $question = $this->findQuestionByCode($survey, $project->getMap()->getType());

        if (!isset($question)) {
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
