<?php

declare(strict_types=1);

namespace prime\tests\_helpers;

use yii\base\Model;
use yii\base\NotSupportedException;

trait YiiLoadMustBeDisabled
{
    abstract private function getModel(): Model;

    public function testLoadIsDisabled()
    {
        $this->expectException(NotSupportedException::class);
        $this->getModel()->load([]);
    }
}
