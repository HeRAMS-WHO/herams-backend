<?php

namespace prime\controllers\project;

use prime\models\ar\Project;
use prime\models\forms\ResponseFilter;
use yii\base\Action;
use yii\web\Request;

class Filter extends Action
{


    public function run(
        Request $request,
        int $id,
        int $page_id = null,
        int $parent_id = null
    ) {
        $this->controller->layout = 'css3-grid';
        $project = Project::findOne(['id'  => $id]);
        $survey = $project->getSurvey();
        $filter = new ResponseFilter($survey, $project->getMap());
        $filter->load($request->bodyParams);
        return $this->controller->redirect(['project/view',
            'id' => $id,
            'page_id' => $page_id,
            'parent_id' => $parent_id,
            'filter' => $filter->toQueryParam()]);
    }
}
