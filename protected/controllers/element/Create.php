<?php


namespace prime\controllers\element;


use prime\components\LimesurveyDataProvider;
use prime\components\NotificationService;
use prime\models\ar\Element;
use prime\models\ar\Page;
use prime\models\ar\Permission;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;

class Create extends Action
{

    public function run(
        Request $request,
        NotificationService $notificationService,
        LimesurveyDataProvider $limesurveyDataProvider,
        User $user,
        int $page_id,
        string $type

    ) {
        $page = Page::findOne(['id' => $page_id]);
        if (!isset($page)) {
            throw new NotFoundHttpException();
        }

        $project = $page->project;

        if (!$user->can(Permission::PERMISSION_MANAGE_DASHBOARD, $project)) {
            throw new ForbiddenHttpException();
        }


        try {
            $element = Element::instantiate(['type' => $type]);
            $element->type = $type;
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException('Invalid element type', 0, $e);
        }

        $element->page_id = $page->id;
        $element->sort = $page->getElements()->select('max(sort)')->scalar() + 1;
        $model = new \prime\models\forms\Element($limesurveyDataProvider->getSurvey($project->base_survey_eid), $element);
        $model->load($request->queryParams);
        if ($request->isPost) {
            if ($model->load($request->bodyParams)
                && $model->validate()
            ) {
                $model->save();
                $notificationService->success(\Yii::t('app', "Element created"));
                return $this->controller->redirect(['update', 'id' => $model->id]);
            } else {
                $notificationService->error(\Yii::t('app', "Element not created"));
            }
        }


        return $this->controller->render('update', [
            'page' => $page,
            'model' => $model,
            'project' => $project,
            'url' => Url::to(array_merge($request->queryParams, [
                '__key__' => '__value__',
                '0' => $this->uniqueId
            ]))
        ]);
    }

}