<?php

declare(strict_types=1);

namespace prime\controllers\survey;

use prime\helpers\ModelHydrator;
use prime\models\search\SurveySearch;
use prime\repositories\SurveyRepository;
use yii\base\Action;
use yii\web\Request;

class Index extends Action
{
    public function run() {
        return $this->controller->render('index');
    }
}
