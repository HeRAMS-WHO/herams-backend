<?php
declare(strict_types=1);

namespace prime\repositories;

use prime\models\ar\AccessRequest;
use prime\models\ar\Permission;
use prime\models\ar\User;
use prime\objects\UserNotification;
use SamIT\abac\AuthManager;
use yii\base\Component;
use yii\data\ArrayDataProvider;
use yii\data\DataProviderInterface;

class UserNotificationRepository extends Component
{
    public function __construct(
        private AuthManager $abacManager,
        private AccessRequestRepository $accessRequestRepository,
        $config = []
    ) {
        parent::__construct($config);
    }

    public function getNewNotificationCountForUser(User $user): int
    {
        // For now the new notifications are all notifications there are since there are no notifications
        // that can be marked as read or so
        return $this->getNotificationsForUser($user)->getTotalCount();
    }

    public function getNotificationsForUser(User $user): DataProviderInterface
    {
        $result = [];

        /** @var AccessRequest $accessRequest */
        foreach ($this->accessRequestRepository->find()->notExpired()->withoutResponse()->each() as $accessRequest) {
            if ($this->abacManager->check($user, $accessRequest, Permission::PERMISSION_RESPOND)) {
                $result[] = new UserNotification(
                    \Yii::t('app', 'There are open access requests that you can respond to.'),
                    ['access-request/index']
                );
                break;
            }
        }

        return \Yii::createObject(ArrayDataProvider::class, [[
            'allModels' => $result,
        ]]);
    }
}
