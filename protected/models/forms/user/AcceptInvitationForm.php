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
        private string $originalEmail,
        private string $subject,
        private string $subjectId,
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

    public function sendConfirmationEmail(MailerInterface $mailer, UrlSigner $urlSigner): void
    {
        $url = [
            '/user/confirm-invitation',
            'email' => $this->email,
            'subject' => $this->subject,
            'subjectId' => $this->subjectId,
            'permissions' => implode(',', $this->permissions),
        ];
        $urlSigner->signParams($url, false, Carbon::tomorrow());

        $mailer->compose(
            'confirm_invitation',
            [
                'confirmationRoute' => $url
            ]
        )
            ->setTo($this->email)
            ->send();
    }
}
