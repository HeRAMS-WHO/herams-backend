<?php

declare(strict_types=1);

namespace herams\api\controllers\userRole;

use herams\common\domain\project\ProjectRepository;
use herams\common\domain\userRole\UserRoleRequest;
use herams\common\helpers\CommonFieldsInTables;
use herams\common\helpers\ModelHydrator;
use yii\base\Action;
use yii\web\Request;
use yii\web\Response;

final class Create extends Action
{
    public function run(
        ProjectRepository $projectRepository,
        Request $request,
        Response $response,
        ModelHydrator $modelHydrator,
    ) {
        $data = $request->bodyParams;
        $target = $data['scope'];
        $response->data = $data;
        foreach ($data['users'] as $userId) {
            foreach ($data['roles'] as $roleId) {
                if ($target === 'project') {
                    $jsonDictionary = [
                        'userId'   => $userId,
                        'roleId'   => $roleId,
                        'target'   => $target,
                        'targetId' => $data['project_id'],
                        ...CommonFieldsInTables::forCreatingHydratation()
                    ];
                    $requestModel = new UserRoleRequest();
                    $modelHydrator->hydrateFromJsonDictionary(
                        $requestModel,
                        $jsonDictionary
                    );
                    return $requestModel;
                }

                if ($target === 'workspace') {
                    foreach ($data['workspace'] as $workspaceId) {
                        $jsonDictionary = [
                            'user_id'   => $userId,
                            'role_id'   => $roleId,
                            'target'    => $target,
                            'target_id' => $workspaceId,
                            ...CommonFieldsInTables::forCreatingHydratation()
                        ];
                    }
                }
            }
        }

        return $response;
    }
}
