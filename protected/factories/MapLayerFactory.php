<?php

namespace prime\factories;

use prime\models\MapLayer;
use yii\helpers\ArrayHelper;

/**
 * Class MapLayerFactory
 * @package prime\factories
 */
class MapLayerFactory
{
    /**
     * Returns a list of all known marketplace map layers.
     * @return array
     */
    public static function classes()
    {
        return [
            'countryGrades' => \prime\models\mapLayers\CountryGrades::class,
            'eventGrades' => \prime\models\mapLayers\EventGrades::class,
            'healthClusters' => \prime\models\mapLayers\HealthClusters::class,
            'projects' => \prime\models\mapLayers\Projects::class,
            'reports' => \prime\models\mapLayers\Reports::class,
            'base' => \prime\models\MapLayer::class
        ];
    }

    /**
     * @param $name
     * @return MapLayer
     * @throws \yii\base\InvalidConfigException
     */
    public static function get($name, $params = [], $config = [])
    {
        return \Yii::$container->get(ArrayHelper::getValue(self::classes(), $name, $name), $params, $config);
    }

    public static function getKey($class)
    {
        return array_flip(static::classes())[$class];
    }
}