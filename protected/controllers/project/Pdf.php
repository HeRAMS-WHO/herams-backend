<?php

namespace prime\controllers\project;

use herams\common\interfaces\AccessCheckInterface;
use herams\common\models\Page;
use herams\common\models\PermissionOld;
use herams\common\models\Project;
use prime\exceptions\SurveyDoesNotExist;
use prime\models\forms\ResponseFilter;
use yii\base\Action;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\ServerErrorHttpException;

class Pdf extends Action
{
    public function run(
        Request $request,
        AccessCheckInterface $accessCheck,
        int $id,
        int $page_id = null,
        int $parent_id = null,
        string $filter = null
    ) {
        //        $this->controller->layout = 'print';
        //        $project = Project::findOne([
        //            'id' => $id,
        //        ]);
        //        $accessCheck->requirePermission($project, PermissionOld::PERMISSION_READ);
        //        try {
        //            $survey = $project->getSurvey();
        //        } catch (SurveyDoesNotExist $e) {
        //            throw new ServerErrorHttpException($e->getMessage());
        //        }
        //
        //        if (isset($parent_id, $page_id)) {
        //            $parent = Page::findOne([
        //                'id' => $parent_id,
        //            ]);
        //            foreach ($parent->getChildPages() as $childPage) {
        //                if ($childPage->getid() === $page_id) {
        //                    $page = $childPage;
        //                    break;
        //                }
        //            }
        //            if (! isset($page)) {
        //                throw new NotFoundHttpException();
        //            }
        //        } elseif (isset($page_id)) {
        //            $page = Page::findOne([
        //                'id' => $page_id,
        //            ]);
        //            if (! isset($page) || $page->project_id !== $project->id) {
        //                throw new NotFoundHttpException();
        //            }
        //        }

        //        $responses = $project->getResponses();
        //
        //        \Yii::beginProfile('ResponseFilterinit');
        //
        //        $filterModel = new ResponseFilter($survey, $project->getMap());
        //        if (! empty($filter)) {
        //            $filterModel->fromQueryParam($filter);
        //        }
        //        $filterModel->load($request->queryParams);
        //        \Yii::endProfile('ResponseFilterinit');
        //
        //        /** @var $filtered */
        //
        //        $filtered = $filterModel->filterQuery($responses)->all();
        //
        //        $params = [
        //            'types' => $this->getTypes($survey, $project),
        //            'data' => $filtered,
        //            'filterModel' => $filterModel,
        //            'project' => $project,
        //            'survey' => $survey,
        //        ];
        //        if (isset($page)) {
        //            $params['page'] = $page;
        //        }
        //        return $this->controller->render('print', $params);
    }
}
