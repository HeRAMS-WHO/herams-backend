<?php

namespace app\models\stats;

use Carbon\Carbon;
use prime\models\ar\Workspace;
use prime\models\ar\Project;
use SamIT\LimeSurvey\JsonRpc\Client;
use SamIT\LimeSurvey\JsonRpc\SerializeHelper;
use yii\caching\Cache;
use yii\web\HttpException;


class ServiceAvailability
{
    public function status(Client $limeSurvey, Cache $cache, $group, $pid)
    {
        $model = Project::loadOne($pid);
        $structure = $this->loadStructure($limeSurvey, $cache, $model->base_survey_eid);
        $qCodes = $this->getGroup($structure, explode(",",$group));

        $responses = $this->loadResponses($cache, $pid, $this->getFilters());

        $availabilities = $this->getServiceAvailability($responses, $qCodes);
        $causes = $this->getCauses($responses, $qCodes);

        return $this->formatService($availabilities, $causes);
    }

    public function formatService($availabilities, $causes)
    {
        $serviceIndicators = [];

        // Service availability
        $labels = $this->getLabels(4);
        $title = \Yii::t('app' , 'Service availability');
        $chart = $this->makePieChart($labels, $availabilities['all'], $title);
        $serviceIndicators[] = $chart;

        // Causes
        $labels = $this->getLabels(9);
        $title = \Yii::t('app' , 'Causes of unavailability');
        $chart = $this->makePieChart($labels, $causes['all'], $title);
        $serviceIndicators[] = $chart;

        // Priority LGA
        $table = $this->makeTable($availabilities['top'], $causes, $labels);
        $serviceIndicators[] = $table;

        return $serviceIndicators;
    }

    public function makePieChart($labels, $data, $title)
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

    private function makeTable($topAreas, $causes, $labels)
    {
        $rows = [];
        foreach ($topAreas as $name => $percent) {
            $cause = $this->getTopCause($name, $causes, $labels);
            $row = [
                'name' => $name,
                'unavailability' => $percent,
                'cause' => $cause['name'],
                'cause_pc' => round($cause['percent'], 1),
            ];
            $rows[] = $row;
        }

        $formatted = [
            'name' => \Yii::t('app' , 'Priority areas / Service availability'),
            'answers' => 140,
            'type' => 'table',
            'columns' => [
                ['label' => \Yii::t('app' , 'LGA name'), 'class' => 'wide'],
                ['label' => \Yii::t('app' , 'Unavailability level (%)'), 'class' => 'small'],
                ['label' => \Yii::t('app' , 'Main cause'), 'class' => 'wide'],
                ['label' => '(%)', 'class' => 'small'],
            ],
            'rows' => $rows
        ];
        return $formatted;
    }

    private function getTopCause($name, $causes, $labels)
    {
        if (isset($causes['geo2'][$name])) {
            $data = $causes['geo2'][$name];
            $max = max($data);
            $code = array_search($max, $data);

            return [
                'name' => $labels[$code]['label'],
                'percent' => $max / array_sum($data) * 100.0
            ];
        }
        return ['name' => "", 'percent' => 0];
    }

    private function getServiceAvailability($responses, $qcodes)
    {
        $result = [];
        $lgaResults = [];
        $codes = array_filter(array_keys($qcodes), function($v){
            return substr($v, -1) != 'x';
        });

        foreach ($responses as $response) {
            foreach($codes as $code) {
                if (isset($response[$code]) && $response[$code]) {
                    $result[$response[$code]] = (isset($result[$response[$code]])) ? $result[$response[$code]] + 1 : 1;
                    $lgaResults[$response['GEO2']][$response[$code]] = (isset($lgaResults[$response['GEO2']][$response[$code]])) ? $lgaResults[$response['GEO2']][$response[$code]] + 1 : 1;

                }
            }
        }
        $topPc = array_map(function($v) {$tot=(isset($v['A2']))?$v['A2']:0;$tot+=(isset($v['A3']))?$v['A3']:0; return round($tot / array_sum($v) *100.0,1); }, $lgaResults);
        arsort($topPc);
        $topPc = array_slice($topPc, 0, 5, true);

        return ['all' => $result, 'top' => $topPc];
    }

    private function getCauses($responses, $qcodes)
    {
        $result = [];
        $lgaResults = [];
        $opts = ['[1]','[2]','[3]','[4]','[5]'];
        $codes = array_filter(array_keys($qcodes), function($v){
            return substr($v, -1) == 'x';
        });

        foreach ($responses as $response) {
            foreach($codes as $code) {
                foreach($opts as $opt) {
                    $answer = $code.$opt;
                    if ($response[$answer]) {
                        $result[$response[$answer]] = (isset($result[$response[$answer]])) ? $result[$response[$answer]] + 1 : 1;
                        $lgaResults[$response['GEO2']][$response[$answer]] = (isset($lgaResults[$response['GEO2']][$response[$answer]])) ? $lgaResults[$response['GEO2']][$response[$answer]] + 1 : 1;
                    }
                }
            }
        }
        return ['all' => $result, 'geo2' => $lgaResults];
    }

