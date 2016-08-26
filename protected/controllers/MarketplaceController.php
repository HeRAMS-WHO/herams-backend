<?php

namespace prime\controllers;

use Carbon\Carbon;
use prime\components\Controller;
use prime\factories\MapLayerFactory;
use prime\models\ar\Project;
use prime\models\ar\Setting;
use prime\models\Country;
use prime\models\forms\MarketplaceFilter;
use prime\models\mapLayers\CountryGrades;
use prime\models\mapLayers\EventGrades;
use prime\models\search\Report;
use prime\objects\ResponseCollection;
use prime\objects\ResponseFilter;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use SamIT\LimeSurvey\JsonRpc\Client;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
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
        $filter->scenario = 'global';
        $filter->load($request->queryParams);

        if(!$filter->validate()) {
            throw new BadRequestHttpException("Invalid filter values");
        }

        $project = Project::findOne(Setting::get('healthClusterDashboardProject'));
        $healthClusterResponses = isset($project) ? iterator_to_array($project->getResponses()) : [];

        $mapLayerData = [
            'projects' => $filter->applyToProjects(Project::find()),
            'countryGrades' => new ResponseCollection($filter->applyToResponses($limeSurvey->getResponses(Setting::get('countryGradesSurvey')))),
            'eventGrades' => new ResponseCollection($filter->applyToResponses($limeSurvey->getResponses(Setting::get('eventGradesSurvey')))),
            'healthClusters' => new ResponseCollection($filter->applyToResponses($healthClusterResponses)),
        ];

        //Get active
        return $this->render('map', ['mapLayerData' => $mapLayerData, 'countries' => [], 'filter' => $filter]);
    }

    /**
     * @param Request $request
     * @param Client $limeSurvey
     * @param string $iso_3
     * @param $layer
     * @param bool|false $popup
     * @param string $id
     * @return string
     */
    public function actionCountryDashboard(Request $request, Client $limeSurvey, $iso_3, $layer, $popup = false)
    {
        if ($popup) {
            $this->view->params['hideMenu'] = true;
            $this->view->params['hideFilter'] = true;
            $this->view->params['containerOptions']['class'][] = 'container-fluid';
        }

        $filter = new MarketplaceFilter();
        $filter->scenario = 'country';
        $filter->load($request->queryParams);

        if(!$filter->validate()) {
            throw new BadRequestHttpException("Invalid filter values");
        }

        $country = Country::findOne($iso_3);
        //get projects data provider for projects tab
        $projectsDataProvider = new ActiveDataProvider(
            [
                'query' => $filter->applyToProjects(Project::find()->notClosed()->andWhere(['country_iso_3' => $country->iso_3]))
            ]
        );

        //get country responses
        $countryFilter = new ResponseFilter($filter->applyToResponses($limeSurvey->getResponses(Setting::get('countryGradesSurvey'))));
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
        $eventFilter = new ResponseFilter($filter->applyToResponses($limeSurvey->getResponses(Setting::get('eventGradesSurvey'))));
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
        //only show events that are not "preparedness or ungraded"
        $eventFilter->filterGroups(function($group) {
            $response = $group[count($group) - 1];
            return EventGrades::valueMap()[$response->getData()['GM02']] > 1;
        });
        $eventFilter->sortGroups(function($a, $b) {
            /**
             * @var ResponseInterface $aR
             * @var ResponseInterface $bR
             */
            $aR = $a[count($a) - 1];
            $bR = $b[count($b) - 1];

            if(EventGrades::valueMap()[$aR->getData()['GM02']] > EventGrades::valueMap()[$bR->getData()['GM02']]) {
                return -1;
            } elseif(EventGrades::valueMap()[$aR->getData()['GM02']] < EventGrades::valueMap()[$bR->getData()['GM02']]) {
                return 1;
            } else {
               return 0;
            }
        });

        //get health cluster responses
        $project = Project::findOne(Setting::get('healthClusterDashboardProject'));
        $healthClusterResponses = isset($project) ? iterator_to_array($project->getResponses()) : [];
        $healthClusterFilter = new ResponseFilter($filter->applyToResponses($healthClusterResponses));
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
                'layer' => $layer,
                'filter' => $filter,
                'popup' => $popup
            ]
        );
    }

    /**
     * @param Request $request
     * @param Client $limeSurvey
     * @param $layer
     * @param bool|false $popup
     * @return string
     */
    public function actionGlobalDashboard(Request $request, Client $limeSurvey, $layer = 'countryGrades', $popup = false) {
        if($popup) {
            $this->view->params['hideMenu'] = true;
            $this->view->params['hideFilter'] = true;
            $this->view->params['containerOptions']['class'][] = 'container-fluid';
        }

        $filter = new MarketplaceFilter();
        $filter->scenario = 'global';
        $filter->load($request->queryParams);

        if(!$filter->validate()) {
            throw new BadRequestHttpException("Invalid filter values");
        }

        //get projects data provider for projects tab
        $projectsDataProvider = new ActiveDataProvider(
            [
                'query' => $filter->applyToProjects(Project::find()->notClosed())
            ]
        );

        //get country responses
        $countryFilter = new ResponseFilter($filter->applyToResponses($limeSurvey->getResponses(Setting::get('countryGradesSurvey'))));
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
        $countryFilter->sortGroups(function($a, $b) {
            /**
             * @var ResponseInterface $aR
             * @var ResponseInterface $bR
             */
            $aR = $a[count($a) - 1];
            $bR = $b[count($b) - 1];
            if(CountryGrades::valueMap()[$aR->getData()['GM02']] > CountryGrades::valueMap()[$bR->getData()['GM02']]) {
                return -1;
            } elseif(CountryGrades::valueMap()[$aR->getData()['GM02']] < CountryGrades::valueMap()[$bR->getData()['GM02']]) {
                return 1;
            } else {
                /**
                 * @var Country $aC
                 * @var Country $bC
                 */
                $aC = Country::findOne($aR->getData()['PRIMEID']);
                $bC = Country::findOne($bR->getData()['PRIMEID']);
                return strnatcmp($aC->name, $bC->name);
            }

        });

        //get event responses
        $eventFilter = new ResponseFilter($filter->applyToResponses($limeSurvey->getResponses(Setting::get('eventGradesSurvey'))));
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
        $eventFilter->sortGroups(function($a, $b) {
            /**
             * @var ResponseInterface $aR
             * @var ResponseInterface $bR
             */
            $aR = $a[count($a) - 1];
            $bR = $b[count($b) - 1];

            if(EventGrades::valueMap()[$aR->getData()['GM02']] > EventGrades::valueMap()[$bR->getData()['GM02']]) {
                return -1;
            } elseif(EventGrades::valueMap()[$aR->getData()['GM02']] < EventGrades::valueMap()[$bR->getData()['GM02']]) {
                return 1;
            } else {
                /**
                 * @var Country $aC
                 * @var Country $bC
                 */
                $aC = Country::findOne($aR->getData()['PRIMEID']);
                $bC = Country::findOne($bR->getData()['PRIMEID']);
                return strnatcmp($aC->name, $bC->name);
            }

        });

        //get health cluster responses
        $project = Project::findOne(Setting::get('healthClusterDashboardProject'));
        $healthClusterResponses = isset($project) ? iterator_to_array($project->getResponses()) : [];
        $healthClusterFilter = new ResponseFilter($filter->applyToResponses($healthClusterResponses));
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
                'layer' => $layer,
                'filter' => $filter
            ]
        );
    }

    public function actionEventDashboard(Request $request, Client $limeSurvey, $iso_3, $id, $layer, $popup = false)
    {
        if ($popup) {
            $this->view->params['hideMenu'] = true;
            $this->view->params['hideFilter'] = true;
            $this->view->params['containerOptions']['class'][] = 'container-fluid';
        }

        $filter = new MarketplaceFilter();
        $filter->scenario = 'country';
        $filter->load($request->queryParams);

        if(!$filter->validate()) {
            throw new BadRequestHttpException("Invalid filter values");
        }

        $country = Country::findOne($iso_3);

        //get event responses
        $eventFilter = new ResponseFilter($filter->applyToResponses($limeSurvey->getResponses(Setting::get('eventGradesSurvey'))));
        $eventFilter->filter(
            function (ResponseInterface $response) use ($id) {
                return $response->getData()['UOID'] == $id;
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

        return $this->render(
            '/dashboards/event',
            [
                'country' => $country,
                'id' => $id,
                'eventsResponses' => $eventFilter->getGroups(),
                'layer' => $layer,
                'filter' => $filter,
                'popup' => $popup
            ]
        );
    }

    public function actionHealthClusterDashboard(Request $request, Client $limeSurvey, $iso_3, $id, $layer, $popup = false)
    {
        if ($popup) {
            $this->view->params['hideMenu'] = true;
            $this->view->params['hideFilter'] = true;
            $this->view->params['containerOptions']['class'][] = 'container-fluid';
        }

        $filter = new MarketplaceFilter();
        $filter->scenario = 'country';
        $filter->load($request->queryParams);

        if(!$filter->validate()) {
            throw new BadRequestHttpException("Invalid filter values");
        }

        $country = Country::findOne($iso_3);

        //get health cluster responses
        $project = Project::findOne(Setting::get('healthClusterDashboardProject'));
        $healthClusterResponses = isset($project) ? iterator_to_array($project->getResponses()) : [];
        $healthClusterFilter = new ResponseFilter($filter->applyToResponses($healthClusterResponses));
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
            '/dashboards/healthCluster',
            [
                'country' => $country,
                'id' => $id,
                'healthClustersResponses' => $healthClusterFilter->getGroups(),
                'layer' => $layer,
                'filter' => $filter,
                'popup' => $popup
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
                            'actions' => ['global-dashboard']
                        ],
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