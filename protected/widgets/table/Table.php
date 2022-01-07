<?php

declare(strict_types=1);

namespace prime\widgets\table;

use prime\interfaces\HeramsResponseInterface;
use prime\objects\HeramsSubject;
use prime\traits\SurveyHelper;
use prime\widgets\element\Element;
use yii\helpers\Html;

class Table extends Element
{
    use SurveyHelper;

    public $options = [
        'class' => 'table'
    ];

    public string $code;

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

    public function run(): string
    {
        $parent = substr(parent::run(), 0, -6);
        $parent .= Html::tag('h1', $this->title ?? $this->getTitleFromCode($this->code));
        $parent .= $this->renderTable();
        return $parent . '</div>';
    }

    private function renderTable(): string
    {
        return Html::beginTag('table')
            . $this->renderTableHead()
            . $this->renderTableBody()
            . Html::endTag('table');
    }

    protected function getRows(): iterable
    {
        try {
            $question = $this->findQuestionByCode($this->code);
            $valueGetter = function ($response) {
                return $response->getValueForCode($this->code) ?? [];
            };
        } catch (\InvalidArgumentException $e) {
            // Question doesn't exist, we should use getter to retrieve values.
            $valueGetter = function ($response) {
                $getter = 'get' . ucfirst($this->code);
                return $response->$getter() ?? [];
            };
        }

        try {
            $this->findQuestionByCode($this->reasonCode);
            $reasonGetter = function ($response): array {
                return $response->getValueForCode($this->reasonCode) ?? [];
            };
        } catch (\InvalidArgumentException $e) {
            $getter = 'get' . ucfirst($this->reasonCode);
            $reasonGetter = function ($response) use ($getter): array {
                return $response->$getter();
            };
        }

        $reasonMap = $this->reasonMap ?? $this->getAnswers($this->reasonCode);

        $result = [];
        \Yii::beginProfile(__CLASS__ . 'count');
        foreach ($this->data as $response) {
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
                foreach ($reasonGetter($response) as $reason) {
                    $result[$group]['reasons'][$reason] = ($result[$group]['reasons'][$reason] ?? 0) + 1;
                }
            }
            $result[$group]['counts'][$key] = ($result[$group]['counts'][$key] ?? 0) + 1;
            $result[$group]['counts']['TOTAL'] = ($result[$group]['counts']['TOTAL'] ?? 0) + 1;
        }

        \Yii::endProfile(__CLASS__ . 'count');
        // Todo: SORT
        uasort($result, function ($a, $b) {
            $percentageA = 1.0 * ($a['counts']['FUNCTIONAL'] ?? 0) / $a['counts']['TOTAL'];
            $percentageB = 1.0 * ($b['counts']['FUNCTIONAL'] ?? 0) / $b['counts']['TOTAL'];
            return ($percentageA <=> $percentageB);
        });
        $groupMap = $this->getAnswers($this->groupCode);
        foreach (array_slice($result, 0, 5, true) as $group => $data) {
            $reasons = $data['reasons'] ?? [];
            arsort($reasons);
            $total = array_sum($reasons);
            yield [
                $groupMap[$group] ?? $group,
                number_format(100.0 * ($data['counts']['FUNCTIONAL'] ?? 0) / $data['counts']['TOTAL'], 0),
                /** @phpstan-ignore-next-line */
                empty($reasons) ? 'Unknown' : $reasonMap[array_keys($reasons)[0]] ?? array_keys($reasons)[0],
                /** @phpstan-ignore-next-line */
                empty($reasons) ? 'Unknown' : number_format(100.0 * array_values($reasons)[0] / $total, 0)
            ];
        }
    }

    private function renderTableBody(): string
    {
        $result = Html::beginTag('tbody');
        foreach ($this->getRows() as $row) {
            $result .= Html::beginTag('tr');
            foreach ($row as $cell) {
                $result .= Html::tag('td', $cell);
            }
            $result .= Html::endTag('tr');
        }
        $result .= Html::beginTag('tbody');
        return $result;
    }

    /**
     * @param HeramsSubject|HeramsResponseInterface $data
     * @return string|null
     */
    private function getGroup($data): ?string
    {
        return $data->getValueForCode($this->groupCode);
    }

    private function renderTableHead(): string
    {
        $result = Html::beginTag('thead');
        $result .= Html::beginTag('tr');
        foreach ($this->columnNames as $columnName) {
            $result .= Html::tag('th', $columnName);
        }
        $result .= Html::endTag('tr');
        $result .= Html::endTag('thead');
        return $result;
    }
}
