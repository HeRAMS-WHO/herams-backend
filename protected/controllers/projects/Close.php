<?php


namespace prime\controllers\projects;


use DateTime;
use prime\components\NotificationService;
use prime\models\ar\Workspace;
use prime\models\permissions\Permission;
use yii\base\Action;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Session;

class Close extends Action
{
    /** @var Request */
    private $request;
    /** @var Session */
    private $session;

    public function __construct(
        string $id,
        Controller $controller,
        Request $request,
        Session $session,
        array $config = []
    ) {
        parent::__construct($id, $controller, $config);
        $this->request = $request;
        $this->session = $session;
    }

    public function run(
        NotificationService $notificationService,
        int $id
    ) {

        $model = Workspace::loadOne($id, [], Permission::PERMISSION_ADMIN);
        $model->scenario = 'close';

        $model->closed = (new DateTime())->format('Y-m-d H:i:s');
        if($model->save()) {
            $notificationService->success(\Yii::t('app', "Project <strong>{modelName}</strong> has been closed.", [
                'modelName' => $model->title
            ]));
            return $this->controller->redirect($this->request->referrer);
        }
        if(isset($model)) {
            return $this->controller->redirect(['/projects/read', 'id' => $model->id]);
        } else {
            return $this->controller->redirect(['/projects/list']);
        }
    }
}