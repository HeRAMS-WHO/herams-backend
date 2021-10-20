<?php

declare(strict_types=1);

namespace prime\controllers\project;

use prime\components\Controller;
use prime\exceptions\SurveyDoesNotExist;
use prime\interfaces\PageInterface;
use prime\models\ar\Page;
use prime\models\ar\Permission;
use prime\models\ar\read\Project;
use prime\models\forms\ResponseFilter;
use prime\objects\Breadcrumb;
use SamIT\abac\interfaces\Resolver;
use SamIT\abac\repositories\PreloadingSourceRepository;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\ServerErrorHttpException;
use yii\web\User;

class View extends Action
{
    public function run(
        Resolver $abacResolver,
        PreloadingSourceRepository $preloadingSourceRepository,
        Request $request,
        User $user,
        int $id,
        int $page_id = null,
        int $parent_id = null,
        string $filter = null
    ) {
        $preloadingSourceRepository->preloadSource($abacResolver->fromSubject($user->identity));
        $this->controller->layout = Controller::LAYOUT_CSS3_GRID;
        $project = Project::find()
            ->andWhere(['id'  => $id])
            ->with('mainPages')
            ->one();
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
            if (!isset($page) || $page->project_id !== $project->id) {
                throw new NotFoundHttpException();
            }
        } elseif (!empty($project->mainPages)) {
            $page = $project->mainPages[0];
        } else {
            throw new NotFoundHttpException('No reporting has been set up for this project');
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

        /** @var \prime\components\View $view */
        $view = $this->controller->view;
        $stack = [];
        $parent = $page;
        while (null !== ($parent = $parent->getParentPage())) {
            $stack[] = $parent;
        }

        $view->getBreadcrumbCollection()->add((new Breadcrumb())->setLabel($project->title)->setUrl(['project/view', 'id' => $project->id]));
        while (!empty($stack)) {
            /** @var PageInterface $p */
            $p = array_pop($stack);
            $view->getBreadcrumbCollection()->add((new Breadcrumb())->setLabel($p->getTitle()));
        }
        $view->getBreadcrumbCollection()->add((new Breadcrumb())->setLabel($page->getTitle()));


        return $this->controller->render('view', [
            'types' => $this->getTypes($survey, $project),
            'data' => $filtered,
            'filterModel' => $filterModel,
            'project' => $project,
            'page' => $page,
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
            $map[$answer->getCode()] = trim(preg_split('/:\(/', $answer->getText(), PREG_SPLIT_NO_EMPTY)[0]);
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
