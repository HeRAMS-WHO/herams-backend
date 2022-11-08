<?php

declare(strict_types=1);

namespace prime\controllers\survey;

use herams\common\helpers\ModelHydrator;
use prime\actions\FrontendAction;
use yii\base\Action;

final class Index extends FrontendAction
{
    public function run(): string {
        return $this->render('index');
    }
}
