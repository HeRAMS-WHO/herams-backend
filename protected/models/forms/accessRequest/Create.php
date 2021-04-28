<?php
declare(strict_types=1);

namespace prime\models\forms\accessRequest;

use prime\models\ar\AccessRequest;
use prime\models\ar\Permission;
use prime\models\ar\User;
use SamIT\abac\AuthManager;
use yii\base\Model;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;
use yii\web\ServerErrorHttpException;

/**
 * Class RequestAccess
 * @package prime\models\forms
 */
class Create extends Model
{
    public string $body = '';
    private array $permissionOptions = [];
    public array $permissions = [];
    public array $permissionMap = [
        AccessRequest::PERMISSION_READ => Permission::PERMISSION_READ,
        AccessRequest::PERMISSION_WRITE => Permission::PERMISSION_WRITE,
        AccessRequest::PERMISSION_EXPORT => Permission::PERMISSION_EXPORT,
    ];
    public string $subject = '';

    public function __construct(
        private object $target,
        array $permissionOptions,
        AuthManager $authManager,
        User $user,
        $config = []
    ) {
        foreach ($permissionOptions as $arPermission => $permissionDescription) {
            if (!isset($this->permissionMap[$arPermission]) || !$authManager->check($user, $target, $this->permissionMap[$arPermission])) {
                $this->permissionOptions[$arPermission] = $permissionDescription;
            }
        }
        parent::__construct($config);
    }

    public function createRecords(): void
    {
        $accessRequest = new AccessRequest();
        $accessRequest->body = $this->body;
        $accessRequest->permissions = $this->permissions;
        $accessRequest->subject = $this->subject;
        $accessRequest->target = $this->target;
        if (!$accessRequest->save()) {
            throw new ServerErrorHttpException('Failed saving the record.');
        }
    }

    public function getPermissionOptions(): array
    {
        return $this->permissionOptions;
    }

    public function rules(): array
    {
        return [
            [['body', 'permissions', 'subject'], RequiredValidator::class],
            [['body', 'subject'], StringValidator::class],
            [['permissions'], RangeValidator::class, 'range' => array_keys($this->permissionOptions), 'allowArray' => true],
        ];
    }
}