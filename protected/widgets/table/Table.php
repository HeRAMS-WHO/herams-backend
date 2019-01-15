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

    /** @var HeramsResponse[] $data */
    public $data = [];

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
        $reasonMap = $this->getAnswers($this->reasonCode );
        $result = [];
        /** @var HeramsResponse $response */
        foreach($this->data as $response) {
            $location = $response->getLocation();
            if (empty($location)) {
                continue;
            }
            $value = $response->getValueForCode($this->code);
            if (empty($value)) {
                continue;
            }

            if ($value === 'A1') {
                $key = 'FUNCTIONAL';
            } else {
                $key = 'NONFUNCTIONAL';
                $reasons = $response->getValueForCode($this->reasonCode) ?? [];
                foreach($reasons as $reason) {
                    $result[$location]['reasons'][$reason] = ($result[$location]['reasons'][$reason] ?? 0) + 1;
                }
            }
            $result[$location]['counts'][$key] = ($result[$location]['counts'][$key] ?? 0) + 1;
            $result[$location]['counts']['TOTAL'] = ($result[$location]['counts']['TOTAL'] ?? 0) + 1;
        }

        // Todo: SORT
        uasort($result, function($a, $b) {
            $percentageA = 1.0 * ($a['counts']['FUNCTIONAL'] ?? 0) / $a['counts']['TOTAL'];
            $percentageB = 1.0 * ($b['counts']['FUNCTIONAL'] ?? 0) / $b['counts']['TOTAL'];
            return ($percentageA <=> $percentageB);
        });

        foreach(array_slice($result, 0, 5) as $location => $data) {
            $reasons = $data['reasons'] ?? [];
            arsort($reasons);
            $total = array_sum($reasons);
            yield [
                $location,
                number_format(100.0 * ($data['counts']['FUNCTIONAL'] ?? 0) / $data['counts']['TOTAL'], 2),
                empty($reasons) ? 'Unknown' : $reasonMap[array_keys($reasons)[0]],
                empty($reasons) ? 'Unknown' : (100.0 * array_values($reasons)[0] / $total)
            ];
        }
        return;
        echo '<pre>';
        var_dump(array_slice($result, 0, 5)); die();
        return array_slice($result, 0, 5);
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

    protected function renderTableHead()
    {
        echo Html::beginTag('thead');
        echo Html::beginTag('tr');
        echo Html::tag('th', 'Name');
        echo Html::tag('th', 'Availability (%)');
        echo Html::tag('th', 'Main problem');
        echo Html::tag('th', 'Main cause (%)');
        echo Html::endTag('tr');
        echo Html::endTag('thead');
    }
}