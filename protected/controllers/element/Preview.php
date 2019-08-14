<?php


namespace prime\controllers\element;


use prime\components\LimesurveyDataProvider;
use prime\models\ar\Element;
use prime\models\forms\ResponseFilter;
use prime\models\permissions\Permission;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\User;

class Preview extends Action
{

    public function run(
        LimesurveyDataProvider $limesurveyDataProvider,
        User $user,
        int $id
    ) {
        $element = Element::findOne(['id' => $id]);

        if (!isset($element)) {
            throw new NotFoundHttpException();
        }
        if (!$user->can(Permission::PERMISSION_ADMIN, $element->page->project)) {
            throw new ForbiddenHttpException();
        }
        $responses = $element->project->getResponses();

        $survey = $limesurveyDataProvider->getSurvey($element->project->base_survey_eid);

        \Yii::beginProfile('ResponseFilterinit');
        $filter = new ResponseFilter($survey, $element->project->getMap());
        \Yii::endProfile('ResponseFilterinit');

        $this->controller->layout = 'base';
        return $this->controller->render('preview', [
            'survey' => $survey,
            'element' => $element,
            'data' => $filter->filterQuery($responses)->all()
        ]);

    }

}