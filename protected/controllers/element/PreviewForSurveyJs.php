<?php

declare(strict_types=1);

namespace prime\controllers\element;

use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\helpers\ModelHydrator;
use herams\common\values\ProjectId;
use prime\components\Controller;
use prime\models\forms\element\Chart;
use yii\base\Action;

class PreviewForSurveyJs extends Action
{
    public function run(
        ModelHydrator $modelHydrator,
        FacilityRepository $facilityRepository,
        ProjectRepository $projectRepository,
        SurveyRepository $surveyRepository,
        int $projectId,
        string $config
    ) {
        $this->controller->layout = Controller::LAYOUT_BASE;

        $projectId = new ProjectId($projectId);

        $adminSurveyId = $projectRepository->retrieveAdminSurveyId($projectId);
        $dataSurveyId = $projectRepository->retrieveDataSurveyId($projectId);
        $variableSet = $surveyRepository->retrieveVariableSet($adminSurveyId, $dataSurveyId);

        $model = new Chart($variableSet);
        $modelHydrator->hydrateFromJsonDictionary($model, json_decode($config, true));

        $model->validate();
        $facilities = $facilityRepository->searchInProject(new ProjectId($projectId));

        $this->controller->layout = Controller::LAYOUT_BASE;
        return $this->controller->render('preview-survey-js', [
            'element' => $model,
            'facilities' => $facilities,
            'variableSet' => $variableSet,
        ]);
    }
}
