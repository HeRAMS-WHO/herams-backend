<?php

namespace prime\controllers;

use app\components\Html;
use Befound\Components\DateTime;
use prime\components\Controller;
use prime\models\Country;
use prime\models\forms\projects\CreateUpdate;
use prime\models\forms\Share;
use prime\models\forms\projects\Token;
use prime\models\permissions\Permission;
use prime\models\ar\Project;
use prime\models\ar\Tool;
use SamIT\LimeSurvey\Interfaces\TokenInterface;
use SamIT\LimeSurvey\JsonRpc\Client;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Request;
use yii\web\Session;
use yii\web\User;
use yii\web\Response;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

class ProjectsController extends Controller
{
    public $defaultAction = 'list';

    public function actionClose(Session $session, Request $request, $id)
    {
        if (!$request->isDelete) {
            throw new HttpException(405);
        } else {
            $model = Project::loadOne($id, [], Permission::PERMISSION_WRITE);
            $model->scenario = 'close';

            $model->closed = (new DateTime())->format(DateTime::MYSQL_DATETIME);
            if($model->save()) {
                $session->setFlash(
                    'projectClosed',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => \Yii::t('app', "Project <strong>{modelName}</strong> has been closed.", ['modelName' => $model->title]),
                        'icon' => 'glyphicon glyphicon-trash'
                    ]
                );
                return $this->redirect(['/projects/list']);
            }
        }
        if(isset($model)) {
            return $this->redirect(['/projects/read', 'id' => $model->id]);
        } else {
            return $this->redirect(['/projects/list']);
        }
    }

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
            'token' => $token
        ]);

    }

    public function actionCreate(CreateUpdate $model, Request $request, Session $session)
    {
        $model->scenario = 'create';

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
            'model' =>  $model
        ]);
    }


    /**
     * Shows the available tools in a large grid.
     */
    public function actionNew()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Tool::find()->notHidden()
        ]);

        return $this->render('new', ['dataProvider' => $dataProvider]);
    }
    /**
     * Shows a list of project the user has access to.
     * @return string
     */
    public function actionList(User $user, Request $request)
    {
        $projectSearch = new \prime\models\search\Project();
        $projectsDataProvider = $projectSearch->search($request->queryParams);

        return $this->render('list', [
            'projectSearch' => $projectSearch,
            'projectsDataProvider' => $projectsDataProvider
        ]);
    }

    public function actionProgress(Response $response, $id)
    {
        $project = Project::loadOne($id);
        $report = $project->getProgressReport();

        $response->setContentType($report->getMimeType());
        $response->content = $report->getStream();
        return $response;
    }

    public function actionRead($id)
    {
        $project = Project::loadOne($id);
        return $this->render('read', [
            'model' => $project,
        ]);
    }

    public function actionShare(Session $session, Request $request, $id)
    {
        $project = Project::loadOne($id, [], Permission::PERMISSION_SHARE);
        $model = new Share($project, [$project->owner_id]);

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

    public function actionShareDelete(Request $request, Session $session, $id)
    {
        $permission = Permission::findOne($id);
        //User must be able to share project in order to delete a share
        $project = Project::loadOne($permission->target_id, [], Permission::PERMISSION_SHARE);
        $user = $permission->sourceObject;
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
                            'user' => $user->name
                        ]
                    ),
                    'icon' => 'glyphicon glyphicon-trash'
                ]
            );
        }
        $this->redirect(['/projects/share', 'id' => $project->id]);
    }

    public function actionUpdate(Request $request, Session $session, $id)
    {
        $model = CreateUpdate::loadOne($id, [], Permission::PERMISSION_WRITE);
        $model->scenario = 'update';

        if($request->isPost) {
            if($model->load($request->bodyParams) && $model->save()) {
                $session->setFlash(
                    'projectUpdated',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => \Yii::t('app', "Project <strong>{modelName}</strong> has been updated.", ['modelName' => $model->title]),
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );
                return $this->redirect(['projects/read', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }


    public function actionExplore()
    {
        return $this->renderContent(Html::tag('div', Html::tag('iframe', '', [
            'src' => 'https://internal.shinyapps.io/prime/herams_proto/?initialWidth=1920&childId=shinyapp',
            'style' => [
                'width' => '100%',
                'height' => '100%',
                'border' => 'none'
            ]
        ]), [
            'style' => [
                'position' => 'fixed',
                'left' => '0px',
                'right' => '0px',
                'bottom' => '0px',
                'top' => '70px',
            ]
        ]));
    }
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'share-delete' => ['delete']
                    ]
                ],
                'access' => [
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


    /**
     * Get a list of generators for use in dependent dropdowns.
     * @param Response $response
     * @param Request $request
     * @param array $depdrop_parents
     * @return array
     */
    public function actionDependentSurveys(
        Response $response,
        Request $request,
        Project $project,
        array $depdrop_parents
    )
    {
        $response->format = Response::FORMAT_JSON;
        $project->tool_id = $depdrop_parents[0];
        $result = [];
        if ($project->validate(['tool_id'])) {
            foreach ($project->dataSurveyOptions() as $key => $value) {
                $result[] = [
                    'id' => $key,
                    'name' => $value
                ];

            }
        }

        return [
            'output' => $result,
            'selected' => ''
        ];
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

            $tokens = array_filter($limeSurvey->getTokens($surveyId), function (TokenInterface $token) use ($usedTokens) {
                return !isset($usedTokens[$token->getToken()]) && $token->getToken() != '';
            });

            // Filter these tokens by tokens that are in use.

            /** @var TokenInterface $token */
            foreach ($tokens as $token) {
                $result[] = [
                    'id' => $token->getToken(),
                    'name' => "{$token->getFirstName()} {$token->getLastName()} ({$token->getToken()}) " . implode(', ', array_filter($token->getCustomAttributes()))
                ];
            }
        }
        return [
            'output' => $result,
            'selected' => ''
        ];
    }
}