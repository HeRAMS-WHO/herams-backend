<?php

declare(strict_types=1);

namespace prime\controllers\site;

use prime\interfaces\TicketingInterface;
use yii\base\Action;
use yii\web\BadRequestHttpException;

class LimeSurvey extends Action
{
    public function run(TicketingInterface $limesurveySSo, ?string $error = null): void
    {
        if (isset($error)) {
            throw new BadRequestHttpException($error);
        }
        $limesurveySSo->loginAndRedirectCurrentUser();
    }
}
