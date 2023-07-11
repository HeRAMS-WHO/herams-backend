<?php

declare(strict_types=1);

namespace herams\api\controllers\workspace;

use herams\api\domain\workspace\UpdateWorkspace;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\helpers\ModelHydrator;
use herams\common\values\WorkspaceId;
use yii\base\Action;
use yii\web\Request;
use yii\web\Response;
use yii\helpers\Json;
final class Update extends Action
{
    public function run(
        Request $request,
        WorkspaceRepository $workspaceRepository,
        Response $response,
        int $id
    ) {
        $workspaceId = new WorkspaceId($id);
        $titles = ['title' => $request->bodyParams['data']['title']];
        $workspaceRepository->updateTitles($workspaceId, $titles);
        $response->setStatusCode(200);
        return $response;
    }
}
