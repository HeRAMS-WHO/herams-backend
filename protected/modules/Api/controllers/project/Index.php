<?php
declare(strict_types=1);

namespace prime\modules\Api\controllers\project;

use prime\models\ar\Project;
use yii\base\Action;
use yii\web\UrlManager;

class Index extends Action
{
    public function run(UrlManager $urlManager)
    {
        return $this->controller->asJson(Project::find()
            ->orderBy(['id' => 'asc'])
            ->andWhere(['visibility' => Project::VISIBILITY_PUBLIC])
            ->withFields('latestDate', 'workspaceCount', 'facilityCount', 'contributorPermissionCount')->all());
    }
}
