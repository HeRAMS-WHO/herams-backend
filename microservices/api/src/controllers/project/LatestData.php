<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\values\ProjectId;
use yii\base\Action;

class LatestData extends Action
{
    public function run(
        ProjectRepository $projectRepository,
        FacilityRepository $facilityRepository,
        int $id
    ): array {
        $projectId = new ProjectId($id);

        // This does the permission check as well; is this needed?
        $projectForExport = $projectRepository->retrieveForExport($projectId);

        $data = [];
        foreach ($facilityRepository->searchInProject($projectId) as $facility) {
            $data[] = [
                'admin_data' => $facility->admin_data,
                'data' => $facility->data,
            ];
            if (count($data) > 10) {
                break;
            }
        }

        return $data;
    }
}
