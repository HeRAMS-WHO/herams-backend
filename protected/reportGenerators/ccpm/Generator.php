<?php

namespace prime\reportGenerators\ccpm;

use prime\factories\GeneratorFactory;
use prime\interfaces\ConfigurableGeneratorInterface;
use prime\interfaces\ProjectInterface;
use prime\interfaces\ReportGeneratorInterface;
use prime\interfaces\ReportInterface;
use prime\interfaces\ResponseCollectionInterface;
use prime\interfaces\SignatureInterface;
use prime\interfaces\SurveyCollectionInterface;
use prime\interfaces\UserDataInterface;
use prime\models\ar\Response;
use prime\models\ar\UserData;
use prime\objects\Report;
use prime\objects\Signature;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use yii\helpers\ArrayHelper;

class Generator extends \prime\reportGenerators\ccpmProgressPercentage\Generator implements ConfigurableGeneratorInterface
{

    public function calculateScore(ResponseCollectionInterface $responses, $map, $method = 'median')
    {
        $values = $this->getGroupedQuestionValues($responses, $map, [$this, 'rangeValidator04']);
        $subResult = [];
        foreach($values as $sId => $rs) {
            foreach($rs as $rId => $rValues) {
                if(!empty($rValues)) {
                    $subResult[] = average($rValues);
                }
            }
        }

        $result = !empty($subResult) ? median($subResult, 2) : 0;
        return $this->map04($result);
    }

    public function calculateDistribution(ResponseCollectionInterface $responses, $map)
    {
        $tempResult = [];

        foreach($map as $surveyId => $questionIds) {
            if(!isset($tempResult[$surveyId])) {
                $tempResult[$surveyId] = [];
            }
            $values = $this->getQuestionValues($responses, [$surveyId => $questionIds], [$this, 'rangeValidatorNonEmpty']);
            foreach($values as $value)
            {
                if(!isset($tempResult[$surveyId][$value])) {
                    $tempResult[$surveyId][$value] = 0;
                }
                $tempResult[$surveyId][$value]++;
            }
        }


        $result = [];
        foreach($tempResult as $surveyId => $values) {
            $result[$surveyId] = [];
            foreach($values as $answer => $count) {
                $result[$surveyId][$answer] = array_sum($values) > 0 ? $count / array_sum($values) : 0;
            }
        }

        return $result;
    }

    

    /**
     * @return string the view path that may be prefixed to a relative view name.
     */
    public function getViewPath()
    {
        return __DIR__ . '/views/';
    }

    public function map04($value)
    {
        return $value * 25;
    }

    public function mapStatus($value)
    {
        $map = [
            25 => 'weak',
            50 => 'unsatisfactory',
            75 => 'satisfactory',
            100 => 'good'
        ];
        foreach($map as $max => $status) {
            if ($value <= $max) {
                return $status;
            }
        }
        return $status;
    }

    protected function rangeValidator04($value)
    {
        return $value != '' && $value >= 0 && $value <= 4;
    }

    protected function rangeValidatorNonEmpty($value)
    {
        return $value != '';
    }

    /**
     * @param ResponseCollectionInterface $responses
     * @param SurveyCollectionInterface $surveys,
     * @param SignatureInterface $signature
     * @param ProjectInterface $project
     * @param UserDataInterface|null $userData
     * @return string
     */
    public function renderConfiguration(
        ResponseCollectionInterface $responses,
        SurveyCollectionInterface $surveys,
        ProjectInterface $project,
        SignatureInterface $signature = null,
        UserDataInterface $userData = null
    ) {
        if (!isset($signature)) {
            $signature = new Signature('', 0, '');
        }
        if (!isset($userData)) {
            $userData = new UserData();
        }
        return $this->view->render('preview', [
            'userData' => $userData,
            'project' => $project,
            'signature' => $signature,
            'responses' => $responses,
            'surveys' => $surveys
        ], $this);
    }

    /**
     * This function renders a report.
     * All responses to be used are given as 1 array of Response objects.
     * @param ResponseCollectionInterface $responses
     * @param SurveyCollectionInterface $surveys
     * @param SignatureInterface $signature
     * @param ProjectInterface $project
     * @param UserDataInterface|null $userData
     * @return ReportInterface
     */
    public function render(
        ResponseCollectionInterface $responses,
        SurveyCollectionInterface $surveys,
        ProjectInterface $project,
        SignatureInterface $signature = null,
        UserDataInterface $userData = null
    ) {
        $stream = \GuzzleHttp\Psr7\stream_for($this->view->render('publish', [
            'userData' => $userData,
            'signature' => $signature,
            'responses' => $responses,
            'project' => $project,
            'surveys' => $surveys
        ], $this));

        return new Report($userData, $signature, $stream, __CLASS__, $this->getReportTitle($project, $signature));
    }

