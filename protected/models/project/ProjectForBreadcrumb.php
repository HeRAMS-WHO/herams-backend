<?php

declare(strict_types=1);

namespace prime\models\project;

use prime\interfaces\project\ProjectForBreadcrumbInterface;
use prime\models\ar\Project;
use prime\traits\BreadcrumbTrait;

class ProjectForBreadcrumb implements ProjectForBreadcrumbInterface
{
    use BreadcrumbTrait;

    public function __construct(
        Project $model
    ) {
        $this->label = $model->title;
        $this->url = [
            '/project/view',
            'id' => $model->id,
        ];
    }
}
