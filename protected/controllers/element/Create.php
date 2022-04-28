<?php

namespace prime\controllers\element;

use prime\components\LimesurveyDataProvider;
use prime\components\NotificationService;
use prime\helpers\UserAccessCheck;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Element;
use prime\models\ar\Page;
use prime\models\ar\Permission;
use prime\repositories\ProjectRepository;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;

class Create extends Action
{
    public function run(
        Request $request,
        NotificationService $notificationService,
        LimesurveyDataProvider $limesurveyDataProvider,
        AccessCheckInterface $accessCheck,
        int $page_id,
        string $type
    ) {
        $page = Page::findOne(['id' => $page_id]);
        if (!isset($page)) {
            throw new NotFoundHttpException();
        }

        $project = $page->project;


        $accessCheck->requirePermission($project, Permission::PERMISSION_MANAGE_DASHBOARD);



        try {
            $element = Element::instantiate(['type' => $type]);
            $element->type = $type;
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException('Invalid element type', 0, $e);
        }

        $element->page_id = $page->id;
        $element->sort = $page->getElements()->select('max(sort)')->scalar() + 1;

        $model = new \prime\models\forms\Element($limesurveyDataProvider->getSurvey($project->base_survey_eid), $element);
        $model->load($request->queryParams);
        if ($request->isPost) {
            if ($model->load($request->bodyParams) && $model->save()) {
                $notificationService->success(\Yii::t('app', "Element created"));
                switch ($request->bodyParams['action']) {
                    case 'dashboard':
                        return $this->controller->redirect([
                            'project/view',
                            'page_id' => $element->page->id,
                            'id' => $element->project->id
                        ]);
                    case 'refresh':
                    default:
                        return $this->controller->redirect(['update', 'id' => $model->id]);
                }
            } else {
                $notificationService->error(\Yii::t('app', "Element not created"));
            }
        }

        $breadcrumbCollection = $this->controller->view->getBreadcrumbCollection();

        return $this->controller->render('update', [
            'page' => $page,
            'model' => $model,
            'project' => $project,
            'url' => Url::to(array_merge($request->queryParams, [
                '__key__' => '__value__',
                '0' => $this->uniqueId
            ]))
        ]);
    }
}
