<?php

namespace app\components;

class ProfilingPanel extends \yii\debug\panels\ProfilingPanel {
    private $_models;
    protected function getModels() {
        if (!isset($this->_models) && isset($this->data['messages'])) {
            $timings = Yii::getLogger()->calculateTimings($this->data['messages']);
            $durations = [];
            foreach($timings as $timing) {
                if (isset($durations[$timing['info']])) {
                    $durations[$timing['info']]['duration'] += $timing['duration'];
                    $durations[$timing['info']]['count'] ++;
                } else {
                    $durations[$timing['info']] = [
                        'duration' => $timing['duration'],
                        'count' => 1
                    ];
                }
            }
            foreach($durations as $info => $data) {
                $this->_models[] = [
                    'info' => $info,
                    'duration' => $data['duration'] * 1000,
                    'category' => "Repeated: " . str_pad($data['count'], 5, '0', STR_PAD_LEFT),
                    'timestamp' => 0,
                    'level' => LOG_DEBUG
                    
                ];
            }
            
//             'duration' => $profileTiming['duration'] * 1000, // in milliseconds
//                    'category' => $profileTiming['category'],
//                    'info' => $profileTiming['info'],
//                    'level' => $profileTiming['level'],
//                    'timestamp' => $profileTiming['timestamp'] * 1000, //in milliseconds
//                    'seq' => $seq,
//            var_dump($timings);
//            die();
        }
        return $this->_models;
        
        
    }
}
