<?php
declare(strict_types=1);

namespace prime\controllers\site;

use yii\base\Action;
use yii\web\Response;

class Status extends Action
{

    public function run(Response $response)
    {
        $hash = file_exists('/run/commit_sha') ? trim(file_get_contents('/run/commit_sha')) : null;
        $response->format = Response::FORMAT_JSON;
        $response->data = array_filter([
            'commit' => $hash ?? 'unknown',
            'github' => $hash ? "https://github.com/HeRAMS-WHO/herams-backend/commit/$hash" : null
        ]);
    }
}
