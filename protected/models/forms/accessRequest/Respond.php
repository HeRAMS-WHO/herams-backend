<?php

declare(strict_types=1);

namespace prime\models\forms\accessRequest;

use Carbon\Carbon;
use herams\common\domain\permission\ProposedGrant;
use herams\common\models\Permission;
use herams\common\models\Project;
use herams\common\models\Workspace;
use JCIT\jobqueue\interfaces\JobQueueInterface;
use prime\jobs\accessRequests\ResponseNotificationJob;
use prime\models\ar\AccessRequest;
use SamIT\abac\AuthManager;
use yii\base\Model;
use yii\validators\DefaultValueValidator;
use yii\validators\FilterValidator;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;
use yii\web\IdentityInterface;

class Respond extends Model
{
    private array $permissionOptions = [];

    public $permissions = [];

    public string $response = '';

    public function __construct(
        private AccessRequest $accessRequest,
        private AuthManager $abacManager,
        private IdentityInterface $identity,
        private JobQueueInterface $jobQueue,
        ?array $permissionOptions = null,
        $config = []
    ) {
        $this->setPermissionOptions($permissionOptions);
        parent::__construct($config);
    }

    public function attributeLabels(): array
    {
        return [
            'permissions' => \Yii::t('app', 'Permissions to grant'),
            'response' => \Yii::t('app', 'Explanation of grant or deny'),
        ];
    }

    public function createRecords()
    {
        $transaction = $this->accessRequest::getDb()->beginTransaction();
        $transactionLevel = $transaction->level;

        try {
            $this->accessRequest->response = $this->response;
            $this->accessRequest->responded_by = $this->identity->id;
            $this->accessRequest->accepted = ! empty($this->permissions);
            $this->accessRequest->responded_at = Carbon::now();
            if (! $this->accessRequest->save()) {
                throw new \RuntimeException('Failed saving the access request.');
            }

            foreach ($this->permissions as $permission) {
                $grant = new ProposedGrant(
                    $this->accessRequest->createdByUser,
                    $this->accessRequest->target,
                    $permission
                );

                if ($this->abacManager->check($this->identity, $grant, Permission::PERMISSION_CREATE)) {
                    $this->abacManager->grant(
                        $this->accessRequest->createdByUser,
                        $this->accessRequest->target,
                        $permission
                    );
                } else {
                    throw new \RuntimeException('You are not allowed to create this grant.');
                }
            }

            $transaction->commit();

            $this->jobQueue->putJob(new ResponseNotificationJob($this->accessRequest->id));
        } finally {
            if ($transaction->isActive && $transaction->level == $transactionLevel) {
                $transaction->rollBack();
            }
        }
    }

    public function getAccessRequest(): AccessRequest
    {
        return $this->accessRequest;
    }

    public function getPermissionOptions(): array
    {
        return $this->permissionOptions;
    }

    private function setPermissionOptions(?array $permissionOptions = null): void
    {
        $this->permissionOptions = [];
        $defaultPermissionOptions = [
            Project::class => [
                Permission::PERMISSION_READ,
                Permission::PERMISSION_SURVEY_DATA,
                Permission::PERMISSION_EXPORT,
                Permission::PERMISSION_MANAGE_WORKSPACES,
                Permission::PERMISSION_CREATE_FACILITY,
                Permission::PERMISSION_MANAGE_DASHBOARD,
                Permission::PERMISSION_WRITE,
                Permission::PERMISSION_SHARE,
                Permission::PERMISSION_SURVEY_BACKEND,
                Permission::PERMISSION_SUPER_SHARE,

                Permission::ROLE_LEAD => \Yii::t('app', 'Project coordinator'),
            ],
            Workspace::class => [
                Permission::PERMISSION_SURVEY_DATA,
                Permission::PERMISSION_CREATE_FACILITY,
                Permission::PERMISSION_EXPORT,
                Permission::PERMISSION_SHARE,
                Permission::PERMISSION_SUPER_SHARE,

                Permission::ROLE_LEAD => \Yii::t('app', 'Workspace owner'),
            ],
        ];

        $permissionOptions = $permissionOptions ?? $defaultPermissionOptions[$this->accessRequest->target_class];

        foreach ($permissionOptions as $permission => $label) {
            if (is_numeric($permission)) {
                $permission = $label;
                $label = Permission::permissionLabels()[$permission] ?? $permission;
            }
            $grant = new ProposedGrant($this->getAccessRequest()->createdByUser, $this->accessRequest->target, $permission);
            if ($this->abacManager->check($this->identity, $grant, Permission::PERMISSION_CREATE)) {
                $this->permissionOptions[$permission] = $label;
            }
        }
    }

    public function rules(): array
    {
        return [
            [['response'],
                FilterValidator::class,
                'filter' => 'trim',
            ],
            [['response'], RequiredValidator::class],
            [['response'], StringValidator::class],
            [['permissions'],
                DefaultValueValidator::class,
                'value' => [],
            ],
            [['permissions'],
                RangeValidator::class,
                'range' => array_keys($this->permissionOptions),
                'allowArray' => true,
            ],
        ];
    }
}
