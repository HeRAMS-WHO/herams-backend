<?php

namespace app\models;

use prime\models\ar\Project;
use prime\models\ar\Tool;
use SamIT\LimeSurvey\JsonRpc\Client;
use SamIT\LimeSurvey\JsonRpc\SerializeHelper;
use yii\base\Model;
use yii\caching\Cache;
use yii\web\HttpException;


class Overview extends Model
{
    /**
     * List HF coordinates.
     * @param Cache $cache
     * @param integer $pid
     * @param string $code
     * @return array
     * @throws HttpException
     */
    public static function mapPoints(Client $limeSurvey, Cache $cache, $pid, $code, $services=false)
    {
        $responses = self::loadResponses($cache, $pid);
        $filters = self::filters();
        if ($services) {
            $structure = self::loadStructure($limeSurvey, $cache);
            $qCodes = self::groupQuestions($structure, explode(',',$services));
        }

        $hfCoordinates = [];
        foreach ($responses as $hf) {
            if ($hf['GPS[SQ001]'] != '') {
                if (self::filterResponse($hf, $filters)) {
                    if ($services) {
                        $sl =  self::serviceLevel($hf, $qCodes);
                        $item = ['coord' => [$hf['GPS[SQ001]'], $hf['GPS[SQ002]']], 'type' => $sl];
                    } else {
                        $item = ['coord' => [$hf['GPS[SQ001]'], $hf['GPS[SQ002]']], 'type' => $hf[$code]];
                    }
                    $hfCoordinates[] = $item;
                }
            }
        }

        return $hfCoordinates;
    }

    /**
     * Calculate service level percentage for a HF.
     * @param array $response
     * @param array $qCodes
     */
    public static function serviceLevel($response, $qCodes)
    {
        $nrAvailable = 0;
        $nrTotal = 0;
        $codes = array_filter(array_keys($qCodes), function($v){
            return substr($v, -1) != 'x';
        });

        foreach($codes as $code) {
            if ($response[$code] && $response[$code] != 'A4') {
                if ($response[$code] == 'A1')
                    ++$nrAvailable;
                ++$nrTotal;
            }
        }
        if($nrTotal == 0) return 'C1';
        $percentAvailable = round($nrAvailable / $nrTotal * 100.0);

        if ($percentAvailable < 25) return 'C1';
        if ($percentAvailable < 50) return 'C2';
        if ($percentAvailable < 75) return 'C3';
        return 'C4';
    }

    /**
     * List question codes in a question group.
     * @param array $structure
     * @param array $groupIds
     */
    public static function groupQuestions($structure, $groupIds)
    {
        $questions = [];
        foreach($groupIds as $groupId) {
            $questions += $structure['groups'][$groupId]['questions'];
        }
        return $questions;
    }

    /**
     * Load survey structure from LimeSurvey
     * @param Client $limeSurvey
     * @param Cache $cache
     * @param int $id
     * @return array|mixed
     * @throws HttpException
     */
    public static function loadStructure(Client $limeSurvey, Cache $cache, $id=999549)
    {
        $cacheKey = "STRUCTURE.$id";

        $survey = $cache->get($cacheKey);
        if ($survey === false) {
            try {
                $survey = SerializeHelper::toArray($limeSurvey->getSurvey($id));
            } catch (\Exception $e) {
                throw new HttpException(404, $e->getMessage());
            }
            $cache->set($cacheKey, $survey, 3600);
        }
        return $survey;
    }

    /**
     * Load survey response data from LimeSurvey
     * @param Cache $cache
     * @param int $id
     * @param string $entity
     * @return array|mixed
     * @throws HttpException
     */
    public static function loadResponses(Cache $cache, $id, $entity = 'project')
    {
        $cacheKey = __CLASS__ . __FILE__ . $id . $entity;
        if (false === $responses = $cache->get($cacheKey)) {
            $responses = [];

            switch ($entity) {
                case 'project':
                    $data = Project::loadOne($id)->getResponses();
                    break;
                case 'tool':
                    $data = Tool::loadOne($id)->getResponses();
            }

            foreach($data as $response) {
                $responses[] = $response->getData();
            }
            $cache->set($cacheKey, $responses, 31200);
        }
        return $responses;
    }

