<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use herams\common\domain\facility\Facility;
use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\surveyResponse\SurveyResponseRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\models\Project;
use herams\common\models\SurveyResponse;
use herams\common\models\Workspace;
use herams\common\values\ProjectId;
use herams\common\values\WorkspaceId;
use yii\base\Action;

class Delete extends Action
{
    public function run(
        WorkspaceRepository $workspaceRepository,
        FacilityRepository $facilityRepository,
        SurveyResponseRepository $surveyResponseRepository,
        int $id
    ) {
        $projectId = new ProjectId($id);
        $empty = false;
        $workspaces = $workspaceRepository->retrieveForProject($projectId);
        if ($workspaces) {
            foreach ($workspaces as $workspace) {
                $workspaceId = new WorkspaceId($workspace->id);
                if ($workspaceId) {
                    $facilities = $facilityRepository->retrieveByWorkspaceId($workspaceId);
                    if ($facilities) {
                        foreach ($facilities as $facility) {
                            $surveyResponse = SurveyResponse::find()->where([
                                'facility_id' => $facility->id,
                            ])->one();
                            //SurveyResponse::deleteAll(['facility_id' => $facility->id]);
                            if ($surveyResponse) {
                                $empty = true;
                            }
                        }
                        $empty = true;
                        //Facility::deleteAll(['workspace_id' => $workspace->id]);
                    }
                }
                $empty = true;
                //Workspace::deleteAll(['project_id' => $id]);
            }
        }
        if ($empty == true) {
            return [
                'empty' => true,
            ];
        }
        $project = Project::findOne([
            'id' => $id,
        ]);
        $project->delete();

        return true;
    }
}
