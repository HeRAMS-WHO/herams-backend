<?php
declare(strict_types=1);

namespace prime\models\forms\user;



use Carbon\Carbon;
use prime\models\ar\User;
use SamIT\Yii2\UrlSigner\UrlSigner;
use yii\base\Model;
use yii\helpers\Url;
use yii\mail\MailerInterface;
use yii\validators\EmailValidator;
use yii\validators\RequiredValidator;
use yii\validators\UniqueValidator;

class UpdateEmailForm extends Model
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var UrlSigner
     */
    private $urlSigner;
    /**
     * @var MailerInterface
     */
    private $mailer;

    public $newEmail;

    public function __construct(
        MailerInterface $mailer,
        User $user,
        UrlSigner $urlSigner

    ) {
        parent::__construct();
        $this->user = $user;
        $this->urlSigner =$urlSigner;
        $this->mailer = $mailer;
    }

    public function attributeHints()
    {
        return [
            'newEmail' => \Yii::t('app', 'We will send a confirmation message to this address')
        ];
    }


    public function attributeLabels()
    {
        return [
            'newEmail' => \Yii::t('app', 'New email address'),
        ];
    }


    public function rules()
    {
        return [
            [['newEmail'], RequiredValidator::class],
            [['newEmail'], EmailValidator::class],
            [['newEmail'], UniqueValidator::class, 'targetAttribute'  => 'email', 'targetClass' => User::class]
        ];
    }


    public function run(): void
    {
        $url = [
            '/user/confirm-email',
            'email' => $this->newEmail,
            'old_hash' => password_hash($this->user->email, PASSWORD_DEFAULT)
        ];
        $this->urlSigner->signParams($url, false, Carbon::now()->addHours(3));

        $result = $this->mailer->compose('change-email', [
            'url' => Url::to($url, true),
            'user' => $this->user
        ])
            ->setFrom(\Yii::$app->params['sender'])
            ->send();

        if (!$result) {
            throw new \RuntimeException('Failed to send email');
        }
    }


}