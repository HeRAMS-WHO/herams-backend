<?php

declare(strict_types=1);

namespace prime\controllers\workspace;

use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\values\WorkspaceId;
use prime\components\BreadcrumbService;
use prime\components\Controller;
use prime\components\NotificationService;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Resolver;
use SamIT\Yii2\UrlSigner\UrlSigner;
use yii\base\Action;
use yii\mail\MailerInterface;
use yii\web\Request;
use yii\web\User;

use function iter\toArray;

final class Users extends Action
{
    public function run(
        NotificationService $notificationService,
        WorkspaceRepository $workspaceRepository,
        Request $request,
        AuthManager $abacManager,
        Resolver $abacResolver,
        User $user,
        MailerInterface $mailer,
        UrlSigner $urlSigner,
        BreadcrumbService $breadcrumbService,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $workspaceId = new WorkspaceId($id);
        $this->controller->view->breadcrumbCollection->add(
            ...
            toArray(
                $breadcrumbService->retrieveForWorkspace($workspaceId)
                    ->getIterator()
            )
        );
        $workspace = $workspaceRepository->retrieveById($workspaceId);

        return $this->controller->render('users', [
            'model'        => $workspace,
            'workspace'    => $workspace,
            'tabMenuModel' => $workspaceRepository->retrieveForTabMenu(
                $workspaceId
            ),
        ]);
    }
}
