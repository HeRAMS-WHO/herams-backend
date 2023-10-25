<?php

declare(strict_types=1);

namespace herams\common\domain\userRole;

use herams\common\helpers\ModelHydrator;
use herams\common\models\UserRole;
use herams\common\values\userRole\UserRoleId;
use InvalidArgumentException;

final class UserRoleRepository
{
    /**
     * UserRoleRepository constructor.
     *
     * @param  ModelHydrator  $modelHydrator
     */
    public function __construct(
        private ModelHydrator $modelHydrator,
    ) {
    }

    /**
     * @param  UserRoleRequest  $userRoleRequest
     *
     * @return UserRoleId
     * @throws InvalidArgumentException
     */
    public function create(UserRoleRequest $userRoleRequest): UserRoleId
    {
        $record = new UserRole();
        $this->modelHydrator->hydrateActiveRecord($userRoleRequest, $record);
        if (!$record->save()) {
            throw new InvalidArgumentException(
                'Validation failed: '.print_r($record->errors, true)
            );
        }
        return new UserRoleId($record->id);
    }

}
