<?php

namespace prime\controllers;

use app\components\Request;
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

class MarketplaceController extends Controller
{
    public static $surveyIds = [
        'countryGrades' => 486496,
        'eventGrades' => 473297
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
            'healthClusters' => new ResponseCollection()
        ];

        return $this->render('map', ['mapLayerData' => $mapLayerData]);
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

    public function actionSummary(Client $limesurvey, $id, $layer)
    {

        switch($layer) {
            case 'countryGrades':
                $responses = new ResponseCollection( array_merge(
                    $limesurvey->getResponses(self::$surveyIds['countryGrades']),
                    $limesurvey->getResponses(self::$surveyIds['eventGrades'])
                ));
                break;
            case 'eventGrades':
                $responses = new ResponseCollection($limesurvey->getResponses(self::$surveyIds[$layer]));
                break;
            default:
                $responses = new ResponseCollection();
                break;
        }
        $mapLayer = MapLayerFactory::get($layer, [$responses]);

        return $mapLayer->renderSummary($this->getView(), $id);
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