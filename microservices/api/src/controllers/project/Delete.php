<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use yii\base\Action;
use herams\common\values\ProjectId;
use herams\common\values\WorkspaceId;
use herams\common\values\FacilityId;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\surveyResponse\SurveyResponseRepository;
use herams\common\domain\facility\Facility;
use herams\common\models\SurveyResponse;
use herams\common\models\Workspace;
use herams\common\models\Project;



class Delete extends Action
{
    public function run(
        WorkspaceRepository $workspaceRepository,
        FacilityRepository $facilityRepository,
        SurveyResponseRepository $surveyResponseRepository,
        int $id
    ) {
        $projectId = new ProjectId($id);

        $workspaces = $workspaceRepository->retrieveForProject($projectId);
        if($workspaces){
            foreach($workspaces as $workspace){
                $workspaceId = new WorkspaceId($workspace->id);
                if($workspaceId){
                    $facilities = $facilityRepository->retrieveForWorkspace($workspaceId);
                    if($facilities){
                        foreach($facilities as $facility){
                            SurveyResponse::deleteAll(['facility_id' => $facility->id]);
                        }
                        Facility::deleteAll(['workspace_id' => $workspace->id]);
                    }
                }
                Workspace::deleteAll(['project_id' => $id]);
    
            }

        }

        $project = Project::findOne([
            'id' => $id,
        ]);  
        $project->delete();

        return true;
    }
}
