<?php

namespace app\models;

use app\models\stats\PriorityTable;
use prime\models\ar\Project;
use prime\models\ar\Tool;
use SamIT\LimeSurvey\JsonRpc\Client;
use SamIT\LimeSurvey\JsonRpc\SerializeHelper;
use yii\base\Model;
use Carbon\Carbon;
use yii\caching\Cache;
use yii\web\HttpException;


class Overview extends Model
{
    /**
     * List HF coordinates.
     * @param Cache $cache
     * @param int $pid
     * @param $string code
     * @return array
     * @throws HttpException
     */
    public static function mapPoints(Client $limeSurvey, Cache $cache, $pid, $code, $services = false)
    {
        $responses = self::loadResponses($cache, $pid, self::filters());
        if ($services) {
            $model = Tool::loadOne($pid);
            $structure = self::loadStructure($limeSurvey, $cache, $model->base_survey_eid);
            $qCodes = self::groupQuestions($structure, explode(',', $services));
        }

        $hfCoordinates = [];
        foreach ($responses as $hf) {
            if ($hf['GPS[SQ001]'] != '' && $hf['GPS[SQ002]'] != '' && is_numeric($hf['GPS[SQ001]']) && is_numeric($hf['GPS[SQ002]']) ) {
                $item = ['coord' => [$hf['GPS[SQ001]'], $hf['GPS[SQ002]']]];

                if ($services) {
                    $item['type'] = self::serviceLevel($hf, $qCodes);
                } else {
                    $item['type'] = (isset($hf[$code])) ? $hf[$code] : '';
                }

                // HF data for pop-ups
                $item['hf'] = [
                    ['HF1' => $hf['HF1']],
                    ['HF2' => $hf['HF2']],
                    ['HF3' => $hf['HF3']],
                    ['HF4' => $hf['HF4']],
                    ['HFINF1' => $hf['HFINF1']],
                    ['HFINF3' => $hf['HFINF3']],
                ];

                $hfCoordinates[] = $item;
            }
        }

        return $hfCoordinates;
    }

