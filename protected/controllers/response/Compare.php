<?php

declare(strict_types=1);

namespace prime\controllers\response;

use prime\components\LimesurveyDataProvider;
use prime\models\ar\ResponseForLimesurvey;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use yii\base\Action;
use yii\web\NotFoundHttpException;

class Compare extends Action
{
    public function run(
        LimesurveyDataProvider $limesurveyDataProvider,
        int $id,
        int $survey_id
    ) {
        /** @var ResponseForLimesurvey|null $response */
        $response = ResponseForLimesurvey::find()->with('workspace')->andWhere(['id' => $id, 'survey_id' => $survey_id])->one();
        if (!isset($response)) {
            throw new NotFoundHttpException();
        }


        $limesurveyData = $limesurveyDataProvider->refreshResponsesByToken($response->survey_id, $response->workspace->getAttribute('token'));
        // Find the correct response.
        /** @var ResponseInterface $limesurveyResponse */
        foreach ($limesurveyData as $limesurveyResponse) {
            if ((int)$limesurveyResponse->getId() === $id) {
                return $this->controller->render('compare', [
                    'limesurveyResponse' => $limesurveyResponse,
                    'storedResponse' => $response
                ]);
            }
        }
        throw new NotFoundHttpException('Response not found in limesurvey');
    }
}
