<?php

declare(strict_types=1);

use herams\common\domain\facility\FacilityRepository;

return function (\herams\common\interfaces\EnvironmentInterface $env, \yii\di\Container $container) {
    $container->set(\yii\rest\Serializer::class, \herams\api\components\Serializer::class);

    $container->set(\yii\filters\auth\CompositeAuth::class, [
        'optional' => ['health/*'],
        'authMethods' => [
            \herams\api\components\JwtAuth::class,
        ],
    ]);

    $container->setDefinitions([
        \herams\common\interfaces\CommandHandlerInterface::class => \herams\common\services\SynchronousCommandHandler::class,
        FacilityRepository::class => FacilityRepository::class,
        \herams\common\domain\permission\PermissionRepository::class => \herams\common\domain\permission\PermissionRepository::class,
        \herams\common\domain\user\UserRepository::class => \herams\common\domain\user\UserRepository::class,
        \herams\common\domain\page\PageRepository::class => \herams\common\domain\page\PageRepository::class,
        \herams\common\helpers\ModelValidator::class => \herams\common\helpers\ModelValidator::class,
        \herams\common\helpers\ModelHydrator::class => \herams\common\helpers\ModelHydrator::class,
        \herams\common\interfaces\SurveyRepositoryInterface::class => \herams\common\domain\survey\SurveyRepository::class,
        \herams\common\domain\surveyResponse\SurveyResponseRepository::class => \herams\common\domain\surveyResponse\SurveyResponseRepository::class,
        \herams\common\interfaces\CurrentUserIdProviderInterface::class => \herams\common\helpers\CurrentUserIdProvider::class,
    ]);
};
