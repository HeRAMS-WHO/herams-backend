<?php

declare(strict_types=1);

namespace prime\controllers\project;

use herams\common\interfaces\AccessCheckInterface;
use herams\common\models\PermissionOld;
use herams\common\values\ProjectId;
use prime\components\BreadcrumbService;
use prime\components\Controller;
use prime\components\NotificationService;
use prime\exceptions\NoGrantablePermissions;
use prime\models\ar\read\Project;
use prime\models\forms\Share as ShareForm;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Resolver;
use SamIT\Yii2\UrlSigner\UrlSigner;
use yii\base\Action;
use yii\mail\MailerInterface;
use yii\web\Request;
use yii\web\User;
use function iter\toArray;

class Users extends Action
{
    public function run(
        Request $request,
        AccessCheckInterface $accessCheck,
        NotificationService $notificationService,
        AuthManager $abacManager,
        Resolver $abacResolver,
        User $user,
        MailerInterface $mailer,
        UrlSigner $urlSigner,
        BreadcrumbService $breadcrumbService,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $project = Project::findOne([
            'id' => $id,
        ]);
        $projectId = new ProjectId($id);
        $accessCheck->requirePermission($project, PermissionOld::PERMISSION_SHARE, \Yii::t('app', 'You are not allowed to share this project'));

        $this->controller->view->breadcrumbCollection->add(
            ...toArray($breadcrumbService->retrieveForProject($projectId)->getIterator())
        );


        return $this->controller->render('users', [
            'project' => $project,
        ]);
    }
}
