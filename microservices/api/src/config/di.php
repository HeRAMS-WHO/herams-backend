<?php

declare(strict_types=1);

use herams\api\components\JwtAuth;
use herams\api\components\Serializer;
use herams\common\domain\facility\FacilityRepository;
use herams\common\domain\page\PageRepository;
use herams\common\domain\permission\PermissionRepository;
use herams\common\domain\role\RoleRepository;
use herams\common\domain\survey\SurveyRepository;
use herams\common\domain\surveyResponse\SurveyResponseRepository;
use herams\common\domain\user\UserRepository;
use herams\common\domain\userRole\UserRoleRepository;
use herams\common\helpers\CurrentUserIdProvider;
use herams\common\helpers\ModelHydrator;
use herams\common\helpers\ModelValidator;
use herams\common\interfaces\CommandHandlerInterface;
use herams\common\interfaces\CurrentUserIdProviderInterface;
use herams\common\interfaces\EnvironmentInterface;
use herams\common\interfaces\SurveyRepositoryInterface;
use herams\common\services\SynchronousCommandHandler;
use yii\di\Container;
use yii\filters\auth\CompositeAuth;

return function (
    EnvironmentInterface $env,
    Container $container
) {
    $container->set(
        \yii\rest\Serializer::class,
        Serializer::class
    );

    $container->set(CompositeAuth::class, [
        'optional' => ['health/*'],
        'authMethods' => [
            JwtAuth::class,
        ],
    ]);

    $container->setDefinitions([
        CommandHandlerInterface::class => SynchronousCommandHandler::class,
        FacilityRepository::class => FacilityRepository::class,
        UserRoleRepository::class => UserRoleRepository::class,
        PermissionRepository::class => PermissionRepository::class,
        UserRepository::class => UserRepository::class,
        PageRepository::class => PageRepository::class,
        RoleRepository::class => RoleRepository::class,
        ModelValidator::class => ModelValidator::class,
        ModelHydrator::class => ModelHydrator::class,
        SurveyRepositoryInterface::class => SurveyRepository::class,
        SurveyResponseRepository::class => SurveyResponseRepository::class,
        CurrentUserIdProviderInterface::class => CurrentUserIdProvider::class,
    ]);
};
