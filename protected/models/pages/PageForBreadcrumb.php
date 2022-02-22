<?php

declare(strict_types=1);

namespace prime\models\pages;

use prime\interfaces\page\PageForBreadcrumbInterface;
use prime\models\ar\Page;
use prime\traits\BreadcrumbTrait;
use prime\values\ProjectId;

class PageForBreadcrumb implements PageForBreadcrumbInterface
{
    use BreadcrumbTrait;

    private ProjectId $projectId;

    public function __construct(
        Page $model
    ) {
        $this->label = $model->title;
        $this->projectId = new ProjectId($model->project_id);
        $this->url = ['/page/update', 'id' => $model->id];
    }

    public function getProjectId(): ProjectId
    {
        return $this->projectId;
    }
}
