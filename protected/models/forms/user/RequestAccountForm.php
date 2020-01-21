<?php


namespace prime\models\forms\user;


use Carbon\Carbon;
use prime\models\ar\User;
use SamIT\Yii2\UrlSigner\UrlSigner;
use yii\base\Model;
use yii\caching\CacheInterface;
use yii\mail\MailerInterface;
use yii\validators\EmailValidator;
use yii\validators\RequiredValidator;
use yii\validators\UniqueValidator;

class RequestAccountForm extends Model
{
    public $email;
    /** @var CacheInterface $cache */
    private $cache;

    public function attributeHints()
    {
        return [
            'email' => \Yii::t('app', 'We will send you a secure link to the sign up form')
        ];
    }


    public function __construct(CacheInterface $cache, $config = [])
    {
        parent::__construct($config);
        $this->cache = $cache;
    }

    public function rules()
    {
        return [
            [['email'], RequiredValidator::class],
            [['email'], EmailValidator::class],
            [['email'], UniqueValidator::class, 'targetClass' => User::class],
            [['email'], function() {
                $lastAttempt = $this->cache->get(__CLASS__ . $this->email);
                if (is_int($lastAttempt) && Carbon::createFromTimestamp($lastAttempt)->isFuture()) {
                    $this->addError('email',\Yii::t('app', "Too many attempts, try again in {seconds} seconds", [
                        'seconds' => $lastAttempt - Carbon::now()->timestamp
                    ]));
                }
            }]
        ];
    }

    public function send(
        MailerInterface $mailer,
        UrlSigner $urlSigner
    ): bool {
        $params = [
            '/user/create',
            'email' => $this->email,
        ];
        $urlSigner->signParams($params, false, Carbon::tomorrow());

        $mailer->compose('email_verification', [
            'user' => $this,
            'verificationRoute' => $params

        ])
            ->setTo($this->email)
            ->setSubject(\Yii::t('app', "HeRAMS email verification"))
            ->send()
        ;
        $this->cache->set(__CLASS__ . $this->email, Carbon::now()->addMinutes(2)->timestamp);
        return true;
    }




}