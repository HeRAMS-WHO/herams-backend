<?php

declare(strict_types=1);

namespace prime\controllers\survey;

use prime\actions\FrontendAction;

final class Index extends FrontendAction
{
    public function run(): string
    {
        return $this->render('index');
    }
}
