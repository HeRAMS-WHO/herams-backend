<?php

declare(strict_types=1);

use herams\common\domain\facility\FacilityRepository;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;

return function(\herams\common\interfaces\EnvironmentInterface $env, \yii\di\Container $container) {


    $container->set(\yii\filters\auth\CompositeAuth::class, [
        'authMethods' => [
            \herams\api\components\JwtAuth::class
        ],
    ]);

    $container->set(\Lcobucci\JWT\Configuration::class, static function() use ($env): Configuration {
        $result = Configuration::forSymmetricSigner(
            new \Lcobucci\JWT\Signer\Hmac\Sha256(),
            \Lcobucci\JWT\Signer\Key\InMemory::plainText('secretsecretsecretsecretsecretsecretsecretsecretsecret' ?? $env->getSecret('app/sso_private_key'))
        );
        $result->setValidationConstraints(
            new SignedWith($result->signer(), $result->signingKey()),
            new IssuedBy('https://app.herams.org'),
            new PermittedFor('https://api.herams.org'),
            new StrictValidAt(new SystemClock(new DateTimeZone('utc')))
        );
        return $result;
    });

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
        \herams\common\interfaces\CurrentUserIdProviderInterface::class => \herams\common\helpers\CurrentUserIdProvider::class
    ]);

};
