<?php

namespace prime\controllers\element;

use prime\components\LimesurveyDataProvider;
use prime\components\NotificationService;
use prime\models\ar\Element;
use prime\models\ar\Permission;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;

class Update extends Action
{

    public function run(
        Request $request,
        LimesurveyDataProvider $limesurveyDataProvider,
        NotificationService $notificationService,
        User $user,
        int $id
    ) {
        $element = Element::findOne(['id' => $id]);
        if (!isset($element)) {
            throw new NotFoundHttpException();
        }
        if (!$user->can(Permission::PERMISSION_WRITE, $element)) {
            throw new ForbiddenHttpException();
        }

        $model = new \prime\models\forms\Element(
            $limesurveyDataProvider->getSurvey($element->project->base_survey_eid),
            $element
        );

        if ($request->isPost) {
            if ($model->load($request->bodyParams) && $model->save()) {
                $notificationService->success(\Yii::t('app', "Element updated"));
                switch ($request->bodyParams['action']) {
                    case 'dashboard':
                        return $this->controller->redirect([
                            'project/view',
                            'page_id' => $element->page->id,
                            'id' => $element->project->id
                        ]);
                        //return $this->controller->redirect($request->bodyParams['Element']['referrer']);
                    case 'refresh':
                    default:
                        return $this->controller->refresh();
                }
            } else {
                $notificationService->error(\Yii::t('app', "Element not updated"));
            }
        } elseif ($request->isGet) {
            // We load params from GET as well, this allows reloading the page with proper form fields.
            $model->load($request->queryParams);
        }

        return $this->controller->render('update', [
            'model' => $model,
            'project' => $model->page->project,
            'page' => $model->page,
            'url' => Url::to(array_merge($request->queryParams, [
                '__key__' => '__value__',
                '0' => $this->uniqueId
            ]))
        ]);
    }
}
