<?php

declare(strict_types=1);

namespace herams\api\controllers\project;

use herams\common\domain\project\ProjectLocalesRetriever;
use herams\common\values\ProjectId;
use yii\base\Action;

final class Locales extends Action
{
    public function run(
        ProjectLocalesRetriever $projectRepository,
        int $id
    ) {
        return $projectRepository->retrieveProjectLocales(new ProjectId($id));
    }
}
