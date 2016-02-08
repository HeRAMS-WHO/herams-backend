<?php

namespace prime\reportGenerators\cdProgress;

use prime\interfaces\ProjectInterface;
use prime\interfaces\ReportGeneratorInterface;
use prime\interfaces\ReportInterface;
use prime\interfaces\ResponseCollectionInterface;
use prime\interfaces\SignatureInterface;
use prime\interfaces\SurveyCollectionInterface;
use prime\interfaces\UserDataInterface;
use prime\models\ar\UserData;
use prime\objects\Report;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;

class Generator extends \prime\reportGenerators\ccpm\Generator implements ReportGeneratorInterface
{
    /** @var ResponseInterface */
    protected $response;

    public function getProgresses(ResponseCollectionInterface $responses)
    {
        $requiredAnswers = [
            \Yii::t('cd', 'Establishment of the cluster') => [
                'q11',
                'q13',
                'q14',
                'q16',
            ],
            \Yii::t('cd', 'Cluster coordinator') => array_merge([
                'q21', //if q21 == 2, q26, q27, q28, q29
                'q22',
                'q23',
                'q24',
                'q25',
            ], $this->getQuestionValue('q21') == 2 ? ['q26', 'q27', 'q28', 'q29'] : []),
            \Yii::t('cd', 'Cluster management') => array_merge(
                [
                    'q31', //if q31 > 1, foreach 3 questions: q310 + (i - 1) * 3 + (1, 2, 3)
                    'q326',
                    'q327',
                    'q328',
                    'q329', //if q329 > 0, foreach 3 questions: q329 + (i - 1) * 3 + (1, 2, 3)
                    'q345[1]',
                    'q345[2]',
                    'q345[3]',
                    'q345[4]',
                    'q345[5]',
                    'q345[6]',
                    'q345[7]',
                    'q345[8]',
                    'q346[1]',
                    'q346[2]',
                    'q346[3]',
                    'q346[4]',
                    'q346[5]',
                ],
                $this->getQuestionValue('q31') >= 2 ? ['q311', 'q312', 'q313'] : [],
                $this->getQuestionValue('q31') >= 3 ? ['q314', 'q315', 'q316'] : [],
                $this->getQuestionValue('q31') >= 4 ? ['q317', 'q318', 'q319'] : [],
                $this->getQuestionValue('q31') >= 5 ? ['q320', 'q321', 'q322'] : [],
                $this->getQuestionValue('q31') >= 6 ? ['q323', 'q324', 'q325'] : [],
                $this->getQuestionValue('q329') >= 1 ? ['q330', 'q331', 'q332'] : [],
                $this->getQuestionValue('q329') >= 2 ? ['q333', 'q334', 'q335'] : [],
                $this->getQuestionValue('q329') >= 3 ? ['q336', 'q337', 'q338'] : [],
                $this->getQuestionValue('q329') >= 4 ? ['q339', 'q340', 'q341'] : [],
                $this->getQuestionValue('q329') >= 5 ? ['q342', 'q343', 'q344'] : []
            ),
            \Yii::t('cd', 'Cluster participants') => [
                'q41[1]',
                'q41[2]',
                'q41[3]',
                'q41[4]',
                'q41[5]',
                'q41[6]',
                'q42'
            ],
            \Yii::t('cd', 'Deliverable') => [
                'q51[1]',
                'q51[2]',
                'q51[3]',
                'q51[4]',
                'q51[5]',
                'q51[6]',
                'q51[7]',
                'q51[8]',
                'q51[9]',
                'q51[10]',
                'q51[11]',
                'q51[12]',
                'q51[13]',
                'q51[14]',
                'q51[15]',
                'q51[16]',
                'q51[17]',
                'q51[18]',
                'q51[19]',
                'q51[20]',
                'q51[21]',
            ],
            \Yii::t('cd', 'Communication') => [
                'q61[1]',
                'q61[2]',
                'q61[3]',
                'q61[4]'
            ]
        ];

        $result = [];
        $i = false;
        foreach($requiredAnswers as $category => $qTitles) {
            $result[$category] = $i;
            $i = !$i;
//            $result = [];
//            foreach($qTitles as $qTitle) {
//                $result[$qTitle] = $this->getQuestionValue($qTitle);
//            }
//            $requiredAnswers[$category] = $result;
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

    public function getQuestionValue($title)
    {
        return isset($this->response->getData()[$title]) ? $this->response->getData()[$title] : null;
    }

    protected  function initResponses(ResponseCollectionInterface $responses)
    {
        $responses = $responses->sort(function(ResponseInterface $r1, ResponseInterface $r2) {
            // Reverse ordered
            return -1 * strcmp($r1->getId(), $r2->getId());
        });

        // Get the first element, we know the collection is traversable.
        foreach($responses as $key => $response) {
            $this->response = $response;
            break;
        }
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
        $this->initResponses($responses);
        $stream = \GuzzleHttp\Psr7\stream_for($this->view->render('publish', [
            'userData' => $userData,
            'signature' => $signature,
            'responses' => $responses,
            'project' => $project,
            'progresses' => $this->getProgresses($responses)
        ], $this));

        $userData = new UserData();
        return new Report($userData, $signature, $stream, $this->className(), $this->getReportTitle($project, $signature));
    }

    /**
     * @param ResponseCollectionInterface $responses
     * @param SurveyCollectionInterface $surveys
     * @param SignatureInterface $signature
     * @param ProjectInterface $project
     * @param UserDataInterface|null $userData
     * @return string
     */
//    public function renderPreview(
//        ResponseCollectionInterface $responses,
//        SurveyCollectionInterface $surveys,
//        ProjectInterface $project,
//        SignatureInterface $signature = null,
//        UserDataInterface $userData = null
//    ) {
//        return $this->view->render('publish', [
//            'progresses' => $this->getProgresses($responses)
//        ], $this);
//    }

    /**
     * Returns the title of the Report
     * @return string
     */
    public static function title()
    {
        return \Yii::t('app', 'CD Progress');
    }





}