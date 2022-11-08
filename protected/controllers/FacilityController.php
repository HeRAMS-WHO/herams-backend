<?php

declare(strict_types=1);

namespace prime\controllers;

use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use prime\components\Controller;
use prime\controllers\facility\AdminResponses;
use prime\controllers\facility\Create;
use prime\controllers\facility\Index;
use prime\controllers\facility\Responses;
use prime\controllers\facility\Update;
use prime\controllers\facility\UpdateSituation;
use prime\controllers\facility\View;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class FacilityController extends Controller
{
    public $layout = self::LAYOUT_ADMIN_TABS;

    public function __construct(
        $id,
        $module,
        private FacilityRepository $facilityRepository,
        private ProjectRepository $projectRepository,
        private WorkspaceRepository $workspaceRepository,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actions(): array
    {
        return [
            'admin-responses' => AdminResponses::class,
            'create' => Create::class,
            'index' => Index::class,
            'responses' => Responses::class,
            'update' => Update::class,
            'view' => View::class,
            'update-situation' => UpdateSituation::class,
        ];
    }

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
                    ],
                ],
            ]
        );
    }
}
