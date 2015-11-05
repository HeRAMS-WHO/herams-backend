<?php

namespace prime\controllers;

use prime\components\Controller;
use prime\models\Country;
use prime\models\permissions\Permission;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class CountriesController extends Controller
{
    public function actionList()
    {
        $countriesDataProvider = new ActiveDataProvider([
            'query' => Country::find()
        ]);

        return $this->render('list', [
            'countriesDataProvider' => $countriesDataProvider
        ]);
    }

    public function actionUpdate($id)
    {
        $model = Country::loadOne($id, Permission::PERMISSION_WRITE);
        $model->scenario = 'update';

        if(app()->request->isPut) {
            if($model->load(app()->request->data()) && $model->save()) {
                app()->session->setFlash(
                    'countryUpdated',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => \Yii::t('app', "Country <strong>{modelName}</strong> has been updated.", ['modelName' => $model->name]),
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );
                $this->redirect(['/countries/list']);
            }
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                'access' => [
                    'rules' => [

                    ]
                ]
            ]
        );
    }
}