<?php

declare(strict_types=1);

namespace prime\models\project;

use herams\common\models\Project;
use prime\interfaces\project\ProjectForBreadcrumbInterface;
use prime\traits\BreadcrumbTrait;

class ProjectForBreadcrumb implements ProjectForBreadcrumbInterface
{
    use BreadcrumbTrait;

    public function __construct(
        Project $model
    ) {
        $this->label = $model->getTitle();
        $this->url = [
            '/project/view',
            'id' => $model->id,
        ];
    }
}
