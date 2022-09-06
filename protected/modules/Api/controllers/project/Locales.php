<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers\project;

use prime\interfaces\project\ProjectLocalesRetriever;
use prime\repositories\ProjectRepository;
use prime\values\ProjectId;
use yii\base\Action;

final class Locales extends Action
{
    public function run(
        ProjectLocalesRetriever $projectRepository,
        int $id
    )
    {
        $locales = $projectRepository->retrieveProjectLocales(new ProjectId($id));
        var_dump($locales);
        die();
    }
}
