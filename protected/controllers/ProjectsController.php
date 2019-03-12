<?php

namespace prime\controllers;

use app\queries\WorkspaceQuery;
use app\queries\ToolQuery;
use prime\components\Controller;
use prime\components\LimesurveyDataProvider;
use prime\controllers\projects\Close;
use prime\controllers\projects\Download;
use prime\controllers\projects\View;
use prime\models\ar\Workspace;
use prime\models\ar\Setting;
use prime\models\ar\Project;
use prime\models\forms\projects\CreateUpdate;
use prime\models\forms\projects\Token;
use prime\models\forms\Share;
use prime\models\permissions\Permission;
use prime\models\search\Workspace as ProjectSearch;
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
        /** @var Workspace $model */
        $model = Workspace::loadOne($id, [], Permission::PERMISSION_WRITE);
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

    public function actionReOpen(Session $session, Request $request, $id)
    {
        if (!$request->isPut) {
            throw new HttpException(405);
        } else {
            $model = Workspace::loadOne($id, [], Permission::PERMISSION_ADMIN);
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




    public function actionUpdateLimeSurvey($id)
    {
        $model = Workspace::loadOne($id, [], Permission::PERMISSION_WRITE);
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
        LimesurveyDataProvider $limesurveyDataProvider,
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
            $usedTokens = array_flip(Workspace::find()->select('token')->column());
            $tokens = $limesurveyDataProvider->getTokens($surveyId);
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
            'close' => Close::class,
        ];
    }


}
