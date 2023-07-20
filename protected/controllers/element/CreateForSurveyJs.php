<?php

declare(strict_types=1);

namespace prime\controllers\element;

use herams\common\domain\element\Element;
use herams\common\interfaces\AccessCheckInterface;
use herams\common\models\Page;
use herams\common\models\Permission;
use herams\common\values\PageId;
use herams\common\values\ProjectId;
use prime\components\NotificationService;
use yii\base\Action;
use yii\web\BadRequestHttpException;
use yii\web\Request;
use yii\web\UnprocessableEntityHttpException;

class CreateForSurveyJs extends Action
{
    public function run(
        Request $request,
        NotificationService $notificationService,
        AccessCheckInterface $accessCheck,
        int $page_id
    ) {

        $page = Page::findOne([
            'id' => $page_id,
        ]);
        if (! isset($page)) {
            throw new UnprocessableEntityHttpException(\Yii::t('app', 'Page with id {id} not found', [
                'id' => $page_id,
            ]));
        }

        $project = $page->project;

        $accessCheck->requirePermission($project, Permission::PERMISSION_MANAGE_DASHBOARD);

        try {
            $element = Element::instantiate([
                'type' => "bar",
            ]);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException('Invalid element type', 0, $e);
        }

        $element->page_id = $page->id;
        $element->sort = $page->getElements()->getNextSortValue();

        $breadcrumbCollection = $this->controller->view->getBreadcrumbCollection();

        $projectId = new ProjectId($page->project_id);
        return $this->controller->render('update-survey-js', [
            'pageId' => PageId::fromPage($page),
            'projectId' => $projectId,
            'endpointUrl' => [
                '/api/element/create',
                'projectId' => $projectId,
            ],
            'model' => $element,
        ]);
    }
}
