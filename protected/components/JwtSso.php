<?php

namespace prime\components;

use Carbon\Carbon;
use Closure;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Token\Builder;
use prime\interfaces\TicketingInterface;
use yii\base\Component;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Url;

class JwtSso extends Component implements TicketingInterface
{
    public ?string $issuer;
    public $loginUrl;

    /**
     * @var string|array A route indicating where a user should be sent in case of errors with SSO.
     * The error message will be passed back via a GET request.
     */
    public $errorRoute;

    /**
     * @var string Claim name to use for the username.
     */
    public $claim = 'username';

    /**
     * @var string The name of the POST param to use for the token
     */
    public $paramName = 'jwt';



    /**
     * @var int Default validity period for a ticket, in case of clock skew between servers this might need to be increased.
     */
    public $defaultExpiration = 300;


    /**
     * A closure that given a user ID generates a username for SSO.
     * @var Closure Defaults to the identity function
     */
    private $_userNameGenerator;

    private Secret $privateKey;

    public function __construct($config = [])
    {
        $this->_userNameGenerator = static function ($id) {
            return $id;
        };
        parent::__construct($config);
    }

    public function init()
    {
        parent::init();
        if (!isset($this->privateKey)) {
            throw new InvalidConfigException("Private key must be configured");
        }

        if (!isset($this->loginUrl)) {
            throw new InvalidConfigException("Login URL is mandatory");
        }
    }

    public function createToken(
        string $identifier,
        ?int $expires = null
    ): string {

        $builder = new Builder(new JoseEncoder(), ChainedFormatter::withUnixTimestampDates());
        $builder
            ->issuedBy($this->issuer ?? \Yii::$app->name)
            ->permittedFor($this->loginUrl)
            ->withClaim($this->claim, $this->createUserName($identifier))
            ->issuedAt(Carbon::now()->toImmutable())
            ->expiresAt(Carbon::now()->addSeconds($expires ?? $this->defaultExpiration)->toImmutable());

        if (isset($this->errorRoute)) {
            $builder->withClaim('errorUrl', Url::to($this->errorRoute, true));
        }

        $key = Signer\Key\InMemory::plainText(strtr((string)$this->privateKey, ['    ' => "\n"]));
        $jwt =  $builder->getToken(new Signer\Rsa\Sha256(), $key)->toString();
        return $jwt;
    }

    private function createUserName(string $identifier): string
    {
        return call_user_func($this->_userNameGenerator, $identifier);
    }

    /**
     * @throws \yii\base\ExitException
     * @inheritdoc
     */
    public function loginAndRedirectCurrentUser(): void
    {
        if (\Yii::$app->user->isGuest) {
            throw new InvalidCallException("This function cannot be called when there is no logged in user");
        }

        $form = Html::beginForm($this->loginUrl, 'post', ['id' => 'ssoform'])
            . Html::hiddenInput($this->paramName, $this->createToken(\Yii::$app->user->id))
            . Html::endForm();
        \Yii::$app->response->content = <<<HTML
<html>
    <head></head>
    <body>
    $form
    <script>
    document.getElementById('ssoform').submit();
    </script>
    </body>    
</html>

HTML;
        \Yii::$app->end();
    }

    public function setPrivateKey(Secret $key)
    {
        $this->privateKey = $key;
    }

    /**
     * Setter to allow configuring a generator for usernames,
     * allows for more flexibility with respect to just a prefix
     * @param \Closure $closure
     */
    public function setUserNameGenerator(\Closure $closure)
    {
        $this->_userNameGenerator = $closure;
    }

    /**
     * Setter to allow configuring a prefix for username generation
     * @param string $prefix
     */

    public function setUserNamePrefix(string $prefix)
    {
        $this->_userNameGenerator = function ($id) use ($prefix) {
            return $prefix . $id;
        };
    }
}
