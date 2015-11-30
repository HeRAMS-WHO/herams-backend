<?php

namespace prime\controllers;

use app\components\Html;
use Befound\Components\DateTime;
use prime\components\Controller;
use prime\models\Country;
use prime\models\forms\projects\CreateUpdate;
use prime\models\forms\projects\Share;
use prime\models\forms\projects\Token;
use prime\models\permissions\Permission;
use prime\models\ar\Project;
use prime\models\ar\Tool;
use SamIT\LimeSurvey\JsonRpc\Client;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Request;
use yii\web\Session;
use yii\web\User;
use yii\web\Response;

class ProjectsController extends Controller
{
    public $defaultAction = 'list';

    public function actionClose(Session $session, Request $request, $id)
    {
        $model = Project::loadOne($id, Permission::PERMISSION_WRITE);
        $model->scenario = 'close';
        if($request->isDelete) {
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

    public function actionConfigure(Client $limesurvey, $id)
    {
        /** @var Project $model */
        $model = Project::loadOne($id, Permission::PERMISSION_WRITE);
        $ad = $limesurvey->getSurveyProperties($model->data_survey_eid, [
            'attributedescriptions'
        ])['attributedescriptions'];

        // Always attempt creation.
        $limesurvey->createToken($model->data_survey_eid, ['token' => $model->token]);
        $data = $limesurvey->getToken($model->data_survey_eid, $model->token);
        // Try json_decode first.
        if (null === $descriptions = json_decode($ad, true)) {
            $descriptions = unserialize($ad);
        }
        $token = new Token();
        $token->loadAttributesFromDescriptions($descriptions);
        $token->setAttributes($data, false);
        foreach([
            'firstname' => $model->getLocality(),
            'lastname' => $model->owner->lastName,
            'Country' => $model->getCountry()->name,
            'SubNational' => $model->getLocality(),
            'validfrom' => $model->created

        ] as $attribute => $value) {
            if ($token->canSetProperty($attribute)) {
                $token->$attribute = $value;
            }

        }

        foreach(ArrayHelper::getColumn($descriptions, 'description') as $description) {
//            vdd($mode)
        }

//        $this->render('configure')
        vd($token->attributes);
        vdd($descriptions);
    }

    public function actionCreate(Request $request, Session $session, Client $limesurvey)
    {
        $model = new CreateUpdate();
        $model->scenario = 'create';

        if ($request->isPost) {
            if($model->load($request->bodyParams) && $model->validate()) {


                $session->setFlash(
                    'projectCreated',
                    [
                        'type' => \kartik\widgets\Growl::TYPE_SUCCESS,
                        'text' => \Yii::t('app', "Project <strong>{modelName}</strong> has been created.", ['modelName' => $model->title]),
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );

                $ad = $limesurvey->getSurveyProperties($model->data_survey_eid, [
                    'attributedescriptions'
                ])['attributedescriptions'];

                // Try json_decode first.
                if (null === $descriptions = json_decode($ad, true)) {
                    $descriptions = unserialize($ad);
                }
                if (!empty($descriptions)) {
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
            'query' => Tool::find()
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
        $project = Project::loadOne($id, Permission::PERMISSION_SHARE);

        $model = new Share([
            'projectId' => $project->id
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
                                'modelName' => $model->project->title,
                                'users' => implode(', ', array_map(function($model){return $model->name;}, $model->getUsers()->all()))
                            ]),
                        'icon' => 'glyphicon glyphicon-ok'
                    ]
                );
                return $this->redirect(['projects/read', 'id' => $model->project->id]);
            }
        }

        return $this->render('share', [
            'model' => $model
        ]);
    }

    public function actionUpdate(Request $request, Session $session, $id)
    {
        $model = CreateUpdate::loadOne($id, Permission::PERMISSION_WRITE);
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
                'access' => [
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@']
                        ],
                    ]
                ]
            ]
        );
    }
}