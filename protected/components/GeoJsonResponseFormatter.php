<?php

declare(strict_types=1);

namespace prime\components;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Response;
use yii\web\ResponseFormatterInterface;

class GeoJsonResponseFormatter implements ResponseFormatterInterface
{

    /**
     * @param Response $response
     */
    public function format($response)
    {
        $response->getHeaders()->set('Content-Type', 'application/geo+json');


        if ($response->data !== null) {
            $options = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT;

            $response->content = Json::encode($this->transformToFeatureCollection($response->data), $options);
        } elseif ($response->content === null) {
            $response->content = 'null';
        }
    }

    private function transformToFeatureCollection(array $entries): array
    {
        $data = [
            "type" => "FeatureCollection",
            "features" => []
        ];

        foreach ($entries as $entry) {
            // Check if this is a valid GeoJSON feature.
            if (!isset($entry['latitude'], $entry['longitude'])) {
                continue;
            }

            $data['features'][] = [
                "type" => "Feature",
                "id" => ArrayHelper::remove($entry, 'id'),
                "geometry" => [
                    "type" => "Point",
                    "coordinates" => [ArrayHelper::remove($entry, 'longitude'), ArrayHelper::remove($entry, 'latitude')]
                ],
                "properties" => $entry
            ];
        }
        return $data;
    }
}
