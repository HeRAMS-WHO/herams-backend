<?php


namespace prime\controllers\page;


use prime\models\ar\Page;
use yii\base\Action;
use yii\web\NotFoundHttpException;

class Update extends Action
{

    public function run(int $id)
    {
        $page = Page::findOne(['id' => $id]);
        if (!isset($page)) {
            throw new NotFoundHttpException();
        }

        return $this->controller->render('update', [
            'page' => $page
        ]);
    }

}