    public function sectionQuestionMapping()
    {
        return [
            '1.1' => [$this->CPASurveyId => ['q111', 'q112', 'q114', 'q118', 'q113', 'q115', 'q119', 'q116', 'q117'], $this->PPASurveyId => ['q111', 'q112', 'q113', 'q114', 'q115', 'q116']],
            '1.1.1' => [$this->CPASurveyId => ['q111'], $this->PPASurveyId => []],
            '1.1.2' => [$this->CPASurveyId => ['q112'], $this->PPASurveyId => ['q111']],
            '1.1.3' => [$this->CPASurveyId => ['q114'], $this->PPASurveyId => ['q112']],
            '1.1.4' => [$this->CPASurveyId => [], $this->PPASurveyId => ['q113']],
            '1.1.5' => [$this->CPASurveyId => ['q118'], $this->PPASurveyId => ['q114']],
            '1.1.6' => [$this->CPASurveyId => ['q113'], $this->PPASurveyId => []],
            '1.1.7' => [$this->CPASurveyId => ['q115'], $this->PPASurveyId => ['q115']],
            '1.1.8' => [$this->CPASurveyId => ['q119'], $this->PPASurveyId => ['q116']],
            '1.1.9' => [$this->CPASurveyId => ['q116'], $this->PPASurveyId => []],
            '1.1.10' => [$this->CPASurveyId => ['q117'], $this->PPASurveyId => []],
            '1.2' => [$this->CPASurveyId => ['q121', 'q122', 'q123'], $this->PPASurveyId => ['q121', 'q122', 'q123']],
            '1.2.1' => [$this->CPASurveyId => ['q121'], $this->PPASurveyId => []],
            '1.2.2' => [$this->CPASurveyId => ['q122'], $this->PPASurveyId => ['q121']],
            '1.2.3' => [$this->CPASurveyId => [], $this->PPASurveyId => ['q122']],
            '1.2.4' => [$this->CPASurveyId => ['q123'], $this->PPASurveyId => ['q123']],
            '2.1' => [$this->CPASurveyId => ['q211', 'q212', 'q213'], $this->PPASurveyId => ['q211', 'q212', 'q213']],
            '2.1.1' => [$this->CPASurveyId => ['q211'], $this->PPASurveyId => ['q211']],
            '2.1.2' => [$this->CPASurveyId => ['q212'], $this->PPASurveyId => ['q212']],
            '2.1.3' => [$this->CPASurveyId => ['q213'], $this->PPASurveyId => ['q213']],
            '2.2' => [$this->CPASurveyId => ['q221', 'q222[1]', 'q222[2]', 'q222[3]', 'q222[4]', 'q222[5]', 'q223[1]', 'q223[2]', 'q223[3]', 'q223[4]', 'q223[5]', 'q223[6]', 'q223[7]', 'q223[8]'], $this->PPASurveyId => ['q221', 'q222[1]', 'q222[2]', 'q222[3]', 'q222[4]', 'q222[5]', 'q223[1]', 'q223[2]', 'q223[3]', 'q223[4]', 'q223[5]', 'q223[6]', 'q223[7]', 'q223[8]']],
            '2.2.1' => [$this->CPASurveyId => ['q221'], $this->PPASurveyId => ['q221']],
            '2.2.2' => [$this->CPASurveyId => ['q222[1]'], $this->PPASurveyId => ['q222[1]']],
            '2.2.3' => [$this->CPASurveyId => ['q222[2]'], $this->PPASurveyId => ['q222[2]']],
            '2.2.4' => [$this->CPASurveyId => ['q222[3]'], $this->PPASurveyId => ['q222[3]']],
            '2.2.5' => [$this->CPASurveyId => ['q222[4]'], $this->PPASurveyId => ['q222[4]']],
            '2.2.6' => [$this->CPASurveyId => ['q222[5]'], $this->PPASurveyId => ['q222[5]']],
            '2.2.7' => [$this->CPASurveyId => ['q223[1]'], $this->PPASurveyId => ['q223[1]']],
            '2.2.8' => [$this->CPASurveyId => ['q223[2]'], $this->PPASurveyId => ['q223[2]']],
            '2.2.9' => [$this->CPASurveyId => ['q223[3]'], $this->PPASurveyId => ['q223[3]']],
            '2.2.10' => [$this->CPASurveyId => ['q223[4]'], $this->PPASurveyId => ['q223[4]']],
            '2.2.11' => [$this->CPASurveyId => ['q223[5]'], $this->PPASurveyId => ['q223[5]']],
            '2.2.12' => [$this->CPASurveyId => ['q223[6]'], $this->PPASurveyId => ['q223[6]']],
            '2.2.13' => [$this->CPASurveyId => ['q223[7]'], $this->PPASurveyId => ['q223[7]']],
            '2.2.14' => [$this->CPASurveyId => ['q223[8]'], $this->PPASurveyId => ['q223[8]']],
            '2.3' => [$this->CPASurveyId => ['q231'], $this->PPASurveyId => ['q231']],
            '2.3.1' => [$this->CPASurveyId => ['q231'], $this->PPASurveyId => ['q231']],
            '3.1' => [$this->CPASurveyId => ['q311', 'q314', 'q312', 'q313', 'q315[1]', 'q315[2]', 'q315[3]', 'q315[4]', 'q315[5]', 'q315[6]', 'q315[7]', 'q315[8]', 'q316', 'q317', 'q318'], $this->PPASurveyId => ['q311', 'q312']],
            '3.1.1' => [$this->CPASurveyId => ['q311'], $this->PPASurveyId => []],
            '3.1.2' => [$this->CPASurveyId => ['q314'], $this->PPASurveyId => ['q311']],
            '3.1.3' => [$this->CPASurveyId => ['q312'], $this->PPASurveyId => []],
            '3.1.4' => [$this->CPASurveyId => ['q313'], $this->PPASurveyId => []],
            '3.1.5' => [$this->CPASurveyId => ['q315[1]'], $this->PPASurveyId => []],
            '3.1.6' => [$this->CPASurveyId => ['q315[2]'], $this->PPASurveyId => []],
            '3.1.7' => [$this->CPASurveyId => ['q315[3]'], $this->PPASurveyId => []],
            '3.1.8' => [$this->CPASurveyId => ['q315[4]'], $this->PPASurveyId => []],
            '3.1.9' => [$this->CPASurveyId => ['q315[5]'], $this->PPASurveyId => []],
            '3.1.10' => [$this->CPASurveyId => ['q315[6]'], $this->PPASurveyId => []],
            '3.1.11' => [$this->CPASurveyId => ['q315[7]'], $this->PPASurveyId => []],
            '3.1.12' => [$this->CPASurveyId => ['q315[8]'], $this->PPASurveyId => []],
            '3.1.13' => [$this->CPASurveyId => ['q316'], $this->PPASurveyId => []],
            '3.1.14' => [$this->CPASurveyId => ['q317'], $this->PPASurveyId => ['q312']],
            '3.1.15' => [$this->CPASurveyId => ['q318'], $this->PPASurveyId => []],
            '3.2' => [$this->CPASurveyId => ['q321', 'q322'], $this->PPASurveyId => ['q321']],
            '3.2.1' => [$this->CPASurveyId => ['q321'], $this->PPASurveyId => []],
            '3.2.2' => [$this->CPASurveyId => ['q322'], $this->PPASurveyId => ['q321']],
            '3.3' => [$this->CPASurveyId => ['q331', 'q332', 'q333', 'q334'], $this->PPASurveyId => ['q331', 'q332', 'q333']],
            '3.3.1' => [$this->CPASurveyId => ['q331'], $this->PPASurveyId => ['q331']],
            '3.3.2' => [$this->CPASurveyId => ['q332'], $this->PPASurveyId => ['q332']],
            '3.3.3' => [$this->CPASurveyId => ['q333'], $this->PPASurveyId => []],
            '3.3.4' => [$this->CPASurveyId => ['q334'], $this->PPASurveyId => ['q333']],
            '4.1' => [$this->CPASurveyId => ['q411'], $this->PPASurveyId => ['q411']],
            '4.1.1' => [$this->CPASurveyId => ['q411'], $this->PPASurveyId => ['q411']],
            '4.2' => [$this->CPASurveyId => ['q421'], $this->PPASurveyId => ['q421']],
            '4.2.1' => [$this->CPASurveyId => ['q421'], $this->PPASurveyId => ['q421']],
            '5' => [$this->CPASurveyId => ['q51', 'q52', 'q53', 'q54', 'q55', 'q56'], $this->PPASurveyId => ['q51', 'q52', 'q53']],
            '5.1.1' => [$this->CPASurveyId => ['q51'], $this->PPASurveyId => ['q52']],
            '5.1.2' => [$this->CPASurveyId => ['q52'], $this->PPASurveyId => []],
            '5.1.3' => [$this->CPASurveyId => ['q53'], $this->PPASurveyId => []],
            '5.1.4' => [$this->CPASurveyId => ['q54'], $this->PPASurveyId => []],
            '5.1.5' => [$this->CPASurveyId => ['q55'], $this->PPASurveyId => ['q51']],
            '5.1.6' => [$this->CPASurveyId => ['q56'], $this->PPASurveyId => ['q53']],
            '6' => [$this->CPASurveyId => ['q61', 'q62', 'q63', 'q64', 'q65', 'q66'], $this->PPASurveyId => ['q61', 'q62']],
            '6.1.1' => [$this->CPASurveyId => ['q61'], $this->PPASurveyId => []],
            '6.1.2' => [$this->CPASurveyId => ['q62'], $this->PPASurveyId => []],
            '6.1.3' => [$this->CPASurveyId => ['q63'], $this->PPASurveyId => ['q61']],
            '6.1.4' => [$this->CPASurveyId => ['q64'], $this->PPASurveyId => ['q62']],
            '6.1.5' => [$this->CPASurveyId => ['q65'], $this->PPASurveyId => []],
            '7' => [$this->CPASurveyId => ['q71', 'q72'], $this->PPASurveyId => ['q71', 'q72']],
            '7.1.1' => [$this->CPASurveyId => ['q71'], $this->PPASurveyId => ['q71']],
            '7.1.2' => [$this->CPASurveyId => ['q72'], $this->PPASurveyId => ['q72']],
        ];
    }

    /**
     * Returns the title of the Report
     * @return string
     */
    public static function title()
    {
        return \Yii::t('ccpm', 'CCPM');
    }
}