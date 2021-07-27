<?php
declare(strict_types=1);

namespace prime\widgets;

use kartik\builder\Form;

/**
 * Class FormButtonsWidget
 * @package prime\widgets
 */
class FormButtonsWidget
{


    /**
     * Returns a config for embedding this in a form.
     */
    public static function embed(array $config): array
    {
        // Set the default button type to submit, which makes sense for forms.
        if (!isset($config['defaultButtonType'])) {
            $config['defaultButtonType'] = 'submit';
        }
        return [
            'type' => Form::INPUT_RAW,
            'value' => \prime\widgets\ButtonGroup::widget($config)
        ];
    }
}
