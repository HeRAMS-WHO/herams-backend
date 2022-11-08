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

final class AdminResponses extends Action
{
    public function run(
        FacilityRepository $facilityRepository,
        WorkspaceRepository $workspaceRepository,
        ProjectRepository $projectRepository,
        SurveyRepository $surveyRepository,
        SurveyResponseRepository $surveyResponseRepository,
        int $id,
        string $language = null
    ) {
        $facilityId = new FacilityId($id);

        $workspaceId = $facilityRepository->getWorkspaceId($facilityId);
        $projectId = $workspaceRepository->getProjectId($workspaceId);

        $variableSet = $surveyRepository->retrieveSimpleVariableSet($projectRepository->retrieveAdminSurveyId($projectId));

        $variables = [...filter(fn (VariableInterface $variable) => $variable->getRawConfigurationValue('showInResponseList') !== null, $variableSet->getVariables())];
        usort($variables, fn (VariableInterface $a, VariableInterface $b) => $a->getRawConfigurationValue('showInResponseList') <=> $b->getRawConfigurationValue('showInResponseList'));

        $data = [];
        foreach ($surveyResponseRepository->retrieveAdminDataInFacility($facilityId) as $facility) {
            $row = [
                'id' => $facility->id + random_int(1, 1000),
                'raw' => $facility->allData()
            ];
            /** @var VariableInterface $variable */
            foreach ($variables as $variable) {
                $row[$variable->getName()] = $variable->getDisplayValue(
                    $facility,
                    isset($language) ? substr($language, 0, 2) : \Yii::$app->language
                )->getRawValue();
            }
            $data[] = $row;
        }
        return $data;
    }
}