    /**
     * Return true if a response matches given filters
     * @param array $response
     * @param array $filters
     * @return bool
     */
    public static function filterResponse($response, $filters)
    {
        if (!empty($filters['location']) && is_array($filters['location']) && !in_array($response['GEO2'], $filters['location']))
            return false;
        if (isset($filters['date']) && $filters['date'] && strtotime($response['datestamp']) > strtotime($filters['date']))
            return false;
        if (!empty($filters['hftypes']) && is_array($filters['hftypes']) && !in_array($response['HF2'], $filters['hftypes']))
            return false;
        if (isset($filters['advanced']) && is_array($filters['advanced'])) {
            foreach ($filters['advanced'] as $filter) {
                foreach ($filter as $code => $opts) {
                    $opts = explode(',', $opts);
                    if (!empty($opts) && isset($response[$code]) && !in_array($response[$code], $opts))
                        return false;
                }
            }
        }
        return true;
    }

    /**
     * Get indicator labels and colors from DB
     * @param int $id
     * @return array
     */
    public static function indicatorLabels($id)
    {
        $labels = [];
        $options = \Yii::$app->db->createCommand('SELECT * FROM prime2_indicator_option WHERE indicator_id=:id')
            ->bindValue(':id', $id)
            ->queryAll();

        foreach ($options as $opt) {
            $labels[$opt['option_code']] = ['label' => $opt['option_label'], 'color' => $opt['option_color']];
        }

        return $labels;
    }

    /**
     * Decode filter parameter if exists
     * @return array|mixed
     */
    public static function filters()
    {
        if (($json = \Yii::$app->request->get('filters')) != '') {
            $filters = \json_decode($json, true);
            if (!empty($filters['location'])) {
                $filters['location'] = self::getRegions($filters['location']);
            }

            return $filters;
        }
        return [];
    }

    /**
     * Get region names with a geo id list.
     * @param array $geoCodes
     */
    public static function getRegions($geoCodes)
    {
        $regions = [];
        foreach($geoCodes as $geoId) {
            $name = \Yii::$app->db->createCommand('SELECT geo_name FROM prime2_geography WHERE geo_id = :id')
                ->bindValue(':id', $geoId)
                ->queryScalar();
            $regions[] = $name;
        }
        return $regions;
    }

    /**
     * Calculte distribution of answers for given question
     * @param array $responses
     * @param string $questionCode
     * @return array
     */
    public static function questionData($responses, $questionCode)
    {
        $result = [];
        $filters = self::getFilters();

        foreach ($responses as $response) {
            if (self::filterResponse($response, $filters)) {
                if ($response[$questionCode])
                    $result[$response[$questionCode]] = (isset($result[$response[$questionCode]])) ? $result[$response[$questionCode]]+1 : 1;
                else
                    $result["NR"] = (isset($result["NR"])) ? $result["NR"]+1 :  1;
            }
        }
        return $result;
    }

    /**
     * Get labels and colors for map points.
     *
     * @param integer $id
     * @return array
     */
    public static function mapLegend($id)
    {
        $labels = [];
        $colors = [];
        $options = \Yii::$app->db->createCommand('SELECT * FROM prime2_indicator_option WHERE indicator_id=:id')
            ->bindValue(':id', $id)
            ->queryAll();

        foreach ($options as $opt) {
            $labels[$opt['option_code']] = ['label' => $opt['option_label'], 'color' => $opt['option_color']];
            $colors[$opt['option_code']] = $opt['option_color'];
        }

        /** @todo  remove colors when front can take them from legend */
        $legend = [
            'colors' => $colors,
            "legend" => $labels,
        ];

        return $legend;
    }
}
