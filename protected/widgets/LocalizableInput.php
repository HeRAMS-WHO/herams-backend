<?php
declare(strict_types=1);

namespace prime\widgets;

use yii\helpers\Html;
use yii\widgets\InputWidget;
use function iter\toArrayWithKeys;

class LocalizableInput extends InputWidget
{
    public iterable $languages = [];
    public function init(): void
    {
        parent::init();
        ob_start();
        $name = Html::getInputName($this->model, $this->attribute);
        $options = array_merge([
            'name' => $name,
            'value' => Html::getAttributeValue($this->model, $this->attribute),
            'languages' => toArrayWithKeys($this->languages),
        ], $this->options);

        echo Html::beginTag('localizable-input', $options);
    }

    public function run(): string
    {
        $css = <<<CSS
            
CSS;

        $this->view->registerCss($css);
        $this->view->registerJsFile('/js/components/localizable-text.js', ['type' => 'module']);
        echo Html::endTag('localizable-input');
        return ob_get_clean();
    }
}
