<?php
declare(strict_types=1);

namespace herams\api\controllers;

class HealthController extends Controller
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['access']['rules'] = [['allow' => true], ...$behaviors['access']['rules']];
        return $behaviors;

    }


    public function actionStatus(): string
    {
        return 'OK';
    }
}
