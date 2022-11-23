<?php

declare(strict_types=1);

namespace prime\controllers\project;

use prime\actions\FrontendAction;

final class Index extends FrontendAction
{
    public function run()
    {
        return $this->render(
            'index',
        );
    }
}
