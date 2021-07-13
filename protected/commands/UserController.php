<?php
declare(strict_types=1);

namespace prime\commands;

use prime\components\NewsletterService;
use yii\console\Controller;

class UserController extends Controller
{
    public function actionInitNewsletterStatus(
        NewsletterService $newsletterService
    ) {
        $newsletterService->initSyncExternalToDatabase();
    }
}
