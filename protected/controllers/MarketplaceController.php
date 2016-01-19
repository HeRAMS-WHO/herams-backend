<?php

namespace prime\controllers;

use Carbon\Carbon;
use prime\components\Controller;
use prime\factories\MapLayerFactory;
use prime\models\ar\Project;
use prime\models\ar\Setting;
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
    public function actionIndex()
    {
        return $this->redirect(['/marketplace/map']);
    }

    public function actionMap(Client $limeSurvey)
    {
        //TODO: Survey ids in settings
        $mapLayerData = [
            'projects' => Project::find()->notClosed(),
            'countryGrades' => new ResponseCollection($limeSurvey->getResponses(Setting::get('countryGradesSurvey'))),
            'eventGrades' => new ResponseCollection($limeSurvey->getResponses(Setting::get('eventGradesSurvey'))),
            'healthClusters' => new ResponseCollection($limeSurvey->getResponses(Setting::get('healthClusterMappingSurvey')))
        ];

        //Get active

        return $this->render('map', ['mapLayerData' => $mapLayerData, 'countries' => []]);
    }

    /**
     * @todo Refactor this; global dashboard(s) should have separate action.
     * @param Request $request
     * @param Client $limeSurvey
     * @param null $iso_3
     * @param $layer
     * @param null $id
     * @param bool|false $noMenu
     * @return string
     */
    public function actionSummary(Request $request, Client $limeSurvey, $iso_3 = null, $layer, $id = null, $noMenu = false)
    {
        if($noMenu) {
            $this->view->params['hideMenu'] = true;
            $this->view->params['containerOptions']['class'][] = 'container-fluid';
        }

        $country = Country::findOne($iso_3);
        //get projects data provider for projects tab
        $projectsDataProvider = new ActiveDataProvider([
            'query' => Project::find()->notClosed()->andWhere(isset($country) ? ['country_iso_3' => $country->iso_3] : [])
        ]);

        //retrieve reponses for country grading tab
        $countriesResponses = [];
        foreach($limeSurvey->getResponses(Setting::get('countryGradesSurvey')) as $response) {
            if (!isset($country) || $response->getData()['PRIMEID'] == $country->iso_3) {
                if(!isset($countriesResponses[$response->getData()['PRIMEID']])) {
                    $countriesResponses[$response->getData()['PRIMEID']] = [];
                }
                $countriesResponses[$response->getData()['PRIMEID']][] = $response;
            }
        }

        foreach($countriesResponses as &$countryResponses) {
            usort($countryResponses, function($a, $b){
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

        //retrieve responses for events tab
        $eventsResponses = [];
        foreach($limeSurvey->getResponses(Setting::get('eventGradesSurvey')) as $response) {
            if (!isset($country) || $response->getData()['PRIMEID'] == $country->iso_3) {
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
        foreach($limeSurvey->getResponses(Setting::get('healthClusterMappingSurvey')) as $response) {
            if (!isset($country) || $response->getData()['PRIMEID'] == $country->iso_3) {
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
        //render dashboard
        if(isset($country)) {
            return $this->render(
                '/dashboards/country',
                [
                    'country' => $country,
                    'projectsDataProvider' => $projectsDataProvider,
                    'countriesResponses' => $countriesResponses,
                    'eventsResponses' => $eventsResponses,
                    'healthClustersResponses' => $healthClustersResponses,
                    'layer' => $layer
                ]
            );
        } else {
            return $this->render(
                '/dashboards/global',
                [
                    'projectsDataProvider' => $projectsDataProvider,
                    'countriesResponses' => $countriesResponses,
                    'eventsResponses' => $eventsResponses,
                    'healthClustersResponses' => $healthClustersResponses,
                    'layer' => $layer
                ]
            );
        }
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