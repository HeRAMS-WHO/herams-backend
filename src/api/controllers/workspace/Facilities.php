<?php

declare(strict_types=1);

namespace herams\api\controllers\workspace;

use Collecthor\DataInterfaces\VariableInterface;
use prime\repositories\FacilityRepository;
use prime\repositories\ProjectRepository;
use prime\repositories\SurveyRepository;
use prime\repositories\WorkspaceRepository;
use prime\values\WorkspaceId;
use yii\base\Action;
use function iter\filter;
use function iter\toArray;

final class Facilities extends Action
{
    public function run(
        FacilityRepository $facilityRepository,
        WorkspaceRepository $workspaceRepository,
        ProjectRepository $projectRepository,
        SurveyRepository $surveyRepository,
        int $id
    ) {
        $workspaceId = new WorkspaceId($id);
        $projectId = $workspaceRepository->getProjectId($workspaceId);

        $adminVariables = new \SplObjectStorage();
        $variables = [...$surveyRepository->retrieveSimpleVariableSet($projectRepository->retrieveDataSurveyId($projectId))->getVariables()];
        foreach($surveyRepository->retrieveSimpleVariableSet($projectRepository->retrieveAdminSurveyId($projectId))->getVariables() as $variable) {
            $adminVariables->attach($variable);
            $variables[] = $variable;
        }
        $sorter = static fn (VariableInterface $a, VariableInterface $b) => $a->getRawConfigurationValue('showInFacilityList') <=> $b->getRawConfigurationValue('showInFacilityList');

        // Sort them separately
        usort($variables, $sorter);

        $data = [];

        foreach ($facilityRepository->retrieveForWorkspace($workspaceId) as $model) {
            $row = [
                'id' => $model->id,
            ];
            /** @var VariableInterface $variable */
            foreach ($variables as $variable) {
                $row[$variable->getName()] = $variable->getDisplayValue(
                    $adminVariables->contains($variable) ? $model->getAdminRecord() : $model->getDataRecord(),
                    \Yii::$app->language
                )->getRawValue();
            }
            if (empty($row['name'])) {
                $row['name'] = 'no name';
            }
            $data[] = $row;
        }
        return $data;
    }
}
