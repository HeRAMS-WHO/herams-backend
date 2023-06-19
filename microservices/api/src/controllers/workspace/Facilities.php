<?php

declare(strict_types=1);

namespace herams\api\controllers\workspace;

use Collecthor\DataInterfaces\VariableInterface;
use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\values\WorkspaceId;
use Yii;
use yii\base\Action;
use yii\helpers\Html;
use yii\helpers\VarDumper;

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
        $facilities = $facilityRepository->getByWorkspace($workspaceId);
        foreach($facilities as &$facility){
            $facility['admin_data'] = json_decode($facility['admin_data']);
        }
        foreach ($facilityRepository->retrieveByWorkspaceId($workspaceId) as $model) {

            $row = [
                'id' => $model->id
            ];
            /** @var VariableInterface $variable */
            foreach ($variables as $variable) {
                $row[$variable->getName()] = $variable->getDisplayValue(
                    $adminVariables->contains($variable) ? $model->getAdminRecord() : $model->getDataRecord(),
                    \Yii::$app->language
                )->getRawValue();
            }
            $row['date_of_update'] = $model->date_of_update;
            if (empty($row['name'])) {
                $row['name'] = 'no name';
            }
            foreach($facilities as $facility){
                if ($facility['id'] === $model->id){
                    try {
                        $row['LAST_DATE_OF_UPDATE'] = $facility['latestSurveyResponse']['date_of_update'];
                    }
                    catch (Error $error){
                        $row['LAST_DATE_OF_UPDATE'] = '';
                    }
                    catch (\Exception $exeption){
                        $row['LAST_DATE_OF_UPDATE'] = '';
                    }
                }
            }
            $data[] = $row;
        }
        return $data;
    }
}
