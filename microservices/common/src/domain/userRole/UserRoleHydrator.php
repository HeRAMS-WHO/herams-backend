<?php

declare(strict_types=1);

namespace herams\common\domain\userRole;

use herams\common\attributes\SupportedType;
use herams\common\interfaces\ActiveRecordHydratorInterface;
use herams\common\models\ActiveRecord;
use herams\common\models\RequestModel;
use herams\common\models\UserRole;
use herams\common\values\userRole\UserRoleCreatedBy;
use herams\common\values\userRole\UserRoleCreatedDate;
use herams\common\values\userRole\UserRoleId;
use herams\common\values\userRole\UserRoleLastModifiedBy;
use herams\common\values\userRole\UserRoleLastModifiedDate;
use herams\common\values\userRole\UserRoleRoleId;
use herams\common\values\userRole\UserRoleTargetEnum;
use herams\common\values\userRole\UserRoleTargetId;
use herams\common\values\userRole\UserRoleUserId;

#[
    SupportedType(UserRole::class, UserRoleRequest::class)
]
class UserRoleHydrator implements ActiveRecordHydratorInterface
{
    /**
     * @param  UserRoleRequest  $source
     * @param  UserRole  $target
     */
    public function hydrateActiveRecord(
        RequestModel $source,
        ActiveRecord $target
    ): void {
        $target->id = $source->id?->getValue();
        $target->user_id = $source->userId?->getValue();
        $target->role_id = $source->roleId?->getValue();
        $target->target = $source->target->value;
        $target->target_id = $source->targetId?->getValue();
        $target->created_date = $source->createdDate->getValue();
        $target->created_by = $source->createdBy?->getValue();
        $target->last_modified_date = $source->lastModifiedDate?->getValue();
        $target->last_modified_by = $source->lastModifiedBy?->getValue();
    }

    /**
     * @param  UserRole  $source
     * @param  UserRoleRequest  $target
     */
    public function hydrateRequestModel(
        ActiveRecord $source,
        RequestModel $target
    ): void {
        $target->id = $source?->id ? new UserRoleId($source->id) : null;
        $target->userId = new UserRoleUserId($source->user_id);
        $target->roleId = new UserRoleRoleId($source->role_id);
        $target->target = UserRoleTargetEnum::getEnumValue($source->target);
        $target->targetId = new UserRoleTargetId($source->target_id);
        $target->createdDate = new UserRoleCreatedDate($source->created_date);
        $target->createdBy = new UserRoleCreatedBy($source->created_by);
        $target->lastModifiedDate = new UserRoleLastModifiedDate(
            $source->last_modified_date
        );
        $target->lastModifiedBy = new UserRoleLastModifiedBy(
            $source->last_modified_by
        );
    }
}
