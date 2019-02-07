<?php


namespace prime\widgets\table;


use prime\objects\HeramsResponse;
use prime\traits\SurveyHelper;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\Widget;
use yii\helpers\Html;

class Table extends Widget
{
    use SurveyHelper;

    /** @var SurveyInterface */
    public $survey;

    /** @var string */
    public $code;

    public $reasonCode;

    public $reasonMap;

    /** @var HeramsResponse[] $data */
    public $data = [];

    public $groupCode = 'location';

    public $columnNames = [
        'Name',
        'Availability (%)',
        'Main problem',
        'Main cause (%)'
    ];

    public function run()
    {
        echo Html::beginTag('div', ['class' => ['table']]);
        echo Html::tag('h1', $this->getTitle());
        $this->renderTable();
        echo Html::endTag('div');
    }

    protected function renderTable()
    {
        echo Html::beginTag('table');
        $this->renderTableHead();
        $this->renderTableBody();
        echo Html::endTag('table');
    }

    protected function getRows(): iterable
    {
        try {
            $question = $this->findQuestionByCode($this->code);
            $valueGetter = function($response) {
                return $response->getValueForCode($this->code) ?? [];
            };
        } catch (\InvalidArgumentException $e) {
            // Question doesn't exist, we should use getter to retrieve values.
            $valueGetter = function($response) {
                $getter = 'get'. ucfirst($this->code);
                return $response->$getter() ?? [];
            };
        }

        try {
            $this->findQuestionByCode($this->reasonCode);
            $reasonGetter = function($response): array {
                return $response->getValueForCode($this->reasonCode) ?? [];
            };
            $reasonMap = $this->reasonMap ?? $this->getAnswers($this->reasonCode);
        } catch (\InvalidArgumentException $e) {
            $getter = 'get'. ucfirst($this->reasonCode);
            $reasonGetter = function($response) use ($getter):array {
                return $response->$getter();
            };
            $reasonMap = $this->reasonMap;
        }

        $result = [];
        /** @var HeramsResponse $response */
        \Yii::beginProfile(__CLASS__ . 'count');
        foreach($this->data as $response) {
            $group = $this->getGroup($response);
            if (empty($group)) {
                continue;
            }

            $value = $valueGetter($response);
            if (empty($value)) {
                continue;
            }

            if ($value === 'A1') {
                $key = 'FUNCTIONAL';
            } else {
                $key = 'NONFUNCTIONAL';
                foreach($reasonGetter($response) as $reason) {
                    $result[$group]['reasons'][$reason] = ($result[$group]['reasons'][$reason] ?? 0) + 1;
                }
            }
            $result[$group]['counts'][$key] = ($result[$group]['counts'][$key] ?? 0) + 1;
            $result[$group]['counts']['TOTAL'] = ($result[$group]['counts']['TOTAL'] ?? 0) + 1;
        }

        \Yii::endProfile(__CLASS__ . 'count');
        // Todo: SORT
        uasort($result, function($a, $b) {
            $percentageA = 1.0 * ($a['counts']['FUNCTIONAL'] ?? 0) / $a['counts']['TOTAL'];
            $percentageB = 1.0 * ($b['counts']['FUNCTIONAL'] ?? 0) / $b['counts']['TOTAL'];
            return ($percentageA <=> $percentageB);
        });
        foreach(array_slice($result, 0, 5) as $group => $data) {
            $reasons = $data['reasons'] ?? [];
            arsort($reasons);
            $total = array_sum($reasons);
            yield [
                $group,
                number_format(100.0 * ($data['counts']['FUNCTIONAL'] ?? 0) / $data['counts']['TOTAL'], 0),
                empty($reasons) ? 'Unknown' : $reasonMap[array_keys($reasons)[0]] ?? array_keys($reasons)[0],
                empty($reasons) ? 'Unknown' : number_format(100.0 * array_values($reasons)[0] / $total, 0)
            ];
        }
    }

    protected function renderTableBody() {
        echo Html::beginTag('tbody');
        foreach($this->getRows() as $row) {
            echo Html::beginTag('tr');
            foreach($row as $cell) {
                echo Html::tag('td', $cell);
            }
            echo Html::endTag('tr');
        }
        echo Html::beginTag('tbody');
    }

    private $groupCodeIsQuestion;

    private function getGroup($data): ?string
    {
        if (!isset($this->groupCodeIsQuestion)) {
            try {
                $this->findQuestionByCode($this->groupCode);
                $this->groupCodeIsQuestion = true;


            } catch (\InvalidArgumentException $e) {
                $this->groupCodeIsQuestion = false;

            }
        }

        if ($this->groupCodeIsQuestion) {
            return $data->getValueForCode($this->groupCode);
        } else {
            $getter = 'get' . ucfirst($this->groupCode);
            return $data->$getter();
        }


    }

    protected function renderTableHead()
    {
        echo Html::beginTag('thead');
        echo Html::beginTag('tr');
        foreach($this->columnNames as $columnName)
        {
            echo Html::tag('th', $columnName);
        }
        echo Html::endTag('tr');
        echo Html::endTag('thead');
    }
}