<?php

declare(strict_types=1);

namespace herams\api\controllers\facility;

use Collecthor\DataInterfaces\VariableInterface;
use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\surveyResponse\SurveyResponseRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\values\FacilityId;
use yii\base\Action;
use function iter\filter;

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

        $variableSet = $surveyRepository->retrieveSimpleVariableSet($projectRepository->retrieveDataSurveyId($projectId));

        $variables = [
            ...filter(fn (VariableInterface $variable) => $variable->getRawConfigurationValue('showInResponseList') !== null, $variableSet->getVariables()),
        ];
        usort($variables, fn (VariableInterface $a, VariableInterface $b): int => $a->getRawConfigurationValue('showInResponseList') <=> $b->getRawConfigurationValue('showInResponseList'));

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
