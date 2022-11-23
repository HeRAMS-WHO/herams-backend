<?php

declare(strict_types=1);

namespace prime\controllers\session;

use Carbon\Carbon;
use Lcobucci\JWT\Configuration;
use yii\base\Action;
use yii\web\User;

class AuthToken extends Action
{
    public function run(User $user, Configuration $configuration)
    {
        $token = $configuration->builder()
            ->issuedBy('https://app.herams.org')
            ->expiresAt(Carbon::now()->addMinutes(5)->toDateTimeImmutable())
            ->permittedFor('https://api.herams.org')
            ->withClaim('userId', $user->id)
            ->getToken($configuration->signer(), $configuration->signingKey());
        return $token->toString();
    }
}
