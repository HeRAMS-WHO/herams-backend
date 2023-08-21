<?php

declare(strict_types=1);

namespace herams\api\controllers\workspace;

use herams\common\domain\facility\Facility;
use herams\common\domain\facility\FacilityRepository;
use herams\common\models\SurveyResponse;
use herams\common\models\Workspace;
use herams\common\values\WorkspaceId;
use yii\base\Action;

class DeleteWorkspace extends Action
{
    public function run(
        FacilityRepository $facilityRepository,
        int $id
    ) {
        $workspaceId = new WorkspaceId($id);

        $facilities = Facility::find()->where([
            'workspace_id' => $id,
        ])->all();
        ;
        if ($facilities) {
            foreach ($facilities as $facility) {
                //SurveyResponse::deleteAll(['facility_id' => $facility->id]);
                $surveyResponseList = SurveyResponse::find()->where([
                    'facility_id' => $facility->id,
                ])->all();
                foreach ($surveyResponseList as $surveyResponse) {
                    $surveyResponse->status = 'Deleted';
                    $surveyResponse->update();
                }
                $facility->status = 'Deleted';
                $facility->update();
            }
            //Facility::deleteAll(['workspace_id' => $id]);
        }
        //$workspace = Workspace::findOne($id)->delete();
        $workspace = Workspace::findOne($id);
        $workspace->status = 'Deleted';
        $workspace->update();
        return true;
    }
}
