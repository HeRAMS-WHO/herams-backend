<?php
declare(strict_types=1);

namespace prime\widgets;

use kartik\builder\Form;
use yii\base\InvalidConfigException;
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Widget;
use yii\helpers\Html;

class FormButtonsWidget extends Widget
{
    public const ORIENTATION_RIGHT = 'right';
    public const ORIENTATION_LEFT = 'left';
    public const ORIENTATION_BLOCK = 'block';
    public Form $form;

    public $buttons = [];

    public string $orientation = self::ORIENTATION_RIGHT;

    public function run(): string
    {
        return $this->renderButtons();
    }

    protected function renderButtons(): string
    {
        Html::addCssClass($this->options, 'formbuttons orientation-' . $this->orientation);
        return Html::tag('div', ButtonGroup::widget([
            'buttons' => $this->buttons,
            'options' => $this->options
        ]), ['class' => ['form-group', 'formbuttons-container']]);
    }
    /**
     * Returns a closure for embedding this in a form.
     */
    public static function embed(array $config): array
    {

        return [
            'type' => Form::INPUT_RAW,
            'value' => static::widget($config)
        ];
    }
}
