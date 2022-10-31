<?php

declare(strict_types=1);

use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;

return function(\prime\interfaces\EnvironmentInterface $env, \yii\di\Container $container) {
    $container->setDefinitions(require __DIR__ . '/../../../protected/config/di.php');

    $container->set(\yii\filters\auth\CompositeAuth::class, [
        'authMethods' => [
            \herams\api\components\JwtAuth::class
        ]
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

};
