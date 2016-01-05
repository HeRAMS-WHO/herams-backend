<?php


namespace prime\factories;

use yii\helpers\ArrayHelper;


/**
 * Class GeneratorFactory
 * @package prime\factories
 */
class GeneratorFactory
{
    /**
     * Returns a list of all known generator classes.
     *
     * @return array
     */
    public static function classes()
    {
        return [
            'ccpm' => \prime\reportGenerators\ccpm\Generator::class,
            'cd' => \prime\reportGenerators\cd\Generator::class,
            'cdProgress' => \prime\reportGenerators\cdProgress\Generator::class,
            'percentage' => \prime\reportGenerators\progressPercentage\Generator::class,
            'ccpmPercentage' => \prime\reportGenerators\ccpmProgressPercentage\Generator::class
        ];

    }

    /**
     * Returns a map of generator name => title
     * @return array
     */
    public static function options() {
        return array_map(function($className) {
            return $className::title();
        }, GeneratorFactory::classes());
    }

    public static function get($name) {
        return \Yii::$container->get(ArrayHelper::getValue(self::classes(), $name, $name));
    }
}