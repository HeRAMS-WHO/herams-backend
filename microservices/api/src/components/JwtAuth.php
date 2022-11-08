<?php
declare(strict_types=1);

namespace herams\api\components;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\UnencryptedToken;
use yii\filters\auth\AuthMethod;
use yii\web\IdentityInterface;
use yii\web\UnauthorizedHttpException;

class JwtAuth extends AuthMethod
{
    public function __construct(private Configuration $configuration)
    {
        parent::__construct([]);
    }


    public function authenticate($user, $request, $response)
    {
        if (preg_match('/^Bearer (.*)$/', $request->getHeaders()->get('Authorization', ''), $matches)) {
            $token = $this->configuration->parser()->parse($matches[1]);
            assert($token instanceof UnencryptedToken);



            if (!$this->configuration->validator()->validate($token, ...$this->configuration->validationConstraints())) {
                throw new UnauthorizedHttpException();
            }


            $userId = $token->claims()->get('userId');


            /** @var class-string<IdentityInterface> $identityClass */
            $identityClass = $user->identityClass;
            $identity = $identityClass::findIdentity($userId);
            if ($identity === null) {
                $this->handleFailure($response);
            }

            $user->login($identity);
            return $identity;
        }

        return null;
    }


}
