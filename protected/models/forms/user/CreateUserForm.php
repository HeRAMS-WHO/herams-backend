<?php
declare(strict_types=1);

namespace prime\models\forms\user;

use kartik\password\StrengthValidator;
use prime\models\ar\User;
use yii\base\Model;
use yii\validators\CompareValidator;
use yii\validators\RegularExpressionValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;

class CreateUserForm extends Model
{
    public $confirm_password;
    public $password;

    public $email;
    public $name;

    public function attributeLabels(): array
    {
        return [
            'confirm_password' => \Yii::t('app', 'Confirm password'),
            'email' => \Yii::t('app', 'Email'),
            'password' => \Yii::t('app', 'Password'),
        ];
    }

    public function getDisplayName(): string
    {
        return $this->email;
    }

    public static function tableName(): string
    {
        return User::tableName();
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
                'message' => \Yii::t('app', 'Email already taken')
            ],
            [['name'], StringValidator::class, 'max' => 50],
            [['name'], RegularExpressionValidator::class, 'pattern' => User::NAME_REGEX],
            [['password'], StrengthValidator::class, 'usernameValue' => $this->email, 'preset' => StrengthValidator::NORMAL],
            [
                ['confirm_password'],
                CompareValidator::class, 'compareAttribute' => 'password',
                'message' => \Yii::t('app', 'Passwords don\'t match')
            ],
        ];
    }

    public function run(): void
    {
        $user = new User();
        $user->email = $this->email;
        $user->name = $this->name;
        $user->setPassword($this->password);
        if (!$user->save()) {
            throw new \RuntimeException('Failed to create user');
        }
    }
}
