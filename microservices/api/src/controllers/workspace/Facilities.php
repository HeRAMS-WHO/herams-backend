<?php

declare(strict_types=1);

namespace herams\api\controllers\workspace;

use Collecthor\DataInterfaces\VariableInterface;
use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\values\WorkspaceId;
use yii\base\Action;

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
        foreach ($surveyRepository->retrieveSimpleVariableSet($projectRepository->retrieveAdminSurveyId($projectId))->getVariables() as $variable) {
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
                'date_of_update' => $model->date_of_update,
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
        // sort($data);
        // print_r($data); exit;

        return $data;
    }
}
