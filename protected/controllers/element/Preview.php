<?php

namespace prime\controllers\element;

use prime\components\Controller;
use prime\components\LimesurveyDataProvider;
use prime\models\ar\Element;
use prime\models\ar\Permission;
use prime\models\forms\ResponseFilter;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;

class Preview extends Action
{

    public function run(
        LimesurveyDataProvider $limesurveyDataProvider,
        User $user,
        Request $request,
        int $id
    ) {
        $element = Element::findOne(['id' => $id]);

        if (!isset($element)) {
            throw new NotFoundHttpException();
        }
        if (!$user->can(Permission::PERMISSION_WRITE, $element)) {
            throw new ForbiddenHttpException();
        }
        $element->load($request->queryParams);
        // Hack for colors
        $colors = [];
        foreach ($request->queryParams['Element'] ?? [] as $key => $value) {
            if (strncmp($key, 'color.', 6) === 0) {
                $colors[substr($key, 6)] = $value;
            }
        }
        $element->setColors($colors);

        $responses = $element->project->getResponses();

        $survey = $limesurveyDataProvider->getSurvey($element->project->base_survey_eid);

        \Yii::beginProfile('ResponseFilterInit');
        $filter = new ResponseFilter($survey, $element->project->getMap());
        \Yii::endProfile('ResponseFilterInit');

        $this->controller->layout = Controller::LAYOUT_BASE;
        return $this->controller->render('preview', [
            'survey' => $survey,
            'element' => $element,
            'data' => $filter->filterQuery($responses)->all()
        ]);
    }
}
