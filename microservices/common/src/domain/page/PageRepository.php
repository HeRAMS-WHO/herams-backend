<?php

declare(strict_types=1);

namespace herams\common\domain\page;

use herams\common\interfaces\AccessCheckInterface;
use herams\common\models\Page;
use herams\common\models\Permission;
use herams\common\models\Project;
use herams\common\values\PageId;
use herams\common\values\ProjectId;
use prime\interfaces\page\PageForBreadcrumbInterface as ForBreadcrumbInterface;
use prime\models\pages\PageForBreadcrumb;
use yii\web\NotFoundHttpException;

class PageRepository
{
    public function __construct(
        private AccessCheckInterface $accessCheck,
    )
    {

    }

    public function retrieveForBreadcrumb(PageId $id): ForBreadcrumbInterface
    {
        $record = Page::findOne([
            'id' => $id,
        ]);
        return new PageForBreadcrumb($record);
    }
    public function retrieveProjectId(PageId $pageId): ProjectId
    {
        $id = Page::find()
            ->andWhere([
                'id' => $pageId->getValue(),
            ])
            ->select('project_id')
            ->scalar();

        if (! isset($id)) {
            throw new NotFoundHttpException();
        }
        return new ProjectId($id);
    }

    /**
     * @return Page
     */
    public function retrieveForProject(ProjectId $id): array
    {
        $project = Project::findOne([
            'id' => $id->getValue(),
        ]);
        $this->accessCheck->requirePermission($project, Permission::PERMISSION_MANAGE_DASHBOARD);
        return Page::find()->andWhere(['project_id' => $id])->all();
    }
}