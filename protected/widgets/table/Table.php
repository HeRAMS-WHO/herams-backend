<?php


namespace prime\widgets\table;


use prime\interfaces\HeramsResponseInterface;
use prime\objects\HeramsSubject;
use prime\traits\SurveyHelper;
use prime\widgets\element\Element;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\helpers\Html;

class Table extends Element
{
    use SurveyHelper;

    public $options = [
        'class' => 'table'
    ];

    /** @var string */
    public $code;

    public $reasonCode;

    public $reasonMap;

    /** @var HeramsResponseInterface[] $data */
    public $data = [];

    public $groupCode;

    public $columnNames = [
        'Name',
        'Availability (%)',
        'Main problem',
        'Main cause (%)'
    ];

    public $title;

    public function __construct(\prime\models\ar\Element $element, $config = [])
    {
        parent::__construct($element, $config);
        $this->columnNames[0] = $this->getTitleFromCode($this->groupCode);
    }

    public function run()
    {
        echo Html::tag('h1', $this->title ?? $this->getTitleFromCode($this->code));
        $this->renderTable();
        parent::run();
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
        } catch (\InvalidArgumentException $e) {
            $getter = 'get'. ucfirst($this->reasonCode);
            $reasonGetter = function($response) use ($getter):array {
                return $response->$getter();
            };
        }

        $reasonMap = $this->reasonMap ?? $this->getAnswers($this->reasonCode);
        
        $result = [];
        /** @var HeramsResponseInterface $response */
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

            if ($value === HeramsSubject::FULLY_AVAILABLE) {
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
        $groupMap = $this->getAnswers($this->groupCode);
        foreach(array_slice($result, 0, 5, true) as $group => $data) {
            $reasons = $data['reasons'] ?? [];
            arsort($reasons);
            $total = array_sum($reasons);
            yield [
                $groupMap[$group] ?? $group,
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

    /**
     * @param HeramsSubject|HeramsResponseInterface $data
     * @return string|null
     */
    private function getGroup($data): ?string
    {
        return $data->getValueForCode($this->groupCode);
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