<?php


namespace prime\helpers;


use prime\assets\IconBundle;
use yii\helpers\Html;
use yii\helpers\Inflector;

/**
 * Class Icon
 * @package prime\helpers
 * @method static string eye
 * @method static string pencilAlt
 * @method static string share
 * @method static string hospital
 * @method static string user
 * @method static string clipboardList
 * @method static string signOutAlt
// * @method static string pencil
 */
class Icon
{


    public static function icon($name, array $options = [])
    {
        $svg = IconBundle::register(\Yii::$app->view)->baseUrl . '/symbol-defs.svg';
        Html::addCssClass($options, ['icon', "icon-$name"]);
        return Html::tag('svg',
            Html::tag('use', '', ['href' => "{$svg}#icon-{$name}"]),
            $options
        );
    }

    public static function __callStatic($name, $arguments)
    {
        return self::icon(Inflector::camel2id($name), $arguments[0] ?? []);
    }
}