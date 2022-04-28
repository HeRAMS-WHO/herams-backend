<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers\project;

use Collecthor\DataInterfaces\ClosedVariableInterface;
use Collecthor\DataInterfaces\ValueOptionInterface;
use prime\interfaces\HeramsVariableSetRepositoryInterface;
use prime\repositories\FacilityRepository;
use prime\repositories\ProjectRepository;
use prime\values\ProjectId;
use yii\base\Action;
use yii\web\User;

class LatestData extends Action
{
    public function run(
        ProjectRepository $projectRepository,
        FacilityRepository $facilityRepository, int $id): array
    {
        $projectId = new ProjectId($id);

        // This does the permission check as well; is this needed?
        $projectForExport = $projectRepository->retrieveForExport($projectId);

        $data = [];
        foreach($facilityRepository->searchInProject($projectId) as $facility) {
            $data[] = [
                'admin_data' => $facility->admin_data,
                'data' => $facility->data
            ];
            if (count($data) > 10) {
                break;
            }
        }

        return $data;
    }
}
