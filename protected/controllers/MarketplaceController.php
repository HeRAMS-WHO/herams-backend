<?php

namespace prime\controllers;

use Carbon\Carbon;
use prime\components\Controller;
use prime\factories\MapLayerFactory;
use prime\models\ar\Project;
use prime\models\ar\Setting;
use prime\models\Country;
use prime\models\forms\MarketplaceFilter;
use prime\models\search\Report;
use prime\objects\ResponseCollection;
use prime\objects\ResponseFilter;
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

    public function actionMap(Request $request, Client $limeSurvey)
    {
        $filter = new MarketplaceFilter();

        $filter->load($request->queryParams);

        $mapLayerData = [
            'projects' => $filter->applyToProjects(Project::find()),
            'countryGrades' => new ResponseCollection($filter->applyToResponses($limeSurvey->getResponses(Setting::get('countryGradesSurvey')))),
            'eventGrades' => new ResponseCollection($filter->applyToResponses($limeSurvey->getResponses(Setting::get('eventGradesSurvey')))),
            'healthClusters' => new ResponseCollection($filter->applyToResponses($limeSurvey->getResponses(Setting::get('healthClusterMappingSurvey')))),
        ];

        //Get active
        return $this->render('map', ['mapLayerData' => $mapLayerData, 'countries' => [], 'filter' => $filter]);
    }

    /**
     * @param Request $request
     * @param Client $limeSurvey
     * @param string $iso_3
     * @param $layer
     * @param bool|false $noMenu
     * @return string
     */
    public function actionCountryDashboard(Request $request, Client $limeSurvey, $iso_3, $layer, $noMenu = false)
    {
        if ($noMenu) {
            $this->view->params['hideMenu'] = true;
            $this->view->params['containerOptions']['class'][] = 'container-fluid';
        }

        $country = Country::findOne($iso_3);
        //get projects data provider for projects tab
        $projectsDataProvider = new ActiveDataProvider(
            [
                'query' => Project::find()->notClosed()->andWhere(['country_iso_3' => $country->iso_3])
            ]
        );

        //get country responses
        $countryFilter = new ResponseFilter($limeSurvey->getResponses(Setting::get('countryGradesSurvey')));
        $countryFilter->filter(
            function (ResponseInterface $response) use ($country) {
                return $response->getData()['PRIMEID'] == $country->iso_3;
            }
        );
        $countryFilter->group('PRIMEID');
        $countryFilter->sortGroupsInternally(function($a, $b){
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

        //get event responses
        $eventFilter = new ResponseFilter($limeSurvey->getResponses(Setting::get('eventGradesSurvey')));
        $eventFilter->filter(
            function (ResponseInterface $response) use ($country) {
                return $response->getData()['PRIMEID'] == $country->iso_3;
            }
        );
        $eventFilter->group('UOID');
        $eventFilter->sortGroupsInternally(function($a, $b){
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

        //get health cluster responses
        $healthClusterFilter = new ResponseFilter($limeSurvey->getResponses(Setting::get('healthClusterMappingSurvey')));
        $healthClusterFilter->filter(
            function (ResponseInterface $response) use ($country) {
                return $response->getData()['PRIMEID'] == $country->iso_3;
            }
        );
        $healthClusterFilter->group('UOID');
        $healthClusterFilter->sortGroupsInternally(function($a, $b){
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

        return $this->render(
            '/dashboards/country',
            [
                'country' => $country,
                'projectsDataProvider' => $projectsDataProvider,
                'countriesResponses' => $countryFilter->getGroups(),
                'eventsResponses' => $eventFilter->getGroups(),
                'healthClustersResponses' => $healthClusterFilter->getGroups(),
                'layer' => $layer
            ]
        );
    }

    /**
     * @param Request $request
     * @param Client $limeSurvey
     * @param $layer
     * @param bool|false $noMenu
     * @return string
     */
    public function actionGlobalDashboard(Request $request, Client $limeSurvey, $layer, $noMenu = false) {
        if($noMenu) {
            $this->view->params['hideMenu'] = true;
            $this->view->params['containerOptions']['class'][] = 'container-fluid';
        }

        //get projects data provider for projects tab
        $projectsDataProvider = new ActiveDataProvider(
            [
                'query' => Project::find()->notClosed()
            ]
        );

        //get country responses
        $countryFilter = new ResponseFilter($limeSurvey->getResponses(Setting::get('countryGradesSurvey')));
        $countryFilter->filter(
            function (ResponseInterface $response) {
                return true;
            }
        );
        $countryFilter->group('PRIMEID');
        $countryFilter->sortGroupsInternally(function($a, $b){
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

        //get event responses
        $eventFilter = new ResponseFilter($limeSurvey->getResponses(Setting::get('eventGradesSurvey')));
        $eventFilter->filter(
            function (ResponseInterface $response) {
                return true;
            }
        );
        $eventFilter->group('UOID');
        $eventFilter->sortGroupsInternally(function($a, $b){
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

        //get health cluster responses
        $healthClusterFilter = new ResponseFilter($limeSurvey->getResponses(Setting::get('healthClusterMappingSurvey')));
        $healthClusterFilter->filter(
            function (ResponseInterface $response) {
                return true;
            }
        );
        $healthClusterFilter->group('UOID');
        $healthClusterFilter->sortGroupsInternally(function($a, $b){
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

        return $this->render(
            '/dashboards/global',
            [
                'projectsDataProvider' => $projectsDataProvider,
                'countriesResponses' => $countryFilter->getGroups(),
                'eventsResponses' => $eventFilter->getGroups(),
                'healthClustersResponses' => $healthClusterFilter->getGroups(),
                'layer' => $layer
            ]
        );
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