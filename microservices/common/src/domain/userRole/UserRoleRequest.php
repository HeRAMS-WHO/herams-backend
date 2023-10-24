<?php

declare(strict_types=1);

namespace herams\common\domain\userRole;

use herams\common\models\RequestModel;
use herams\common\values\userRole\UserRoleCreatedBy;
use herams\common\values\userRole\UserRoleCreatedDate;
use herams\common\values\userRole\UserRoleId;
use herams\common\values\userRole\UserRoleLastModifiedBy;
use herams\common\values\userRole\UserRoleLastModifiedDate;
use herams\common\values\userRole\UserRoleRoleId;
use herams\common\values\userRole\UserRoleTargetEnum;
use herams\common\values\userRole\UserRoleTargetId;
use herams\common\values\userRole\UserRoleUserId;
use yii\validators\RequiredValidator;

final class UserRoleRequest extends RequestModel
{

    public null|UserRoleId $id = null;
    //Put a path anotation

    public null|UserRoleUserId $userId = null;
    public null|UserRoleRoleId $roleId = null;
    public null|UserRoleTargetEnum $target = null;
    public null|UserRoleTargetId $targetId = null;
    public null|UserRoleCreatedDate $createdDate = null;
    public null|UserRoleCreatedBy $createdBy = null;
    public null|UserRoleLastModifiedDate $lastModifiedDate = null;
    public null|UserRoleLastModifiedBy $lastModifiedBy = null;

    public function rules(): array
    {
        return [
            [
                [
                    'id',
                    'userId',
                    'roleId',
                    'target',
                    'targetId',
                    'createdDate',
                    'createdBy',
                    'lastModifiedDate',
                    'lastModifiedBy',
                ],
                RequiredValidator::class
            ],
        ];
    }
}
