<?php

namespace prime\controllers;

use prime\components\Controller;
use prime\factories\MapLayerFactory;
use prime\models\ar\Project;
use prime\models\Country;
use prime\models\search\Report;
use prime\objects\ResponseCollection;
use SamIT\LimeSurvey\JsonRpc\Client;
use Symfony\Component\Finder\Exception\AccessDeniedException;
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

    public function actionList(Request $request)
    {
        $reportSearch = new Report();
        $reportsDataProvider = $reportSearch->search($request->queryParams);

        return $this->render('list', [
            'reportsDataProvider' => $reportsDataProvider,
            'reportSearch' => $reportSearch
        ]);
    }

    public function actionSummary(Request $request, Client $limesurvey, $id, $layer, $noMenu = false)
    {

        switch($layer) {
            case 'countryGrades':
                $responses = new ResponseCollection( array_merge(
                    $limesurvey->getResponses(self::$surveyIds['countryGrades']),
                    $limesurvey->getResponses(self::$surveyIds['eventGrades'])
                ));
                break;
            case 'healthClusters':
                $responses = new ResponseCollection($limesurvey->getResponses(self::$surveyIds['healthClusters']));
                break;
            case 'eventGrades':
                $responses = new ResponseCollection($limesurvey->getResponses(self::$surveyIds[$layer]));
                break;
            case 'projects':
                $responses = Project::find()->andWhere(['id' => $id]);
                break;
            default:
                $responses = new ResponseCollection();
                break;
        }
        $mapLayer = MapLayerFactory::get($layer, [$responses]);

        if($noMenu) {
            $this->view->params['hideMenu'] = true;
        }
        return $this->renderContent($mapLayer->renderSummary($this->getView(), $id));

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