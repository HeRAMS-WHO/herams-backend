<?php
namespace prime\api\controllers;

use app\models\ar\Category;
use app\models\Menu;
use prime\models\ar\Tool;
use yii\caching\Cache;
use yii\web\HttpException;


class CategoriesController extends Controller
{
    /**
     * Categories for the left side menu and user info.
     */
    public function actionView($id, $pid)
    {
        $model = Tool::loadone($pid);
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
