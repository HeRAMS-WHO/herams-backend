<?php

declare(strict_types=1);

namespace prime\controllers\page;

use prime\components\NotificationService;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Page;
use prime\models\ar\Permission;
use yii\base\Action;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class Update extends Action
{
    public function run(
        Request $request,
        NotificationService $notificationService,
        AccessCheckInterface $accessCheck,
        int $id
    ) {
        $page = Page::findOne([
            'id' => $id,
        ]);
        if (! isset($page)) {
            throw new NotFoundHttpException();
        }

        $accessCheck->requirePermission($page, Permission::PERMISSION_WRITE);

        if ($request->isPut) {
            if ($page->load($request->bodyParams) && $page->save()) {
                $notificationService->success(\Yii::t('app', "Page <strong>{page}</strong> updated", [
                    'page' => $page->title,
                ]));
                return $this->controller->refresh();
            }
        }

        return $this->controller->render('update', [
            'page' => $page,
            'project' => $page->project,
        ]);
    }
}
