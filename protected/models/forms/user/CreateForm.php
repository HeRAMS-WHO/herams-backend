<?php

declare(strict_types=1);

namespace prime\models\forms\user;

use kartik\password\StrengthValidator;
use prime\models\ar\User;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\validators\BooleanValidator;
use yii\validators\CompareValidator;
use yii\validators\DefaultValueValidator;
use yii\validators\RegularExpressionValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;

class CreateForm extends Model
{
    public string $email = '';

    public string $confirm_password = '';

    public string $name = '';

    public string $password = '';

    public bool $subscribeToNewsletter = false;

    public function __construct(
        private User $user,
        $config = []
    ) {
        parent::__construct($config);
    }

    public function attributeLabels(): array
    {
        return ArrayHelper::merge($this->user->attributeLabels(), [
            'confirm_password' => \Yii::t('app', 'Confirm password'),
            'password' => \Yii::t('app', 'Password'),
            'subscribeToNewsletter' => \Yii::t('app', 'Subscribe to newsletter'),
        ]);
    }

    public function rules(): array
    {
        return [
            [['email', 'name', 'password'], RequiredValidator::class],
            [
                ['email'],
                UniqueValidator::class,
                'targetClass' => User::class,
                'targetAttribute' => 'email',
                'message' => \Yii::t('app', 'Email already taken'),
            ],
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
            [
                ['confirm_password'],
                CompareValidator::class,
                'compareAttribute' => 'password',
                'message' => \Yii::t('app', 'Passwords don\'t match'),
            ],
            [['subscribeToNewsletter'],
                DefaultValueValidator::class,
                'value' => false,
            ],
            [['subscribeToNewsletter'], BooleanValidator::class],
        ];
    }

    public function run(): void
    {
        $this->user->email = $this->email;
        $this->user->name = $this->name;
        $this->user->setPassword($this->password);
        $this->user->newsletter_subscription = $this->subscribeToNewsletter;
        if (! $this->user->save()) {
            throw new \RuntimeException('Failed to create user');
        }
    }
}
