<?php

declare(strict_types=1);

namespace herams\common\domain\userRole;

use herams\common\attributes\Field;
use herams\common\models\RequestModel;
use herams\common\values\role\RoleCreatedBy;
use herams\common\values\role\RoleCreatedDate;
use herams\common\values\userRole\UserRoleId;
use herams\common\values\userRole\UserRoleLastModifiedBy;
use herams\common\values\userRole\UserRoleLastModifiedDate;
use herams\common\values\userRole\UserRoleRoleId;
use herams\common\values\userRole\UserRoleTargetEnum;
use herams\common\values\userRole\UserRoleTargetId;
use herams\common\values\userRole\UserRoleUserId;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;

final class UserRoleRequest extends RequestModel
{
    public null|UserRoleId $id = null;
    //Put a path anotation

    #[Field('user_id')]
    public null|UserRoleUserId $userId = null;

    #[Field('role_id')]
    public null|UserRoleRoleId $roleId = null;

    #[Field('target')]
    public null|UserRoleTargetEnum $target = null;

    #[Field('target_id')]
    public null|UserRoleTargetId $targetId = null;

    #[Field('created_date')]
    public null|RoleCreatedDate $createdDate = null;

    #[Field('created_by')]
    public null|RoleCreatedBy $createdBy = null;

    #[Field('last_modified_date')]
    public null|UserRoleLastModifiedDate $lastModifiedDate = null;

    #[Field('last_modified_by')]
    public null|UserRoleLastModifiedBy $lastModifiedBy = null;

    public function rules(): array
    {
        return [
            [
                [
                    'userId',
                    'roleId',
                    'target',
                    'createdDate',
                    'createdBy',
                    'lastModifiedDate',
                    'lastModifiedBy',
                ],
                RequiredValidator::class,
            ],
            [
                ['targetId'],
                SafeValidator::class
            ]
        ];
    }
}
