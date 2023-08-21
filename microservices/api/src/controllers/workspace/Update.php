<?php

declare(strict_types=1);

namespace herams\api\controllers\workspace;

use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\values\WorkspaceId;
use yii\base\Action;
use yii\web\Request;
use yii\web\Response;

final class Update extends Action
{
    public function run(
        Request $request,
        WorkspaceRepository $workspaceRepository,
        Response $response,
        int $id
    ) {
        $workspaceId = new WorkspaceId($id);
        $titles = [
            'title' => $request->bodyParams['data']['title'],
        ];
        $workspaceRepository->updateTitles($workspaceId, $titles);
        $response->setStatusCode(200);
        return $response;
    }
}
