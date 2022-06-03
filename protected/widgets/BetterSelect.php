<?php

declare(strict_types=1);

namespace prime\widgets;

use yii\helpers\Html;
use yii\widgets\InputWidget;

class BetterSelect extends InputWidget
{
    public iterable $items = [];

    public function init()
    {
        parent::init();
        ob_start();
        $name = Html::getInputName($this->model, $this->attribute);
        if (substr($name, -2) !== '[]') {
            $name .= '[]';
        }
        $options = array_merge([
            'name' => $name,
        ], $this->options);

        echo Html::beginTag('better-select', $options);
    }

    public function run(): string
    {
        $css = <<<CSS
            
CSS;

        $this->view->registerCss($css);
        $this->view->registerJsFile('/js/components/better-select.js', [
            'type' => 'module',
        ]);
        foreach ($this->items as $value => $label) {
            // Render label then content.
            echo Html::tag('data', $label, [
                'value' => $value,
            ]);
        }
        echo Html::endTag('better-select');
        return ob_get_clean();
    }
}
