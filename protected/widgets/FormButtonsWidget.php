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
    public Form $form;

    public $buttons = [];

    public function init()
    {
        parent::init();
        if (!$this->form instanceof Form) {
            throw new InvalidConfigException("Form must be an instance of Form");
        }
    }

    public function run()
    {
        if (isset($this->form->form)) {
            if (!isset($this->form->form->formConfig['deviceSize'])) {
                $this->form->form->formConfig['deviceSize'] = null;
            }
            if (!isset($this->form->form->formConfig['labelSpan'])) {
                $this->form->form->formConfig['labelSpan'] = null;
            }

            $class = $this->form->form->getFormLayoutStyle()['offsetCss'] ?? [];
            Html::addCssClass($this->options, $class);
        }


        return $this->renderButtons();
    }

    protected function renderButtons()
    {
        if (isset($this->form->form->staticOnly)
            && $this->form->form->staticOnly
        ) {
            return Html::tag('p', \Yii::t('app', "You do not have permission to change these values."));
        }

        Html::addCssStyle($this->options, ['display' => 'block'], false);
        return Html::tag('div', ButtonGroup::widget([
            'buttons' => $this->buttons,
            'options' => $this->options
        ]), ['class' => 'form-group']);
    }
    /**
     * Returns a closure for embedding this in a form.
     */
    public static function embed(array $config): array {

        return [
            'type' => Form::INPUT_RAW,
            'value' => function($model, $index, Form $form) use ($config) {
                $config['form'] = $form;
                return static::widget($config);
            }
        ];
    }


}