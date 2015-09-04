<?php
/**
 * Some helper functions in the global namespace.
 */

/**
 *
 * @return \app\components\WebApplication;
 */
function app()
{
    return \Yii::$app;
}

/**
 * Helperfunction for debugging.
 */
function vd($arg, $depth = 10, $highlight = true) {
    if (defined('YII_DEBUG') && YII_DEBUG) {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        if ($trace[1]['function'] == 'vdd') {
            $details = $trace[2];
            $file = $trace[1]['file'];
            $line = $trace[1]['line'];
        } else {
            $details = $trace[1];
            $file = $trace[0]['file'];
            $line = $trace[0]['line'];

        }
        $class = \yii\helpers\ArrayHelper::getValue($details, 'class', 'Global function');
        $token = "{$class}::{$details['function']}, ({$file}:{$line})";
        echo \kartik\helpers\Html::well("Dumped from: " . $token . '<br>'. \app\components\VarDumper::dumpAsString($arg, $depth, $highlight), \kartik\helpers\Html::SIZE_LARGE,[
            'style' => 'text-align: left;'
        ]);
    }
}

function vdd($var, $message = '', $depth = 10, $highlight = true) {
    vd($var, $depth = 10, $highlight = true);
    die($message);
}