<?php

declare(strict_types=1);

namespace prime\repositories;

use prime\interfaces\page\PageForBreadcrumbInterface as ForBreadcrumbInterface;
use prime\models\ar\Page;
use prime\models\pages\PageForBreadcrumb;
use prime\values\PageId;
use prime\values\ProjectId;
use yii\web\NotFoundHttpException;

class PageRepository
{
    public function retrieveForBreadcrumb(PageId $id): ForBreadcrumbInterface
    {
        $record = Page::findOne([
            'id' => $id,
        ]);
        return new PageForBreadcrumb($record);
    }

    public function retrieveForDashboarding(PageId $id)
    {
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
}
