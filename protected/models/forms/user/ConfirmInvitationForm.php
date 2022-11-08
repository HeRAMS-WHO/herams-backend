<?php

declare(strict_types=1);

namespace prime\models\forms\user;

use herams\common\domain\user\User;
use kartik\password\StrengthValidator;
use SamIT\abac\AuthManager;
use SamIT\abac\values\Authorizable;
use yii\base\Model;
use yii\validators\CompareValidator;
use yii\validators\DefaultValueValidator;
use yii\validators\RegularExpressionValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

class ConfirmInvitationForm extends Model
{
    public string $password = '';

    public string $confirmPassword = '';

    public string $name = '';

    public bool $subscribeToNewsletter = false;

    public function __construct(
        public string $email,
        private string $subject,
        private string $subjectId,
        private array $permissions,
        $config = []
    ) {
        parent::__construct($config);
    }

    public function attributeLabels(): array
    {
        return [
            'confirmPassword' => \Yii::t('app', 'Confirm password'),
            'email' => \Yii::t('app', 'Email'),
            'password' => \Yii::t('app', 'Password'),
            'subscribeToNewsletter' => \Yii::t('app', 'Subscribe to newsletter'),
        ];
    }

    public function createAccount(AuthManager $authManager): void
    {
        $user = new User();
        $user->email = $this->email;
        $user->name = $this->name;
        $user->newsletter_subscription = $this->subscribeToNewsletter;
        $user->setPassword($this->password);
        if (! $user->save()) {
            throw new \RuntimeException('Failed to create user');
        }

        $subjectAuthorizable = new Authorizable($this->subjectId, $this->subject);
        foreach ($this->permissions as $permission) {
            $authManager->grant($user, $subjectAuthorizable, $permission);
        }
    }

    public function rules(): array
    {
        return [
            [['confirmPassword', 'name', 'password'], RequiredValidator::class],
            [['name'],
                StringValidator::class,
                'max' => 50,
            ],
            [['name'],
                RegularExpressionValidator::class,
                'pattern' => User::NAME_REGEX,
            ],
            [['password'],
                StrengthValidator::class,
                'usernameValue' => $this->email,
                'preset' => StrengthValidator::NORMAL,
            ],
            [['confirmPassword'],
                CompareValidator::class,
                'compareAttribute' => 'password',
                'message' => \Yii::t('app', "Passwords don't match"),
            ],
            [['subscribeToNewsletter'],
                DefaultValueValidator::class,
                'value' => false,
            ],
        ];
    }
}
