<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers\facility;

use Collecthor\DataInterfaces\VariableInterface;
use prime\repositories\FacilityRepository;
use prime\repositories\ProjectRepository;
use prime\repositories\SurveyRepository;
use prime\repositories\SurveyResponseRepository;
use prime\repositories\WorkspaceRepository;
use prime\values\FacilityId;
use yii\base\Action;
use function iter\filter;
use function iter\toArray;

final class DataResponses extends Action
{
    public function run(
        FacilityRepository $facilityRepository,
        WorkspaceRepository $workspaceRepository,
        ProjectRepository $projectRepository,
        SurveyRepository $surveyRepository,
        SurveyResponseRepository $surveyResponseRepository,
        int $id
    ) {
        $facilityId = new FacilityId($id);

        $workspaceId = $facilityRepository->getWorkspaceId($facilityId);
        $projectId = $workspaceRepository->getProjectId($workspaceId);

        $variableSet = $surveyRepository->retrieveVariableSet($projectRepository->retrieveAdminSurveyId($projectId), $projectRepository->retrieveDataSurveyId($projectId));

        $variables = toArray(filter(fn (VariableInterface $variable) => $variable->getRawConfigurationValue('showInResponseList') !== null, $variableSet->getVariables()));
        usort($variables, fn (VariableInterface $a, VariableInterface $b) => $a->getRawConfigurationValue('showInResponseList') <=> $b->getRawConfigurationValue('showInResponseList'));

        $data = [];
        foreach ($surveyResponseRepository->retrieveDataInFacility($facilityId) as $facility) {
            $row = [
                'id' => $facility->id + random_int(1, 1000),
            ];
            /** @var VariableInterface $variable */
            foreach ($variables as $variable) {
                $row[$variable->getName()] = $variable->getDisplayValue(
                    $facility,
                    \Yii::$app->language
                )->getRawValue();
            }
            $data[] = $row;
        }
        return $data;
    }
}
