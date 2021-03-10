<?php
declare(strict_types=1);

namespace prime\models\forms\user;

use Carbon\Carbon;
use prime\models\ar\User;
use SamIT\Yii2\UrlSigner\UrlSigner;
use yii\base\Model;
use yii\mail\MailerInterface;
use yii\validators\EmailValidator;
use yii\validators\RequiredValidator;
use yii\validators\UniqueValidator;

/**
 * Class AcceptInvitationForm
 * @package prime\models\forms\user
 */
class AcceptInvitationForm extends Model
{
    public string $email;

    public function __construct(
        private MailerInterface $mailer,
        private UrlSigner $urlSigner,
        private string $originalEmail,
        private string $subject,
        private int $subjectId,
        private array $permissions,
        $config = []
    ) {
        $this->email = $originalEmail;
        parent::__construct($config);
    }

    public function attributeLabels(): array
    {
        return [
            'email' => \Yii::t('app', 'Email'),
        ];
    }

    public function hasEmailChanged(): bool
    {
        return $this->email != $this->originalEmail;
    }

    public function rules(): array
    {
        return [
            [['email'], RequiredValidator::class],
            [['email'], EmailValidator::class],
            [['email'], UniqueValidator::class, 'targetClass' => User::class, 'targetAttribute' => 'email'],
        ];
    }

    public function sendConfirmationEmail(): void
    {
        $url = [
            '/user/confirm-invitation',
            'email' => $this->email,
            'subject' => $this->subject,
            'subjectId' => $this->subjectId,
            'permissions' => $this->permissions,
        ];
        $this->urlSigner->signParams($url, false, Carbon::tomorrow());

        $this->mailer->compose(
            'confirm_invitation',
            [
                'confirmationRoute' => $url
            ]
        )
            ->setTo($this->email)
            ->send();
    }
}
