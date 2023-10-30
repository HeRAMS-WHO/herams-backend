<?php

namespace herams\common\domain\role;

use herams\common\attributes\Field;
use herams\common\values\role\RoleCreatedBy;
use herams\common\values\role\RoleCreatedDate;
use herams\common\values\role\RoleId;
use herams\common\values\role\RoleLastModifiedBy;
use herams\common\values\role\RoleLastModifiedDate;
use herams\common\values\role\RoleName;
use herams\common\values\role\RoleProjectId;
use herams\common\values\role\RoleScopEnum;
use herams\common\values\role\RoleTypeEnum;

class RoleRequest
{
    public null|RoleId $id = null;
    public null|RoleName $name = null;
    public null|RoleTypeEnum $scope = null;
    public null|RoleScopEnum $type = null;
    #[Field('project_id')]
    public null|RoleProjectId $project_id = null;
    #[Field('created_date')]
    public null|RoleCreatedDate $createdDate = null;
    #[Field('created_by')]
    public null|RoleCreatedBy $createdBy = null;
    #[Field('last_modified_date')]
    public null|RoleLastModifiedDate $lastModifiedDate = null;
    #[Field('last_modified_by')]
    public null|RoleLastModifiedBy $lastModifiedBy = null;
}