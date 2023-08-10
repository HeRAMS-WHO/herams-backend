<?php

declare(strict_types=1);

namespace prime\controllers\workspace;

use Collecthor\DataInterfaces\VariableInterface;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\utils\tools\SurveyParserClean;
use herams\common\values\WorkspaceId;
use prime\components\BreadcrumbService;
use prime\components\Controller;
use yii\base\Action;
use yii\web\Request;
use function iter\filter;
use function iter\toArray;

class Facilities extends Action
{
    public function run(
        Request $request,
        BreadcrumbService $breadcrumbService,
        WorkspaceRepository $workspaceRepository,
        ProjectRepository $projectRepository,
        SurveyRepository $surveyRepository,
        SurveyParserClean $surveyParserClean,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $workspaceId = new WorkspaceId($id);
        $projectId = $workspaceRepository->getProjectId($workspaceId);
        $variableSet = $surveyRepository->retrieveVariableSet($projectRepository->retrieveAdminSurveyId($projectId), $projectRepository->retrieveDataSurveyId($projectId));
        $variables = toArray(filter(fn (VariableInterface $variable) => $variable->getRawConfigurationValue('showInFacilityList') !== null, $variableSet->getVariables()));
        $adminSurveyId = $surveyRepository->retrieveAdminSurveyForWorkspaceForSurveyJs($workspaceId)->getId();
        $adminSurvey = $surveyRepository->getById($adminSurveyId);
        $sortedCols = [];
        $unsortedCols = [];
        foreach ($adminSurvey->config['pages'] as $page) {
            foreach ($page['elements'] as $element) {
                if ($element['showInFacilityList'] ?? false) {
                    $sortedCols[$element['showInFacilityList'] - 1] = $element['name'];
                } else {
                    if ($element['name'] !== 'HSDU_TYPE_tier') {
                        $unsortedCols[] = $element['name'];
                    }
                }
                if ($element['showTierInFacilityList'] ?? false) {
                    $sortedCols[$element['showTierInFacilityList'] - 1] = 'HSDU_TYPE_tier';
                }
            }
        }

        usort($variables, fn (VariableInterface $a, VariableInterface $b) => $a->getRawConfigurationValue('showInFacilityList') <=> $b->getRawConfigurationValue('showInFacilityList'));
        $this->controller
            ->view
            ->breadcrumbCollection
            ->add(...toArray($breadcrumbService->retrieveForWorkspace($workspaceId)->getIterator()));
        $tableCols = $extraHeaders = [...\iter\map(fn (VariableInterface $variable) => [
            'field' => $variable->getName(),
            'headerName' => $variable->getTitle(\Yii::$app->language),
        ], $variables)];
        foreach ($tableCols as $index => $col) {
            if ($col['field'] === 'name') {
                unset($tableCols[$index]);
            }
        }
        if ($tableCols['name'] ?? '') {
            unset($tableCols['name']);
        }

        $sortedTableCols = [];
        $sortedFields = [];
        foreach ($sortedCols as $sorted) {
            foreach ($tableCols as $key => $col) {
                if ($sorted == $col['field']) {
                    $sortedTableCols[$key] = $col;
                    $sortedFields[$col['field']] = true;
                }
            }
        }
        foreach ($tableCols as $col) {
            if (($sortedFields[$col['field']] ?? null) === null) {
                $sortedTableCols[] = $col;
            }
        }
        return $this->controller->render('facilities', [
            'variables' => $variables,
            'tableCols' => $sortedTableCols,
        ]);
    }
}
