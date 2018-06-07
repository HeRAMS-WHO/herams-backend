<?php

namespace prime\controllers;

use app\queries\ProjectQuery;
use app\queries\ToolQuery;
use Befound\Components\DateTime;
use kartik\depdrop\DepDropAction;
use prime\components\Controller;
use prime\models\ar\Setting;
use prime\models\forms\projects\CreateUpdate;
use prime\models\forms\Share;
use prime\models\forms\projects\Token;
use prime\models\permissions\Permission;
use prime\models\ar\Project;
use prime\models\ar\Tool;
use prime\models\search\Project as ProjectSearch;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use SamIT\LimeSurvey\Interfaces\TokenInterface;
use SamIT\LimeSurvey\JsonRpc\Client;
use SamIT\LimeSurvey\JsonRpc\Concrete\Survey;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\Request;
use yii\web\Session;
use yii\web\User;
use yii\web\Response;
use Lcobucci\JWT\Builder;

class ProjectsController extends Controller
{
    public $layout = 'simple';
    public $defaultAction = 'list';

    public function actionClose(Session $session, Request $request, $id)
    {
        if (!$request->isDelete) {
            throw new HttpException(405);
        } else {
            $model = Project::loadOne($id, [], Permission::PERMISSION_ADMIN);
            $model->scenario = 'close';

            $model->closed = (new DateTime())->format(DateTime::MYSQL_DATETIME);
            if($model->save()) {
                $session->setFlash(
                    'projectClosed',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => \Yii::t('app', "Project <strong>{modelName}</strong> has been closed.", ['modelName' => $model->title]),
                        'icon' => 'glyphicon glyphicon-' . Setting::get('icons.close')
                    ]
                );
                return $this->redirect($request->referrer);
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

    /**
     * Action for creating a new project.
     * @param CreateUpdate $model
     * @param Request $request
     * @param Session $session
     * @return \Befound\Components\type|Response
     */
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
    public function actionList(
        Request $request,
        int $toolId = null
    ) {

        $projectSearch = new ProjectSearch($toolId, [
            'queryCallback' => function(ProjectQuery $query) {
                return $query->readable();
            }
        ]);

        $projectSearch->tool_id = $toolId;
        $projectsDataProvider = $projectSearch->search($request->queryParams);

        return $this->render('list', [
            'projectSearch' => $projectSearch,
            'projectsDataProvider' => $projectsDataProvider,
            'tool' => isset($toolId) ? Tool::findOne(['id' => $toolId]) : null
        ]);
    }

    public function actionListOthers(Request $request, int $toolId = null)
    {
        $projectSearch = new ProjectSearch($toolId, [
            'queryCallback' => function(ProjectQuery $query) {
                return $query->notReadable();
            }
        ]);
        $projectsDataProvider = $projectSearch->search($request->queryParams);
        return $this->render('list', [
            'projectSearch' => $projectSearch,
            'projectsDataProvider' => $projectsDataProvider,
            'tool' => isset($toolId) ? Tool::findOne(['id' => $toolId]) : null
        ]);
    }

    public function actionListClosed(
        Request $request,
        int $toolId = null
    ) {
        $projectSearch = new ProjectSearch($toolId);
        $projectSearch->query = Project::find()->closed()->userCan(Permission::PERMISSION_WRITE);
        if(!app()->user->can('admin')) {
            $projectSearch->query->joinWith(['tool' => function(ToolQuery $query) {return $query->notHidden();}]);
        } else {
            $projectSearch->query->joinWith(['tool']);
        }
        $projectsDataProvider = $projectSearch->search($request->queryParams);

        return $this->render('listDeleted', [
            'projectSearch' => $projectSearch,
            'projectsDataProvider' => $projectsDataProvider
        ]);
    }

    public function actionProgress(Response $response, $id)
    {
        $project = Project::loadOne($id);
        $report = $project->getProgressReport();
        if (!isset($report)) {
            throw new \HttpException(404, "Progress report for project not found.");
        }
        $response->setContentType($report->getMimeType());
        $response->content = $report->getStream();
        return $response;
    }

    public function actionRead($id)
    {
        $project = Project::loadOne($id);
        $this->layout = 'angular';

        return $this->render('overview', [
            'model' => $project,
        ]);
    }

    private function getAnswer(QuestionInterface $q, $value, $text = false)
    {
        if (empty($value)) {
            return "(not set)";
        } elseif ($text && (null !== $answers = $q->getAnswers())) {
            foreach($answers as $answer) {
                if ($answer->getCode() == $value) {
                    return $answer->getText();
                }
            }
            return "Invalid answer : `$value`.";
        } else {
            return $value;
        }

    }

    public function actionDownload(Client $limeSurvey, Response $response, $id, $text = false)
    {
        $project = Project::loadOne($id, [], Permission::PERMISSION_ADMIN);
        /** @var Survey $survey */
        $survey = $project->getSurvey()->get($project->data_survey_eid);
        /** @var QuestionInterface[] $questions */
        $questions = [];
        foreach($survey->getGroups() as $group) {
            foreach($group->getQuestions() as $question) {
                $questions[$question->getTitle()] = $question;
            }
        }
        $rows = [];
        $codes = [];
        foreach($project->getResponses() as $record) {
            $row = [];
            foreach ($record->getData() as $code => $value) {
                if (null !== $question = $survey->getQuestionByCode($code)) {
                    $text = $question->getText();
                    $answer = $this->getAnswer($question, $value, $text);


                } elseif (preg_match('/^(.+)\[(.*)\]$/', $code,
                        $matches) && null !== $question = $survey->getQuestionByCode($matches[1])
                ) {
                    if (null !== $sub = $question->getQuestionByCode($matches[2])) {
                        $text = $sub->getText();
                        $answer = $this->getAnswer($sub, $value, $text);
                    } elseif ($question->getDimensions() == 2 && preg_match('/^(.+)_(.+)$/', $matches[2],
                            $subMatches)
                    ) {
                        if (null !== ($sub = $question->getQuestionByCode($subMatches[1], 0))
                            && null !== $sub2 = $question->getQuestionByCode($subMatches[2], 1)
                        ) {
                            $text = $sub->getText() . ' - ' . $sub2->getText();
                            $answer = $this->getAnswer($sub2, $value, $text);
                        } else {
                            throw new \RuntimeException("Could not find subquestions for 2 dimensional question.");

                        }
                    } else {
                        $text = "Not found";
                        $answer = $value;
                    }
                } else {
                    $text = $code;
                    $answer = $value;
                }
//                echo str_pad($code, 20) . " | " . str_pad(is_null($value) ? 'NULL' : $value, 20) . " | ";
//                echo str_pad(trim(strip_tags($answer)), 40) . ' | ';
//                echo trim(strip_tags($text));
//                echo "\n";
                $codes[$text] = $code;
                $row[$text] = $answer;
            }
            $rows[] = $row;
        }

        $stream = fopen('php://temp', 'w+');
        // First get all columns.
        $columns = [];
        foreach($rows as $row) {
            foreach($row as $key => $dummy) {
                $columns[$key] = true;
            }
        }

        if (!empty($columns)) {
            fputcsv($stream, array_keys($columns));
            $header = [];
            foreach(array_keys($columns) as $columnName) {
                $header[] = $codes[$columnName];
            }
            fputcsv($stream, $header);

            /** @var ResponseInterface $record */
            foreach ($rows as $data) {
                $row = [];
                foreach(array_keys($columns) as $column) {
                    $row[$column] = isset($data[$column]) ? $data[$column] : null;
                }
                fputcsv($stream, $row);
            }
        }
        return $response->sendStreamAsFile($stream, "{$project->title}.csv", [
            'mimeType' => 'text/csv'
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
                return $this->redirect(['projects/read', 'id' => $model->id]);
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

    /**
     * Data exploration.
     * @return string
     */
    public function actionExplore(User $user, $id)
    {
        $project = Project::loadOne($id);

        $builder = new Builder();
        $builder
            ->setIssuedAt(time())
            ->setExpiration(time() + 600)
            ->set('userId', $user->id)
            ->setIssuer("https://primewho.org")
            ->setAudience('https://primewho.org')
            ->sign(new \Lcobucci\JWT\Signer\Rsa\Sha512(), file_get_contents(__DIR__ . '/../config/private.key'));


        /** @var \Lcobucci\JWT\Token $token */
        $token = $builder->getToken();
        return $this->render('explore', ['model' => $project, 'token' => $token]);

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
                            'actions' => ['close', 'configure', 'list', 'list-others', 'list-closed',
                            'progress', 'read', 'download', 're-open', 'share', 'share-delete',
                                'update', 'update-lime-survey', 'explore', 'new',
                            ],
                            'roles' => ['@'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['create', 'dependent-generators', 'dependent-tokens', 'create', 'dependent-surveys'],
                            'roles' => ['createProject']
                        ]
                    ]
                ]
            ]
        );
    }

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'dependent-surveys' => [
                'class' => DepDropAction::class,
                'outputCallback' => function($id, $params) {
                    $project = new Project();
                    $project->tool_id = $id;
                    $result = [];
                    if ($project->validate(['tool_id'])) {
                        foreach ($project->dataSurveyOptions() as $key => $value) {
                            $result[] = [
                                'id' => $key,
                                'name' => $value
                            ];

                        }
                    }

                    return $result;
                }
            ]
        ]);
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
}
