<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers\workspace;

use Collecthor\DataInterfaces\VariableInterface;
use prime\components\BreadcrumbService;
use prime\components\Controller;
use prime\models\facility\FacilityForList;
use prime\models\search\FacilitySearch;
use prime\repositories\FacilityRepository;
use prime\repositories\ProjectRepository;
use prime\repositories\SurveyRepository;
use prime\repositories\WorkspaceRepository;
use prime\values\WorkspaceId;
use yii\base\Action;
use yii\web\Request;
use function iter\filter;
use function iter\toArray;

final class Facilities extends Action
{
    public function run(
        Request $request,
        FacilityRepository $facilityRepository,
        WorkspaceRepository $workspaceRepository,
        ProjectRepository $projectRepository,
        SurveyRepository $surveyRepository,
        int $id
    ) {
        $workspaceId = new WorkspaceId($id);
        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $variableSet = $surveyRepository->retrieveVariableSet($projectRepository->retrieveAdminSurveyId($projectId), $projectRepository->retrieveDataSurveyId($projectId));
        $facilitySearch = new FacilitySearch();
//        $facilitySearch->load($request->queryParams);

        $variables = toArray(filter(fn (VariableInterface $variable) => $variable->getRawConfigurationValue('showInFacilityList') !== null, $variableSet->getVariables()));
        usort($variables, fn (VariableInterface $a, VariableInterface $b) => $a->getRawConfigurationValue('showInFacilityList') <=> $b->getRawConfigurationValue('showInFacilityList'));

        $dataProvider = $facilityRepository->searchInWorkspace($workspaceId, $facilitySearch);
        $dataProvider->getPagination()->setPageSize(-1);

        $data = [];
        /** @var FacilityForList $model */
        foreach ($dataProvider->getModels() as $model) {
            $row = [
                'id' => $model->getId(),
            ];
            /** @var VariableInterface $variable */
            foreach ($variables as $variable) {
                $row[$variable->getName()] = $variable->getDisplayValue($model, \Yii::$app->language)->getRawValue();
            }
            $data[] = $row;
        }
        return $data;
    }
}
