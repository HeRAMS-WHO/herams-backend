<?php

declare(strict_types=1);

namespace prime\controllers\element;

use prime\components\Controller;
use prime\helpers\ModelHydrator;
use prime\interfaces\HeramsVariableSetRepositoryInterface;
use prime\models\forms\element\Chart;
use prime\repositories\FacilityRepository;
use prime\repositories\HeramsVariableSetRepository;
use prime\repositories\PageRepository;
use prime\repositories\ProjectRepository;
use prime\repositories\SurveyResponseRepository;
use prime\values\ProjectId;
use yii\base\Action;
use yii\web\Request;
use yii\web\UnprocessableEntityHttpException;
use yii\web\User;

class PreviewForSurveyJs extends Action
{
    public function run(
        ModelHydrator $modelHydrator,
        FacilityRepository $facilityRepository,
        HeramsVariableSetRepositoryInterface $heramsVariableSetRepository,
        Request $request,
        int $projectId,
        string $config
    ) {
        $this->controller->layout = Controller::LAYOUT_BASE;

        $variableSet = $heramsVariableSetRepository->retrieveForProject(new ProjectId($projectId));
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
