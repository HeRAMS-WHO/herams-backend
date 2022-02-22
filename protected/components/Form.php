<?php

namespace app\components;

class Form extends \kartik\builder\Form
{
    protected function renderActiveInput($form, $model, $attribute, $settings)
    {
        if (
            !isset($settings['options']['placeholder']) &&
            isset($form->formConfig['defaultPlaceholder']) &&
            $form->formConfig['defaultPlaceholder'] &&
            isset($settings['type']) &&
            $settings['type'] != static::INPUT_WIDGET
        ) {
            $settings['options']['placeholder'] = $model->getAttributeLabel($attribute);
        }
        return parent::renderActiveInput($form, $model, $attribute, $settings);
    }

    protected function renderFieldSet()
    {
        $i = 0;
        ob_start();
        ob_implicit_flush(false);
        foreach ($this->attributes as $attribute => $config) {
            echo $this->parseInput($attribute, $config, $i);
            $i++;
        }
        return ob_get_clean();
    }
}
