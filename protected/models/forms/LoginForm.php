<?php

declare(strict_types=1);

namespace prime\models\forms;

use herams\common\domain\user\User;
use prime\traits\DisableYiiLoad;
use Yii;
use yii\base\Model;
use yii\validators\RequiredValidator;

class LoginForm extends Model
{
    use DisableYiiLoad;

    public string|null $login = null;

    public string|null $password = null;

    public function attributeLabels(): array
    {
        return [
            'login' => \Yii::t('app', 'Email'),
            'password' => \Yii::t('app', 'Password'),
        ];
    }

    public function rules(): array
    {
        return [
            [['login', 'password'], RequiredValidator::class],
            ['login', 'validateLogin'],
            [
                'password',
                'validatePassword',
                'when' => function (self $model) {
                    return null !== $model->getUser();
                },
            ],
        ];
    }

    public function validateLogin($attribute, $params): void
    {
        if ($this->getUser() === null) {
            $this->addError($attribute, \Yii::t('app', "Unknown email"));
        }
    }

    public function validatePassword($attribute, $params): void
    {
        if (! $this->hasErrors()) {
            if (! password_verify($this->password, $this->getUser()->password_hash)) {
                $this->addError($attribute, 'Incorrect password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login(): bool
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $user->updateLastLogin();

            return Yii::$app->user->login($user);
        } else {
            return false;
        }
    }

    private function getUser(): ?User
    {
        return User::find()->andWhere([
            'email' => $this->login,
        ])->one();
    }
}
