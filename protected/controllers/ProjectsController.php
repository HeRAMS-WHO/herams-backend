<?php

namespace prime\controllers;

use prime\components\Controller;
use prime\models\Project;
use yii\data\ActiveDataProvider;

class ProjectsController extends Controller
{
    public $defaultAction = 'list';

    public function actionCreate()
    {
        $model = new Project();
        $model->scenario = 'create';

        if (app()->request->isPost) {
            if($model->load(app()->request->data()) && $model->save()) {
                $this->redirect(['projects/read', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' =>  $model
        ]);
    }

    public function actionList()
    {
        $projectsDataProvider = new ActiveDataProvider([
            'query' => app()->user->identity->getProjects()
        ]);

        return $this->render('list', [
            'projectsDataProvider' => $projectsDataProvider
        ]);
    }

    /**
     * @todo only find projects you can access
     */
    public function actionRead($id)
    {
        return $this->render('read', [
            'model' => Project::findOne($id)
        ]);
    }
}