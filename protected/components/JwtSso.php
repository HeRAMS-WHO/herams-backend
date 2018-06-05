<?php


namespace prime\components;


use Closure;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer;
use prime\interfaces\TicketingInterface;
use yii\base\Component;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Url;

class JwtSso extends Component implements TicketingInterface
{
    public $issuer;
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

    /**
     * We store the private key in a stream to prevent accidental leakage (var_dump, log files or stack traces).
     * @var resource
     */
    private $_privateKey;

    public function __construct($config = [])
    {
        $this->_privateKey = fopen('php://memory', 'w+');
        if (!is_resource($this->_privateKey)) {
            throw new \RuntimeException("Failed to create stream for secret storage");
        }
        parent::__construct($config);
    }

    public function init()
    {
        $this->_userNameGenerator = function($id) {
            return $id;
        };
        parent::init();
        if (empty($this->getPrivateKey())) {
            throw new InvalidConfigException("Private key must be configured");
        }

        if (!isset($this->loginUrl)) {
            throw new InvalidConfigException("Login URL is mandatory");
        }
    }

    private function getPrivateKey(): string
    {
        rewind($this->_privateKey);
        return stream_get_contents($this->_privateKey);
    }


    public function createToken(
        string $identifier,
        ?int $expires = null
    ): string {
        $builder = (new Builder())
            ->setIssuer($this->issuer)
            ->setAudience($this->loginUrl)
            ->set($this->claim, $this->createUserName($identifier))
            ->setIssuedAt(time())
            ->setExpiration(time() + ($expires ?? $this->defaultExpiration));

        if (isset($this->errorRoute)) {
            $builder->set('errorUrl', Url::to($this->errorRoute, true));
        }

        return $builder
            ->sign(new Signer\Rsa\Sha256(), $this->getPrivateKey())
            ->getToken()
            ->__toString();
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

    /**
     * Sets the private key
     * @param string $key
     */
    public function setPrivateKey(string $key)
    {
        rewind($this->_privateKey);
        fwrite($this->_privateKey, $key);
    }

    /**
     * Setter to allow configuring a file name in component config.
     * @param string $filename
     */
    public function setPrivateKeyFile(string $filename)
    {
        $this->setPrivateKey(file_get_contents($filename));
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
        $this->_userNameGenerator = function($id) use ($prefix) {
            return $prefix . $id;
        };
    }

}