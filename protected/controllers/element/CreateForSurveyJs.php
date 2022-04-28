<?php

declare(strict_types=1);

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

class CreateForSurveyJs extends Action
{
    public function run(
        Request $request,
        NotificationService $notificationService,
        AccessCheckInterface $accessCheck,
        int $page_id
    ) {
        $page = Page::findOne(['id' => $page_id]);
        if (!isset($page)) {
            throw new NotFoundHttpException();
        }

        $project = $page->project;

        $accessCheck->requirePermission($project, Permission::PERMISSION_MANAGE_DASHBOARD);



        try {
            $element = Element::instantiate(['type' => "chart"]);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException('Invalid element type', 0, $e);
        }

        $element->page_id = $page->id;
        $element->sort = $page->getElements()->select('max(sort)')->scalar() + 1;


        $breadcrumbCollection = $this->controller->view->getBreadcrumbCollection();

        return $this->controller->render('update-survey-js', [
            'page' => $page,
            'model' => $element,
            'project' => $project,
            'url' => Url::to(array_merge($request->queryParams, [
                '__key__' => '__value__',
                '0' => $this->uniqueId
            ]))
        ]);
    }
}
