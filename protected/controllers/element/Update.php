<?php

declare(strict_types=1);

namespace prime\controllers\element;

use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Element;
use prime\models\ar\elements\Svelte;
use prime\models\ar\Permission;
use prime\values\PageId;
use prime\values\ProjectId;
use yii\base\Action;
use yii\web\Request;

class Update extends Action
{
    private function handleSurveyJs(Request $request, Svelte $element)
    {
        return $this->controller->render('update-survey-js', [
            'model' => $element,
            'pageId' => new PageId($element->page_id),
            'endpointUrl' => [
                '/api/element/update',
                'id' => $element->id,
            ],
            'projectId' => new ProjectId(
                $element->page->project_id
            ),
        ]);
    }

    public function run(
        Request $request,
        AccessCheckInterface $accessCheck,
        int $id
    ) {
        $element = Element::find()->andWhere([
            'id' => $id,
        ])->one();

        $accessCheck->requirePermission($element, Permission::PERMISSION_WRITE);

        return $this->handleSurveyJs($request, $element);
    }
}
