<?php

declare(strict_types=1);

namespace prime\controllers\project;

use herams\common\domain\facility\Facility;
use herams\common\domain\facility\FacilityRepository;
use herams\common\interfaces\HeramsVariableSetRepositoryInterface;
use herams\common\interfaces\PageInterface;
use herams\common\models\Page;
use herams\common\models\PermissionOld;
use herams\common\values\FacilityId;
use herams\common\values\ProjectId;
use prime\components\BreadcrumbService;
use prime\components\Controller;
use prime\helpers\CombinedHeramsFacilityRecord;
use prime\interfaces\HeramsFacilityRecordInterface;
use prime\models\ar\read\Project;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\User;
use function iter\flatten;
use function iter\map;
use function iter\toArray;

class ViewForSurveyJs extends Action
{
    public function run(
        BreadcrumbService $breadcrumbService,
        FacilityRepository $facilityRepository,
        HeramsVariableSetRepositoryInterface $heramsVariableSetRepository,
        User $user,
        int $id,
        int $page_id = null,
        int $parent_id = null,
        string $filter = null
    ) {
        $this->controller->layout = Controller::LAYOUT_CSS3_GRID;
        $project = Project::find()
            ->andWhere([
                'id' => $id,
            ])
            ->with('mainPages')
            ->one();
        if (! isset($project)) {
            throw new NotFoundHttpException();
        }

        if (! $user->can(PermissionOld::PERMISSION_READ, $project)) {
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

        $projectId = ProjectId::fromProject($project);
        $facilities = $facilityRepository->searchInProject($projectId);

        \Yii::beginProfile('ResponseFilterinit');

        /** @var \prime\components\View $view */
        $view = $this->controller->view;
        $stack = [];
        $parent = $page;
        while (null !== ($parent = $parent->getParentPage())) {
            $stack[] = $parent;
        }

        $view->getBreadcrumbCollection()->add(...toArray($breadcrumbService->retrieveForProject($projectId)));

        $data = toArray(flatten(map(static fn (Facility $facility): HeramsFacilityRecordInterface => new CombinedHeramsFacilityRecord($facility->getAdminRecord(), $facility->getDataRecord(), FacilityId::fromFacility($facility)), $facilities)));
        return $this->controller->render('view-for-survey-js', [
            'data' => $data,
            'project' => $project,
            'page' => $page,
            'variables' => $variableSet,
        ]);
    }
}
