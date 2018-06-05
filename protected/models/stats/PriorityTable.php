<?php

namespace app\models\Stats;

use app\models\Overview;


class PriorityTable extends Overview
{
    /**
     * Compile a Priority LGA table for a question and cause.
     */
    public static function makePriorityTable($id, $responses, $qCode, $causeCode, $title)
    {
        $rows = [];

        $topRegions = self::topRegions($responses, $qCode);
        $causes = self::causesForRegions($responses, $causeCode);

        foreach ($topRegions as $name => $percent) {
            $cause = self::getTopCause($name, $causes, self::indicatorLabels($id));
            $row = [
                'name' => $name,
                'unavailability' => $percent,
                'cause' => $cause['name'],
                'cause_pc' => round($cause['percent'], 1),
            ];
            $rows[] = $row;
        }
        return self::formatTable($rows, $title);
    }

    public static function formatTable($rows, $title)
    {
        $formatted = [
            'name' => \Yii::t('app' , $title),
            'answers' => 140,
            'type' => 'table',
            'columns' => [
                ['label' => 'Name', 'class' => 'wide'],
                ['label' => 'Dysfunctionality level (%)', 'class' => 'small'],
                ['label' => 'Main cause', 'class' => 'wide'],
                ['label' => '(%)', 'class' => 'small'],
            ],
            'rows' => $rows
        ];

        return $formatted;
    }

    public static function topRegions($responses, $code)
    {
        $lgaResults = [];

        foreach ($responses as $response) {
            if ($response[$code]) {
                $lgaResults[$response['GEO2']][$response[$code]] = (isset($lgaResults[$response['GEO2']][$response[$code]])) ? $lgaResults[$response['GEO2']][$response[$code]] + 1 : 1;
            }
        }
        $topPc = array_map(function($v) {
            $tot=(isset($v['A2']))?$v['A2']:0;$tot+=(isset($v['A3']))?$v['A3']:0;
            return round($tot / array_sum($v) *100.0,1);
        }, $lgaResults);
        arsort($topPc);
        $topPc = array_slice($topPc, 0, 5, true);

        return $topPc;
    }

    public static function causesForRegions($responses, $code)
    {
        $lgaResults = [];

        foreach ($responses as $response) {
            if ($response[$code]) {
                $lgaResults[$response['GEO2']][$response[$code]] = (isset($lgaResults[$response['GEO2']][$response[$code]])) ? $lgaResults[$response['GEO2']][$response[$code]] + 1 : 1;
            }
        }
        return $lgaResults;
    }

    private static function getTopCause($name, $causes, $labels)
    {
        if (isset($causes[$name])) {
            $data = $causes[$name];
            $max = max($data);
            $code = array_search($max, $data);
            $cause = $labels[$code]['label'];

            return [
                'name' => $cause,
                'percent' => $max / array_sum($data) * 100.0
            ];
        } elseif ($name != "") {
           return [
                'name' => 'Unspecified',
                'percent' => 100.0
            ];
        }
        return ['name' => "", 'percent' => 0];
    }
}

