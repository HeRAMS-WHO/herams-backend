<?php

declare(strict_types=1);

namespace prime\controllers\project;

use prime\components\Controller;
use prime\helpers\CombinedHeramsFacilityRecord;
use prime\interfaces\HeramsFacilityRecordInterface;
use prime\interfaces\HeramsVariableSetRepositoryInterface;
use prime\interfaces\PageInterface;
use prime\models\ar\Facility;
use prime\models\ar\Page;
use prime\models\ar\Permission;
use prime\models\ar\read\Project;
use prime\objects\Breadcrumb;
use prime\repositories\FacilityRepository;
use prime\repositories\SurveyRepository;
use prime\values\FacilityId;
use prime\values\ProjectId;
use prime\values\SurveyId;
use SamIT\abac\interfaces\Resolver;
use SamIT\abac\repositories\PreloadingSourceRepository;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;
use function iter\flatten;
use function iter\map;
use function iter\toArray;

class ViewForSurveyJs extends Action
{
    public function run(
        Resolver $abacResolver,
        PreloadingSourceRepository $preloadingSourceRepository,
        SurveyRepository $surveyRepository,
        FacilityRepository $facilityRepository,
        HeramsVariableSetRepositoryInterface $heramsVariableSetRepository,
        Request $request,
        User $user,
        int $id,
        int $page_id = null,
        int $parent_id = null,
        string $filter = null
    ) {
        $preloadingSourceRepository->preloadSource($abacResolver->fromSubject($user->identity));
        $this->controller->layout = Controller::LAYOUT_CSS3_GRID;
        /** @var \prime\models\ar\surveyjs\Project|null $project */
        $project = Project::find()
            ->andWhere([
                'id' => $id,
            ])
            ->with('mainPages')
            ->one();
        if (! isset($project)) {
            throw new NotFoundHttpException();
        }

        if (! $user->can(Permission::PERMISSION_READ, $project)) {
            throw new ForbiddenHttpException();
        }

        $variableSet = $heramsVariableSetRepository->retrieveForProject(ProjectId::fromProject($project));

        if (isset($parent_id, $page_id)) {
            /** @var PageInterface $parent */
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
        } elseif (! empty($project->mainPages)) {
            $page = $project->mainPages[0];
        } else {
            throw new NotFoundHttpException('No reporting has been set up for this project');
        }

        $facilities = $facilityRepository->searchInProject(ProjectId::fromProject($project));

        \Yii::beginProfile('ResponseFilterinit');

        /** @var \prime\components\View $view */
        $view = $this->controller->view;
        $stack = [];
        $parent = $page;
        while (null !== ($parent = $parent->getParentPage())) {
            $stack[] = $parent;
        }

        $view->getBreadcrumbCollection()->add((new Breadcrumb())->setLabel($project->title)->setUrl([
            'project/view',
            'id' => $project->id,
        ]));
        while (! empty($stack)) {
            /** @var PageInterface $p */
            $p = array_pop($stack);
            $view->getBreadcrumbCollection()->add((new Breadcrumb())->setLabel($p->getTitle()));
        }
        $view->getBreadcrumbCollection()->add((new Breadcrumb())->setLabel($page->getTitle()));

        $data = toArray(flatten(map(static fn (Facility $facility): HeramsFacilityRecordInterface => new CombinedHeramsFacilityRecord($facility->getAdminRecord(), $facility->getDataRecord(), FacilityId::fromFacility($facility)), $facilities)));
        return $this->controller->render('view-for-survey-js', [
            'data' => $data,
            'project' => $project,
            'page' => $page,
            'variables' => $variableSet,
        ]);
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
            $map[$answer->getCode()] = trim(preg_split('/:\(/', $answer->getText())[0]);
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