    private function getGroup($structure, $groupIds)
    {
        $questions = [];
        foreach($groupIds as $groupId) {
            foreach ($structure['groups'] as $group) {
                if ($group['index'] == $groupId) {
                    $questions += $group['questions'];
                    break;
                }
            }
        }
        return $questions;
    }

    private function getFilters()
    {
        if (($json = \Yii::$app->request->get('filters')) != '') {
            $filters = \json_decode($json, true);
            if (!empty($filters['location'])) {
                $filters['location'] = $this->getRegions($filters['location']);
            }
            // Unify the special cases that front sends
            if (!empty($filters['advanced'])) {
                $advanced = [];
                foreach($filters['advanced'] as $filter) {
                    foreach($filter as $code => $opts) {
                        if (strpos($code, '_')) {
                            $parts = explode('_', $code);
                            if (count($parts) == 3) {
                                $code = strstr($code, '_', true).'['.substr($code, -1).']';
                                $opts = explode(',', $opts);
                            } else {
                                $code = $parts[0].'['.$parts[1].']';
                                $opts = ['Y'];
                            }
                        } else {
                            if (!is_array($opts))
                                $opts = explode(',', $opts);
                        }
                        $advanced[$code] = $opts;
                    }
                }

                $filters['advanced'] = $advanced;
            }

            return $filters;
        }
        return [];
    }

    public function getRegions($geoCodes)
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

    public function questionData($responses, $questionCode)
    {
        $result = [];

        foreach ($responses as $response) {
            if ($response[$questionCode])
                $result[$response[$questionCode]] = (isset($result[$response[$questionCode]])) ? $result[$response[$questionCode]]+1 : 1;
            else
                $result["NR"] = (isset($result["NR"])) ? $result["NR"]+1 :  1;
        }
        return $result;
    }

    private function loadStructure($limeSurvey, $cache, $id=742358)
    {
        $cacheKey = "STRUCTURE.$id";

        $survey = $cache->get($cacheKey);
        if ($survey === false) {
            try {
                $survey = SerializeHelper::toArray($limeSurvey->getSurvey($id));
            } catch (\Exception $e) {
                throw new HttpException(404, $e->getMessage());
            }
            $cache->set($cacheKey, $survey, 53600);
        }
        return $survey;
    }

    private function loadResponses(Cache $cache, $id, $filters, $entity = 'project')
    {
        $limitDate = isset($filters['date']) ? Carbon::createFromTimestamp(strtotime($filters['date'])) : new Carbon();
        $cacheKey = 'responses' . $id . $entity . $limitDate->format('Ymd');
        if (false === $responses = $cache->get($cacheKey)) {
            $responses = [];

            switch ($entity) {
                case 'workspace':
                    $data = Workspace::loadOne($id)->getResponses();
                    break;
                case 'project':
                    $data = Project::loadOne($id)->getResponses();
            }

            $responses = $this->prepareData($data, $limitDate);

            $cache->set($cacheKey, $responses, 3600);
        }
        if ($this->hasFilters($filters)) {
            $filtered = [];
            foreach($responses as $response) {
                if ($this->filterResponse($response, $filters))
                    $filtered[] = $response;
            }
            return $filtered;
        }
        return $responses;
    }

    public function prepareData($responses, Carbon $date = null)
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


    public function hasFilters($filters)
    {
        return (!empty($filters['location']) ) ||
        (isset($filters['date']) && $filters['date'] ) ||
        (!empty($filters['hftypes']) ) ||
        (isset($filters['advanced']) && is_array($filters['advanced']) && count($filters['advanced']) > 0);
    }

    private function filterResponse($response, $filters)
    {
        if (!empty($filters['location']) && is_array($filters['location']) && !in_array($response['GEO2'], $filters['location']))
            return false;
        if (!empty($filters['hftypes']) && is_array($filters['hftypes']) && !in_array($response['HF2'], $filters['hftypes']))
            return false;
        if (isset($filters['advanced']) && is_array($filters['advanced'])) {
            foreach ($filters['advanced'] as $code => $opts) {
                if (!empty($opts) && isset($response[$code]) && !in_array($response[$code], $opts))
                    return false;
            }
        }
        return true;
    }

    private function getLabels($id)
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

}
