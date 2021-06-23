<?php
declare(strict_types=1);

namespace prime\models\pages;

use prime\models\ar\Page;
use prime\values\PageId;
use prime\values\ProjectId;

/**
 * @codeCoverageIgnore Since all functions are simple getters
 */
class ForBreadcrumb implements \prime\interfaces\page\ForBreadcrumb
{
    private PageId $id;
    private ProjectId $projectId;
    private string $title;

    public function __construct(
        Page $model
    ) {
        $this->id = new PageId($model->id);
        $this->projectId = new ProjectId($model->project_id);
        $this->title = $model->title;
    }

    public function getId(): PageId
    {
        return $this->id;
    }

    public function getProjectId(): ProjectId
    {
        return $this->projectId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
