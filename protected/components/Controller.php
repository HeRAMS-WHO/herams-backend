<?php

namespace prime\components;

class Controller extends \Befound\Components\Controller
{
    public $layout = 'oneRow';

    public function accessRules() {
        $rules = [
            [
                'allow',
                'roles' => ['admin']
            ]
        ];
        return array_merge($rules, parent::accessRules());
    }
}