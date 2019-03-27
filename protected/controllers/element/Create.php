<?php


namespace prime\controllers\element;


use prime\models\ar\Element;
use prime\models\ar\Page;
use prime\models\permissions\Permission;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Session;
use yii\web\User;

class Create extends Action
{

    public function run(
        Request $request,
        Session $session,
        User $user,
        int $page_id

    ) {
        $page = Page::findOne(['id' => $page_id]);
        if (!isset($page)) {
            throw new NotFoundHttpException();
        }

        $project = $page->project;

        if (!$user->can(Permission::PERMISSION_ADMIN, $project)) {
            throw new ForbiddenHttpException();
        }


        $model = new Element();
        $model->page_id = $page->id;

        if ($request->isPost) {
            if ($model->load($request->bodyParams) && $model->save()) {
                $session->setFlash(
                    'elementCreated',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => "Element created",
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );

                return $this->controller->redirect(['update', 'id' => $model->id]);
            }
        }

        return $this->controller->render('create', [
            'page' => $page,
            'model' => $model,
            'project' => $project
        ]);
    }

}