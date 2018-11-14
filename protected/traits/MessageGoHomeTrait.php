<?php

namespace prime\traits;

/**
 * Class MessageGoHomeTrait
 * @package prime\traits
 *
 * This trait is used for the extension of the behaviour of Dektrium Yii2-user
 * When the render function gets '/message' it should go home
 */
trait MessageGoHomeTrait
{
    public function render($view, $params = [])
    {
        if($view == '/message') {
            $this->goHome();
        } else {
            return parent::render($view, $params);
        }
    }
}
