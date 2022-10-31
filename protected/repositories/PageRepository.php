<?php

declare(strict_types=1);

namespace prime\repositories;

use prime\interfaces\AccessCheckInterface;
use prime\interfaces\page\PageForBreadcrumbInterface as ForBreadcrumbInterface;
use prime\models\ar\Page;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\Workspace;
use prime\models\pages\PageForBreadcrumb;
use prime\values\PageId;
use prime\values\ProjectId;
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
     * @return list<Page>
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
