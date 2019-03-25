<?php
namespace prime\api\v1\controllers;

use app\models\ar\Category;
use app\models\Menu;
use prime\models\ar\Project;


class CategoriesController extends Controller
{

    public function actionIndex()
    {
        return Category::find()->all();
    }
    /**
     * Categories for the left side menu and user info.
     */
    public function actionView($id, $pid)
    {
        $model = Project::loadone($pid);
        $user = app()->user->identity;

        $result = [
            'results' => [
                'country' => $model->acronym,
                'categories' => Menu::categories($pid),
                'userinfo' => [
                    'first_name' => $user->getFirstName(),
                    'last_name' => $user->getLastName(),
                    'email' => $user->getUsername()
                ],
            ],
        ];

        return $result;
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
