<?php
declare(strict_types=1);

namespace prime\controllers;

use prime\actions\CreateChildAction;
use prime\actions\UpdateAction;
use prime\components\Controller;
use prime\controllers\facility\Create;
use prime\controllers\facility\Index;
use prime\controllers\facility\Responses;
use prime\controllers\facility\Update;
use prime\helpers\ModelHydrator;
use prime\repositories\FacilityRepository;
use prime\repositories\WorkspaceRepository;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class FacilityController extends Controller
{
    public $layout = self::LAYOUT_ADMIN_TABS;

    public function behaviors(): array
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ]
                ]
            ]
        );
    }

    public function actions(): array
    {
        return [
            'index' => Index::class,
//            'create' => static fn(string $id, Controller $controller, FacilityRepository $repository, WorkspaceRepository $workspaceRepository, ModelHydrator $modelHydrator)
//            => new CreateChildAction($id, $controller, $repository, $workspaceRepository, $modelHydrator),
            'create' => Create::class,
            'update' => Update::class,
//            'update' => static fn(string $id, Controller $controller, FacilityRepository $repository, ModelHydrator $modelHydrator) =>
//                new UpdateAction($id, $controller, $repository, $modelHydrator),
            'responses' => Responses::class
        ];
    }
}
