<?php
declare(strict_types=1);

namespace prime\controllers\site;

use prime\components\Controller;
use yii\base\Action;
use yii\caching\CacheInterface;
use yii\web\Response;

class Maintenance extends Action
{
    public function run(CacheInterface $cache, Response $response)
    {
        $this->controller->layout = Controller::LAYOUT_MAINTENANCE;
        $response->statusCode = 503;
        $response->headers->add('Retry-After', 3600);
        return $this->controller->render('maintenance');
    }
}
