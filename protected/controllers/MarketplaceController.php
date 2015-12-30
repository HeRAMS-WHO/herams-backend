<?php

namespace prime\controllers;

use Carbon\Carbon;
use prime\components\Controller;
use prime\factories\MapLayerFactory;
use prime\models\ar\Project;
use prime\models\Country;
use prime\models\search\Report;
use prime\objects\ResponseCollection;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use SamIT\LimeSurvey\JsonRpc\Client;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\Request;

class MarketplaceController extends Controller
{
    public static $surveyIds = [
        'countryGrades' => 486496,
        'eventGrades' => 473297,
        'healthClusters' => 259688
    ];

    public function actionIndex()
    {
        return $this->redirect(['/marketplace/map']);
    }

    public function actionMap(Client $limesurvey)
    {
        //TODO: Survey ids in settings
        $mapLayerData = [
            'projects' => Project::find()->notClosed(),
            'countryGrades' => new ResponseCollection($limesurvey->getResponses(self::$surveyIds['countryGrades'])),
            'eventGrades' => new ResponseCollection($limesurvey->getResponses(self::$surveyIds['eventGrades'])),
            'healthClusters' => new ResponseCollection($limesurvey->getResponses(self::$surveyIds['healthClusters']))
        ];

        //Get active

        return $this->render('map', ['mapLayerData' => $mapLayerData, 'countries' => []]);
    }

    public function actionSummary(Request $request, Client $limesurvey, $iso_3, $layer, $id = null, $noMenu = false)
    {
        if($noMenu) {
            $this->view->params['hideMenu'] = true;
        }

        $country = Country::findOne($iso_3);
        //get projects data provider for projects tab
        $projectsDataProvider = new ActiveDataProvider([
            'query' => Project::find()->notClosed()->andWhere(['country_iso_3' => $country->iso_3])
        ]);

        //retrieve reponses for country grading tab
        $gradingResponses = [];
        foreach($limesurvey->getResponses(self::$surveyIds['countryGrades']) as $response) {
            if ($response->getData()['PRIMEID'] == $country->iso_3) {
                $gradingResponses[] = $response;
            }
        }
        usort($gradingResponses, function($a, $b){
            /**
             * @var ResponseInterface $a
             * @var ResponseInterface $b
             */
            $aD = new Carbon($a->getData()['GM01']);
            $bD = new Carbon($b->getData()['GM01']);
            if($aD->eq($bD)) {
                return ($a->getId() > $b->getId()) ? 1 : -1;
            }
            return ($aD->gt($bD)) ? 1 : -1;
        });

        //retrieve responses for events tab
        $eventsResponses = [];
        foreach($limesurvey->getResponses(self::$surveyIds['eventGrades']) as $response) {
            if ($response->getData()['PRIMEID'] == $country->iso_3) {
                $eventId = $response->getData()['UOID'];
                if(!isset($eventsResponses[$eventId])) {
                    $eventsResponses[$eventId] = [];
                }
                $eventsResponses[$eventId][] = $response;
            }
        }

        foreach($eventsResponses as &$eventResponses) {
            usort($eventResponses, function($a, $b){
                /**
                 * @var ResponseInterface $a
                 * @var ResponseInterface $b
                 */
                $aD = new Carbon($a->getData()['GM01']);
                $bD = new Carbon($b->getData()['GM01']);
                if($aD->eq($bD)) {
                    return ($a->getId() > $b->getId()) ? 1 : -1;
                }
                return ($aD->gt($bD)) ? 1 : -1;
            });
        }

        //retrieve responses for health clusters tab
        $healthClustersResponses = [];
        foreach($limesurvey->getResponses(self::$surveyIds['healthClusters']) as $response) {
            if ($response->getData()['PRIMEID'] == $country->iso_3) {
                $hcId = $response->getData()['UOID'];
                if(!isset($eventsResponses[$hcId])) {
                    $healthClustersResponses[$hcId] = [];
                }
                $healthClustersResponses[$hcId][] = $response;
            }
        }

        foreach($healthClustersResponses as &$healthClusterResponses) {
            usort($healthClusterResponses, function($a, $b){
                /**
                 * @var ResponseInterface $a
                 * @var ResponseInterface $b
                 */
                $aD = new Carbon($a->getData()['CM03']);
                $bD = new Carbon($b->getData()['CM03']);
                if($aD->eq($bD)) {
                    return ($a->getId() > $b->getId()) ? 1 : -1;
                }
                return ($aD->gt($bD)) ? 1 : -1;
            });
        }

        return $this->render('../dashboards/country', [
            'country' => $country,
            'projectsDataProvider' => $projectsDataProvider,
            'gradingResponses' => $gradingResponses,
            'eventsResponses' => $eventsResponses,
            'healthClustersResponses' => $healthClustersResponses,
            'layer' => $layer
        ]);
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                'access' => [
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@']
                        ],
                    ]
                ]
            ]
        );
    }
}