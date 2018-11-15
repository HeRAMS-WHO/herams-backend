<?php
namespace prime\api;

use yii\base\Module;
use yii\log\FileTarget;

class Api extends Module
{
    public function init()
    {
        parent::init();
        $this->module->response->getHeaders()->add('Access-Control-Allow-Origin', '*');
        $this->module->log->targets['error'] = new FileTarget([
            'logFile' => '@runtime/logs/api-error.log',
            'levels' => ['warning', 'error', 'info']
        ]);
    }
}