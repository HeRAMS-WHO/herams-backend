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
        echo \kartik\helpers\Html::well("Dumped from: " . $token . '<br>'. \yii\helpers\VarDumper::dumpAsString($arg, $depth, $highlight), \kartik\helpers\Html::SIZE_LARGE,[
            'style' => 'text-align: left;'
        ]);
    }
}

function vdd($var, $message = '', $depth = 10, $highlight = true) {
    vd($var, $depth, $highlight = true);
    die($message);
}

function toSql(\yii\db\Query $query)
{
    return $query->prepare(app()->db->queryBuilder)->createCommand()->rawSql;
}

function vdSql(\yii\db\Query $query, $depth = 10, $highlight = true)
{
    vd(toSql($query), $depth, $highlight);
}

function vddSql(\yii\db\Query $query, $mesage = '', $depth = 10, $highlight = true)
{
    vdSql($query, $depth, $highlight);
    die($mesage);
}

function median(array $array, $precision = 0)
{
    if(empty($array)) {
        throw new \Exception("Array must not be empty");
    }

    rsort($array);
    if(count($array) % 2 == 1) {
        $middle = round(count($array) / 2);
        return $array[$middle - 1];
    } else {
        $middle = floor(count($array) / 2);
        return round(($array[$middle - 1] + $array[$middle]) / 2, $precision);
    }

}

function average(array $array)
{
    if(empty($array)) {
        throw new \Exception("Array must not be empty");
    }
    return array_sum($array) / count($array);
}
