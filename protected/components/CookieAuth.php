<?php

declare(strict_types=1);

namespace prime\components;

use prime\models\ar\User;
use yii\filters\auth\AuthInterface;
use yii\web\ForbiddenHttpException;
use yii\web\IdentityInterface;
use yii\web\Session;
use yii\web\UnauthorizedHttpException;

class CookieAuth implements AuthInterface
{
    public function authenticate($user, $request, $response): ?IdentityInterface
    {
        /** @var Session $session */
        $session =  \Yii::$app->get('session');
        \Yii::warning(__CLASS__ . ':' . __FUNCTION__);
        if ($session->hasSessionId) {
            $request->enableCsrfValidation = true;
            if (!$request->validateCsrfToken()) {
                throw new ForbiddenHttpException('CSRF Token missing or invalid');
            }
            $request->enableCsrfValidation = false;
            // Look for the session
            $id = $session->get($user->idParam);
            $session->close();

            if (null !== $identity = User::findOne(['id' => $id])) {
                $user->login($identity);
            }
            return $identity;
        }
        return null;
    }

    public function challenge($response)
    {
        // TODO: Implement challenge() method.
    }

    public function handleFailure($response)
    {
        throw new UnauthorizedHttpException('Your request was made with invalid credentials.');
    }
}
