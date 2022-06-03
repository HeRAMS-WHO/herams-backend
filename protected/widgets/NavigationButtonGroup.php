<?php

declare(strict_types=1);

namespace prime\widgets;

use yii\base\Widget;

class NavigationButtonGroup extends Widget
{
    public iterable $buttons = [];

    public function run(): string
    {
        return ButtonGroup::widget([
            'options' => [
                'class' => ['NavigationButtonGroup'],
            ],
            'buttons' => $this->buttons,
            'tagName' => 'nav',
        ]);
    }
}
