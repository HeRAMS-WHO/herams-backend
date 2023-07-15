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
        int $id
    ) {
        $facilityId = new FacilityId($id);
        $workspaceId = $facilityRepository->getWorkspaceId($facilityId);
        $projectId = $workspaceRepository->getProjectId($workspaceId);

        $variableSet = $surveyRepository->retrieveSimpleVariableSet($projectRepository->retrieveAdminSurveyId($projectId));
        /**
         * @var list<VariableInterface>
         */
        $variables = [
            ...filter(fn (VariableInterface $variable) => $variable->getRawConfigurationValue('showInResponseList') !== null, $variableSet->getVariables()),
        ];
        usort($variables, fn (VariableInterface $a, VariableInterface $b) => $a->getRawConfigurationValue('showInResponseList') <=> $b->getRawConfigurationValue('showInResponseList'));

        $data = [];
        foreach ($surveyResponseRepository->retrieveAdminDataInFacility($facilityId) as $surveyResponse) {
            $row = [
                'id' => $surveyResponse->id,
                'response_type' => $surveyResponse->response_type ?? 'admin',
                'status' => $surveyResponse->status ?? '',
                'date_of_update' => $surveyResponse->date_of_update,
                //'raw' => $facility->allData()
                'last_modified_by' => $surveyResponse->updatedUser?->name,
                'facilityId'=> $facilityId,
                'last_modified_date'=> $surveyResponse->last_modified_date,
                'created_at' => $surveyResponse->created_at,
                'created_by' => $surveyResponse->user->name,
                'action' => $surveyResponse->id,
            ];
            foreach ($variables as $variable) {
                $row[$variable->getName()] = $variable->getDisplayValue(
                    $surveyResponse,
                    substr(\Yii::$app->language, 0, 2)
                )->getRawValue();

            }
            $data[] = $row;
        }
        return $data;
    }
}
