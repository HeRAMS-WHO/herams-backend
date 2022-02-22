<?php

declare(strict_types=1);

namespace prime\controllers\mail;

use prime\components\NewsletterService;
use yii\base\Action;
use yii\web\Request;

class Webhook extends Action
{
    public function init()
    {
        parent::init();

        $this->controller->enableCsrfValidation = false;
    }

    public function run(
        NewsletterService $newsletterService,
        Request $request
    ) {
        $newsletterService->handleWebhook($request);
    }
}
