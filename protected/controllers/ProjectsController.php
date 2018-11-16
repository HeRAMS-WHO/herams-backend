<?php

namespace prime\controllers;

use app\queries\ProjectQuery;
use app\queries\ToolQuery;
use prime\components\Controller;
use prime\controllers\projects\Close;
use prime\controllers\projects\Download;
use prime\models\ar\Project;
use prime\models\ar\Setting;
use prime\models\ar\Tool;
use prime\models\forms\projects\CreateUpdate;
use prime\models\forms\projects\Token;
use prime\models\forms\Share;
use prime\models\permissions\Permission;
use prime\models\search\Project as ProjectSearch;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use SamIT\LimeSurvey\Interfaces\TokenInterface;
use SamIT\LimeSurvey\JsonRpc\Client;
use SamIT\LimeSurvey\JsonRpc\Concrete\Survey;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\Request;
use yii\web\Response;
use yii\web\Session;
use yii\web\User;

class ProjectsController extends Controller
{
    public $layout = 'simple';
    public $defaultAction = 'list';

    public function actionConfigure(Request $request, Session $session, $id)
    {
        /** @var Project $model */
        $model = Project::loadOne($id, [], Permission::PERMISSION_WRITE);
        // Form model.
        $token = new Token($model->getToken());

        if ($request->isPut && $token->load($request->bodyParams) && $token->save(true)) {
            $session->setFlash('success', \Yii::t('app', "Token updated."));
            $this->refresh();
        }
        return $this->render('configure', [
            'token' => $token,
            'model' => $model
        ]);
    }

    /**
     * Action for creating a new project.
     * @param CreateUpdate $model
     * @param Request $request
     * @param Session $session
     */
    public function actionCreate(
        Request $request,
        User $user,
        Session $session,
        Client $limeSurvey,
        int $toolId
    ) {
        $model = new CreateUpdate();
        $model->scenario = 'create';
        $model->tool_id = $toolId;

        $tool = Tool::loadOne($toolId);
        if (!$tool->validate()) {
            throw new InvalidConfigException("This project is not configured correctly, the survey could be missing");
        }
        if (!$tool->userCan(Permission::PERMISSION_INSTANTIATE, $user->identity)) {
            throw new ForbiddenHttpException("You are not allowed to create a workspace for this project");
        }
        if ($request->isPost) {
            if($model->load($request->bodyParams) && $model->save()) {
                $session->setFlash(
                    'projectCreated',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => \Yii::t('app', "Project <strong>{modelName}</strong> has been created.", ['modelName' => $model->title]),
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );

                if (!empty($model->getToken()->getCustomAttributes())) {
                    return $this->redirect(['projects/configure', 'id' => $model->id]);
                }
                return $this->redirect(['projects/read', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' =>  $model,
            'tool' => $tool
        ]);
    }

    /**
     * Shows a list of project the user has access to.
     * @return string
     */
    public function actionList(
        Request $request,
        int $toolId
    ) {
        $tool = Tool::loadOne($toolId);
        $projectSearch = new ProjectSearch($tool->id, [
            'queryCallback' => function(ProjectQuery $query) {
                return $query->readable();
            }
        ]);

        $projectsDataProvider = $projectSearch->search($request->queryParams);

        return $this->render('list', [
            'projectSearch' => $projectSearch,
            'projectsDataProvider' => $projectsDataProvider,
            'tool' => isset($toolId) ? Tool::findOne(['id' => $toolId]) : null
        ]);
    }

    public function actionListOthers(
        Request $request,
        int $toolId
    ) {
        $tool = Tool::loadOne($toolId);
        $projectSearch = new ProjectSearch($tool->id, [
            'queryCallback' => function(ProjectQuery $query) {
                return $query->notReadable();
            }
        ]);
        $projectsDataProvider = $projectSearch->search($request->queryParams);
        return $this->render('list', [
            'projectSearch' => $projectSearch,
            'projectsDataProvider' => $projectsDataProvider,
            'tool' => $tool
        ]);
    }

    public function actionListClosed(
        Request $request,
        int $toolId
    ) {
        $tool = Tool::loadOne($toolId);
        $projectSearch = new ProjectSearch($tool->id);
        $projectSearch->query = Project::find()->closed()->userCan(Permission::PERMISSION_WRITE);
        if(!app()->user->can('admin')) {
            $projectSearch->query->joinWith(['tool' => function(ToolQuery $query) {return $query->notHidden();}]);
        } else {
            $projectSearch->query->joinWith(['tool']);
        }
        $projectsDataProvider = $projectSearch->search($request->queryParams);

        return $this->render('listDeleted', [
            'projectSearch' => $projectSearch,
            'projectsDataProvider' => $projectsDataProvider,
            'tool' => $tool
        ]);
    }

    public function actionRead($id)
    {
        $project = Project::loadOne($id);
        $this->layout = 'angular';

        return $this->render('overview', [
            'model' => $project,
        ]);
    }

    public function actionOverview($pid)
    {
        $model = Tool::loadOne($pid);
        $this->layout = 'angular';

        return $this->render('overview', [
            'model' => $model,
        ]);
    }

    public function actionReOpen(Session $session, Request $request, $id)
    {
        if (!$request->isPut) {
            throw new HttpException(405);
        } else {
            $model = Project::loadOne($id, [], Permission::PERMISSION_ADMIN);
            $model->scenario = 'reOpen';

            $model->closed = null;
            if($model->save()) {
                $session->setFlash(
                    'projectReopened',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => \Yii::t('app', "Project <strong>{modelName}</strong> has been re-opened.", ['modelName' => $model->title]),
                        'icon' => 'glyphicon glyphicon-' . Setting::get('icons.open')
                    ]
                );
                return $this->redirect($request->referrer);
            }
        }
        if(isset($model)) {
            return $this->redirect(['/projects/read', 'id' => $model->id]);
        } else {
            return $this->redirect(['/projects/list-closed']);
        }
    }

    public function actionShare(Session $session, Request $request, $id)
    {
        $project = Project::loadOne($id, [], Permission::PERMISSION_SHARE);
        $model = new Share($project, [$project->owner_id], [
            'permissions' => [
                Permission::PERMISSION_READ,
                Permission::PERMISSION_WRITE,
                Permission::PERMISSION_SHARE,
                Permission::PERMISSION_ADMIN,

            ]
        ]);

        if($request->isPost) {
            if($model->load($request->bodyParams) && $model->createRecords()) {
                $session->setFlash(
                    'projectShared',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => \Yii::t('app',
                            "Project <strong>{modelName}</strong> has been shared with: <strong>{users}</strong>",
                            [
                                'modelName' => $project->title,
                                'users' => implode(', ', array_map(function($model){return $model->name;}, $model->getUsers()->all()))
                            ]),
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );
                $model = new Share($project, [$project->owner_id]);
            }
        }


        return $this->render('share', [
            'model' => $model,
            'project' => $project
        ]);
    }

    public function actionShareDelete(User $user, Request $request, Session $session, $id)
    {
        $permission = Permission::findOne($id);
        //User must be able to share project in order to delete a share
        $project = Project::loadOne($permission->target_id, [], Permission::PERMISSION_SHARE);
        if($permission->delete()) {
            $session->setFlash(
                'projectShared',
                [
                    'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                    'text' => \Yii::t(
                        'app',
                        "Stopped sharing project <strong>{modelName}</strong> with: <strong>{user}</strong>",
                        [
                            'modelName' => $project->title,
                            'user' => $user->identity->name
                        ]
                    ),
                    'icon' => 'glyphicon glyphicon-trash'
                ]
            );
        }
        $this->redirect(['/projects/share', 'id' => $project->id]);
    }

