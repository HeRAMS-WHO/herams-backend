<?php

declare(strict_types=1);

namespace prime\models\forms\user;

use Carbon\Carbon;
use prime\models\ar\User;
use SamIT\abac\AuthManager;
use SamIT\abac\values\Authorizable;
use SamIT\Yii2\UrlSigner\UrlSigner;
use yii\base\Model;
use yii\mail\MailerInterface;
use yii\validators\BooleanValidator;
use yii\validators\EmailValidator;
use yii\validators\RequiredValidator;
use yii\validators\UniqueValidator;
use yii\web\User as UserComponent;

/**
 * Class AcceptInvitationForm
 * @package prime\models\forms\user
 */
class AcceptInvitationForm extends Model
{
    public bool $loggedInAccept = false;
    public string $email = '';

    public function __construct(
        private UserComponent $user,
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

    public function getUser(): User
    {
        return $this->user->identity;
    }

    public function hasEmailChanged(): bool
    {
        return $this->email != $this->originalEmail;
    }

    public function grantLoggedInUser(AuthManager $abacManager): void
    {
        $subjectAuthorizable = new Authorizable($this->subjectId, $this->subject);
        foreach ($this->permissions as $permission) {
            $abacManager->grant($this->user->identity, $subjectAuthorizable, $permission);
        }
    }

    public function isLoggedIn(): bool
    {
        return !$this->user->isGuest;
    }

    public function rules(): array
    {
        return [
            [['email'], RequiredValidator::class, 'when' => fn() => !$this->loggedInAccept],
            [['email'], EmailValidator::class],
            [
                ['email'],
                UniqueValidator::class,
                'targetClass' => User::class,
                'targetAttribute' => 'email',
                'message' => \Yii::t('app', 'This email is already in use, log in and click the link again.'),
                'when' => static fn(self $model) => !$model->loggedInAccept,
            ],
            [['loggedInAccept'], BooleanValidator::class, 'when' => fn() => $this->isLoggedIn()],
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
