<?php

namespace prime\components;

class Controller extends \Befound\Components\Controller
{
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