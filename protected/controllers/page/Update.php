<?php


namespace prime\controllers\page;

use prime\components\NotificationService;
use prime\models\ar\Page;
use prime\models\ar\Permission;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;

class Update extends Action
{

    public function run(
        Request $request,
        NotificationService $notificationService,
        User $user,
        int $id
    ) {
        $page = Page::findOne(['id' => $id]);
        if (!isset($page)) {
            throw new NotFoundHttpException();
        }

        if (!$user->can(Permission::PERMISSION_WRITE, $page)) {
            throw new ForbiddenHttpException();
        }

        if ($request->isPut) {
            if ($page->load($request->bodyParams) && $page->save()) {
                $notificationService->success(\Yii::t('app', "Page <strong>{page}</strong> updated", [
                    'page' => $page->title
                ]));
                return $this->controller->refresh();
            }
        }


        return $this->controller->render('update', [
            'page' => $page,
            'project' => $page->project
        ]);
    }
}
