<?php


namespace prime\controllers;

use prime\actions\CreateChildAction;
use prime\actions\DeleteAction;
use prime\actions\ExportAction;
use prime\components\Controller;
use prime\controllers\workspace\Configure;
use prime\controllers\workspace\Create;
use prime\controllers\workspace\Facilities;
use prime\controllers\workspace\Import;
use prime\controllers\workspace\Limesurvey;
use prime\controllers\workspace\Refresh;
use prime\controllers\workspace\Responses;
use prime\controllers\workspace\Share;
use prime\controllers\workspace\Update;
use prime\controllers\workspace\View;
use prime\helpers\ModelHydrator;
use prime\models\ar\Permission;
use prime\models\ar\Workspace;
use prime\models\forms\Workspace as WorkspaceForm;
use prime\queries\ResponseQuery;
use prime\repositories\FacilityRepository;
use prime\repositories\ProjectRepository;
use prime\repositories\WorkspaceRepository;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Request;
use yii\web\User;

class WorkspaceController extends Controller
{
    public $layout = Controller::LAYOUT_ADMIN;


    public function actions(): array
    {
        return [
            'responses' => Responses::class,
            'export' => [
                'class' => ExportAction::class,
                'subject' => static function (Request $request) {
                      return Workspace::findOne(['id' => $request->getQueryParam('id')]);
                },
                'responseQuery' => static function (Workspace $workspace): ResponseQuery {
                    return $workspace->getResponses();
                },
                'surveyFinder' => function (Workspace $workspace) {
                    return $workspace->project->getSurvey();
                },
                'checkAccess' => function (Workspace $workspace, User $user) {
                    return $user->can(Permission::PERMISSION_EXPORT, $workspace);
                }
            ],
            'facilities' => Facilities::class,
            'limesurvey' => Limesurvey::class,
            'update' => Update::class,
            'create' => static function (
                string $id,
                Controller $controller,
                ProjectRepository $projectRepository,
                WorkspaceRepository $repository,
                ModelHydrator $modelHydrator
) {
                $action = new CreateChildAction($id, $controller, $repository, $projectRepository, $modelHydrator);
                $action->paramName = 'project_id';
                return $action;
            },
            'share' => Share::class,
            'import' => Import::class,
            'refresh' => Refresh::class,
            'delete' => [
                'class' => DeleteAction::class,
                'query' => Workspace::find(),
                'redirect' => function (Workspace $workspace) {
                    return ['/project/workspaces', 'id' => $workspace->tool_id];
                }
            ],

        ];
    }

    public function behaviors(): array
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'verb' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'create' => ['get', 'post']
                    ]
                ],
                'access' => [
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ]
                ],
            ]
        );
    }
}
