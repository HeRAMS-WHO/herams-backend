<?php

declare(strict_types=1);

namespace herams\api\controllers\userRole;

use herams\common\domain\role\RoleRepository;
use herams\common\domain\userRole\UserRoleRepository;
use herams\common\domain\userRole\UserRoleRequest;
use herams\common\helpers\CommonFieldsInTables;
use herams\common\helpers\ModelHydrator;
use herams\common\values\role\RoleTypeEnum;
use herams\common\values\userRole\UserRoleTargetEnum;
use InvalidArgumentException;
use yii\base\Action;
use yii\web\HttpException;
use yii\web\Request;
use yii\web\Response;

final class Create extends Action
{
    public function run(
        UserRoleRepository $userRoleRepository,
        RoleRepository $roleRepository,
        ModelHydrator $modelHydrator,
        Request $request,
        Response $response,
    ) {
        $data = $request->bodyParams;
        $this->validateIfAllRolesAreCompatible($roleRepository, $data);
        $target = UserRoleTargetEnum::tryFrom($data['scope']);
        $call = $this->execute($target);
        if (is_callable([$this, $call])) {
            $this->$call($data, $userRoleRepository, $modelHydrator);
            $response->setStatusCode(201);
            return $response;
        }
        throw new HttpException(500, "Method {$call} should be implemented");
    }

    /**
     * @param  RoleRepository  $roleRepository
     * @param  object|array  $data
     *
     * @throws HttpException
     */
    private function validateIfAllRolesAreCompatible(
        RoleRepository $roleRepository,
        object|array $data
    ): void {
        if (!$roleRepository->checkIfEveryRoleHasScope(
            $data['roles'],
            RoleTypeEnum::from($data['scope'])
        )
        ) {
            throw new HttpException(
                400,
                "Not every role has scope {$data['scope']}"
            );
        }
    }

    /**
     * @param  UserRoleTargetEnum|null  $target
     *
     * @return string
     * @throws InvalidArgumentException If the UserRoleTargetEnum is invalid.
     */
    private function execute(UserRoleTargetEnum|null $target): string
    {
        if ($target === UserRoleTargetEnum::project) {
            return "createUserRoleForProject";
        }
        if ($target === UserRoleTargetEnum::workspace) {
            return "createUserRoleForWorkspaces";
        }
        throw new InvalidArgumentException('Invalid target');
    }

    /**
     * @param  array  $data
     * @param  UserRoleRepository  $userRoleRepository
     * @param  ModelHydrator  $modelHydrator
     */
    private function createUserRoleForProject(
        array $data,
        UserRoleRepository $userRoleRepository,
        ModelHydrator $modelHydrator
    ): void {
        foreach ($data['users'] as $userId) {
            foreach ($data['roles'] as $roleId) {
                $jsonDictionary = $this->getJsonDictionary(
                    (int)$userId,
                    (int)$roleId,
                    (int)$data['project_id'],
                    UserRoleTargetEnum::project
                );
                $this->createUserRole(
                    $modelHydrator,
                    $jsonDictionary,
                    $userRoleRepository
                );
            }
        }
    }

    /**
     * @param  int  $userId
     * @param  int  $roleId
     * @param  int  $targetId
     * @param  UserRoleTargetEnum  $target
     *
     * @return array
     */
    private function getJsonDictionary(
        int $userId,
        int $roleId,
        int $targetId,
        UserRoleTargetEnum $target,
    ): array {
        return [
            'userId'   => $userId,
            'roleId'   => $roleId,
            'targetId' => $targetId,
            'target'   => $target->value,
            ...CommonFieldsInTables::forCreatingHydratation()
        ];
    }

    /**
     * @param  ModelHydrator  $modelHydrator
     * @param  array  $jsonDictionary
     * @param  UserRoleRepository  $userRoleRepository
     */
    private function createUserRole(
        ModelHydrator $modelHydrator,
        array $jsonDictionary,
        UserRoleRepository $userRoleRepository
    ): void {
        $requestModel = new UserRoleRequest();
        $modelHydrator->hydrateFromJsonDictionary(
            $requestModel,
            $jsonDictionary
        );
        $userRoleRepository->create($requestModel);
    }

    /**
     * @param  array  $data
     * @param  UserRoleRepository  $userRoleRepository
     * @param  ModelHydrator  $modelHydrator
     */
    private function createUserRoleForWorkspaces(
        array $data,
        UserRoleRepository $userRoleRepository,
        ModelHydrator $modelHydrator
    ): void {
        foreach ($data['users'] as $userId) {
            foreach ($data['roles'] as $roleId) {
                foreach ($data['workspaces'] as $workspaceId) {
                    $jsonDictionary = $this->getJsonDictionary(
                        (int)$userId,
                        (int)$roleId,
                        (int)$workspaceId,
                        UserRoleTargetEnum::workspace
                    );
                    $this->createUserRole(
                        $modelHydrator,
                        $jsonDictionary,
                        $userRoleRepository
                    );
                }
            }
        }
    }
}