    public function actionUpdate(
        User $user,
        Request $request,
        Session $session,
        $id
    )
    {
        $model = CreateUpdate::loadOne($id, [], Permission::PERMISSION_ADMIN);
        if ($user->can('admin')) {
            $model->scenario = 'admin-update';
        } else {
            $model->scenario = 'update';
        }
        if($request->isPut) {
            if($model->load($request->bodyParams) && $model->save()) {
                $session->setFlash(
                    'projectUpdated',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => \Yii::t('app', "Project <strong>{modelName}</strong> has been updated.", ['modelName' => $model->title]),
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );
                return $this->redirect(['projects/list', 'toolId' => $model->tool_id]);
            }
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function actionUpdateLimeSurvey($id)
    {
        $model = Project::loadOne($id, [], Permission::PERMISSION_WRITE);
        return $this->render('updateLimeSurvey', [
            'model' => $model
        ]);
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'share-delete' => ['delete'],
                        'delete' => ['delete']
                    ]
                ],
                'access' => [
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['close', 'configure', 'list', 'list-others', 'list-closed',
                            'progress', 'read', 'download', 're-open', 'share', 'share-delete',
                                'update', 'update-lime-survey', 'new', 'overview'
                            ],
                            'roles' => ['@'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['create'],
                            'roles' => ['createProject']
                        ]
                    ]
                ]
            ]
        );
    }

    /**
     * Get a list of tokens for use in dependent dropdowns.
     * @param Response $response
     * @param Request $request
     * @param array $depdrop_parents
     * @return array
     */
    public function actionDependentTokens(
        Response $response,
        Request $request,
        Client $limeSurvey,
        array $depdrop_parents
    )
    {
        $response->format = Response::FORMAT_JSON;

        $surveyId = intval($depdrop_parents[0]);

        $result = [
//            [
//                'id' => 'new',
//                'name' => \Yii::t('app', 'Create new token')
//            ]
        ];

        if ($surveyId > 0) {
            // Get all tokens for the selected survey.
            $usedTokens = array_flip(Project::find()->select('token')->column());
            $tokens = $limeSurvey->getTokens($surveyId);
            /** @var TokenInterface $token */
            foreach ($tokens as $token) {
                if (!empty($token->getToken())) {
                    $row = [
                        'id' => $token->getToken(),
                        'name' => "{$token->getFirstName()} {$token->getLastName()} ({$token->getToken()}) " . implode(
                                ', ',
                                array_filter($token->getCustomAttributes())
                            ),
                        'options' => [
                            'disabled' => isset($usedTokens[$token->getToken()])
                        ]
                    ];

                    $result[] = $row;
                }
            }
        }
        return [
            'output' => $result,
            'selected' => ''
        ];
    }

    public function actions()
    {
        return [
            'download' => Download::class,
            'close' => Close::class
        ];
    }


}
