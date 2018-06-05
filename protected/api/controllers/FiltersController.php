<?php

namespace prime\api\controllers;

use app\models\Overview;
use prime\models\ar\Project;
use prime\models\permissions\Permission;
use SamIT\LimeSurvey\JsonRpc\Client;
use yii\caching\Cache;
use yii\web\HttpException;


class FiltersController extends Controller
{
    /**
     * List questions that can be used as filters.
     * Currently all questions are included.
     */
    public function actionView(Client $limeSurvey, Cache $cache, $id)
    {
        try {
            $project = Project::loadOne($id, [], Permission::PERMISSION_READ);

            $filters = Overview::loadStructure($limeSurvey, $cache, $project->data_survey_eid);

            // Exclude groups or questions here.

            return $filters;
        } catch (\Exception $ex) {
            throw new HttpException(404, $ex->getMessage());
        }
    }


    public function behaviors()
    {
        $result = parent::behaviors();

        array_unshift($result['access']['rules'],
            [
                'allow' => true,
                'roles' => ['@'],
                'actions' => ['view']
            ]
        );
        return $result;
    }

}
