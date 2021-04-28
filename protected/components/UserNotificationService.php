<?php
declare(strict_types=1);

namespace prime\components;

use prime\models\ar\AccessRequest;
use prime\models\ar\Permission;
use prime\models\ar\User;
use prime\objects\UserNotification;
use SamIT\abac\AuthManager;
use yii\base\Component;
use yii\data\ArrayDataProvider;
use yii\data\DataProviderInterface;

/**
 * Class UserNotificationService
 * @package prime\components
 */
class UserNotificationService extends Component
{
    private int $_newNotificationCount;

    public function __construct(
        private AuthManager $abacManager,
        $config = []
    ) {
        parent::__construct($config);
    }

    public function getNewNotificationCount(User $user): int
    {
        if (!isset($this->_newNotificationCount)) {
            $this->_newNotificationCount = $this->getNotifications($user)->getTotalCount();
        }

        return $this->_newNotificationCount;
    }

    public function getNotifications(User $user): DataProviderInterface
    {
        $result = [];

        /** @var AccessRequest $accessRequest */
        foreach (AccessRequest::find()->notExpired()->withoutResponse()->each() as $accessRequest) {
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
