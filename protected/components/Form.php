<?php

namespace app\components;

class Form extends \kartik\builder\Form
{
    protected function renderActiveInput($form, $model, $attribute, $settings)
    {
        if (!isset($settings['options']['placeholder']) &&
            isset($form->formConfig['defaultPlaceholder']) &&
            $form->formConfig['defaultPlaceholder'] &&
            isset($settings['type']) &&
            $settings['type'] != static::INPUT_WIDGET
        ) {
            $settings['options']['placeholder'] = $model->getAttributeLabel($attribute);
        }
        return parent::renderActiveInput($form, $model, $attribute, $settings);
    }
}