    /**
     * Calculate HF service level percentage
     * @param $response
     * @param $qCodes
     * @return string
     */
    public static function serviceLevel($response, $qCodes)
    {
        $nrAvailable = 0;
        $nrTotal = 0;
        $codes = array_filter(array_keys($qCodes), function($v){
            return substr($v, -1) != 'x';
        });

        foreach($codes as $code) {
            if (isset($response[$code]) && $response[$code] && $response[$code] != 'A4') {
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
     * Get questions in question groups
     * @param $structure
     * @param $groupIds
     * @return array
     */
    public static function groupQuestions($structure, $groupIds)
    {
        $questions = [];
        foreach($groupIds as $groupId) {
            foreach($structure['groups'] as $group)
                if ($group['index'] == $groupId) {
                    $questions += $group['questions'];
                    break;
                }
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
    public static function loadStructure(Client $limeSurvey, Cache $cache, $id=742358)
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

    public static function geoLabels(Client $limeSurvey, Cache $cache, $survey_eid)
    {
        $structure = self::loadStructure($limeSurvey, $cache, $survey_eid);

        $labels =  ['2' => 'State', '3' => 'Locality'];
        foreach ($structure['groups'] as $group) {
            if (isset($group['questions']['GEO1']['text']))
                $labels['2'] = $group['questions']['GEO1']['text'];
            if (isset($group['questions']['GEO2']['text'])) {
                $labels['3'] = $group['questions']['GEO2']['text'];
                break;
            }
        }
        return ['geo_level' => $labels];
    }

    /**
     * Load survey response data from LimeSurvey
     * @param Cache $cache
     * @param int $id
     * @param string $entity
     * @return array|mixed
     * @throws HttpException
     */
    public static function loadResponses(Cache $cache, $id, $filters, $entity = 'project')
    {
        $limitDate = isset($filters['date']) ? Carbon::createFromTimestamp(strtotime($filters['date'])) : new Carbon();
        $cacheKey = 'responses' . $id . $entity . $limitDate->format('Ymd');
        if (false === $responses = $cache->get($cacheKey)) {
            $responses = [];

            switch ($entity) {
                case 'workspace':
                    $data = Project::loadOne($id)->getResponses();
                    break;
                case 'project':
                    $data = Tool::loadOne($id)->getResponses();
            }

            $responses = self::prepareData($data, $limitDate);

            $cache->set($cacheKey, $responses, 3600);
        }
        if (self::hasFilters($filters)) {
            $filtered = [];
            foreach($responses as $response) {
                if (self::filterResponse($response, $filters))
                    $filtered[] = $response;
            }
            return $filtered;
        }
        return $responses;
    }

    public static function prepareData($responses, Carbon $date = null)
    {
        if (!isset($date)) {
            $date = new Carbon();
        }

        $tempData = [];
        foreach ($responses as $response) {
            $responseData = $response->getData();
            if ($responseData['UOID'] != '' && isset($responseData['datestamp'])) {
                $responseDate = new Carbon($responseData['datestamp']);
                if (!isset($tempData[$responseData['UOID']]) && $responseDate->lte($date)) {
                    $tempData[$responseData['UOID']] = $responseData;
                } else {
                    if ($responseDate->lte($date) && $responseDate->gt(new Carbon($tempData[$responseData['UOID']]['datestamp']))) {
                        $tempData[$responseData['UOID']] = $responseData;
                    }
                }
            }
        }
        return array_values($tempData);
    }

    public static function hasFilters($filters)
    {
        return (!empty($filters['location']) ) ||
        (isset($filters['date']) && $filters['date'] ) ||
        (!empty($filters['hftypes']) ) ||
        (isset($filters['advanced']) && is_array($filters['advanced']) && count($filters['advanced']) > 0);
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
        if (!empty($filters['hftypes']) && is_array($filters['hftypes']) && !in_array($response['HF2'], $filters['hftypes']))
            return false;
        if (isset($filters['advanced']) && is_array($filters['advanced'])) {
            foreach ($filters['advanced'] as $filter) {
                foreach ($filter as $code => $opts) {
                    //if (!isset($response[$code])) return false;
                    $opts = explode(',', $opts);
                    if (count($opts)>0 && isset($response[$code]) && !in_array($response[$code], $opts))
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
        $filters = self::filters();

        foreach ($responses as $response) {
            //if (self::filterResponse($response, $filters)) {
                if ($response[$questionCode])
                    $result[$response[$questionCode]] = (isset($result[$response[$questionCode]])) ? $result[$response[$questionCode]]+1 : 1;
                else
                    $result["NR"] = (isset($result["NR"])) ? $result["NR"]+1 :  1;
            //}
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

    /**
     * Format chart data for Angular.
     * @param array $chart
     * @param array $responses
     * @return array
     */
    public static function formatChart($chart, $responses)
    {
        if ($chart['rendering_type'] == 'pie') {
            $chartData = self::questionData($responses, $chart['query']);
            $labels = self::indicatorLabels($chart['indicator_id']);
            $formatted = self::pieChart($labels, $chartData, $chart['indicator_name']);

        } elseif ($chart['rendering_type'] == 'stacked') {
            // show dummy chart until defined
            $formatted = [
                "type" => "stacked",
                "title" => "Damage per type",
                "total" => 743,
                "labels" => [
                    'Hospital (Tertiary)',
                    'Hospital (Secondary)',
                    'Health Centre (Primary)',
                    'Primary Health Care Centre (Primary)',
                    'Clinic (Primary)',
                    'Mobile clinic',
                ],
                "colors" => ['#00bc1a', '#f7931e', '#ff0000', '#adadad'],
                "series" => [
                    [
                        "name" => "Not damaged",
                        "data" => [0, 0, 0, 0, 0, 0],
                    ],
                    [
                        "name" => "Partially damaged",
                        "data" => [0, 0, 0, 0, 0, 0],
                    ],
                    [
                        "name" => "Fully damaged",
                        "data" => [0, 0, 0, 0, 0, 0],
                    ],
                    [
                        "name" => "N/A",
                        "data" => [100, 100, 100, 100, 100, 100],
                    ],
                ],
            ];
        } else {
            if ($chart['indicator_id'] == 7) {
                $formatted = PriorityTable::getTopDamage($responses);
            } elseif ($chart['indicator_id'] == 8) {
                $formatted = PriorityTable::makePriorityTable(5, $responses, 'HFINF3', 'HFINF4[1]', 'Priority areas / Functionality');
            }
        }

        return $formatted;
    }

    /**
     * Format pie chart data
     * @param array $labels
     * @param array $data
     * @param string $title
     * @return array
     */
    public static function pieChart($labels, $data, $title)
    {
        $chart = [];
        $total = 0;

        foreach ($labels as $code => $row) {
            $val = (isset($data[$code])) ? (int)$data[$code] : 0;
            $chart[] = [
                'name' => $row['label'],
                'y' => $val,
                'color' => $row['color'],
            ];
            $total += (int)$val;
        }

        $formatted = [
            "type" => "pie",
            "title" => $title,
            "total" => $total,
            "data" => $chart,
        ];

        return $formatted;
    }
}

