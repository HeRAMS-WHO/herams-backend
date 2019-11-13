<?php


namespace prime\models\forms;



use prime\models\ar\User;
use Yii;
use yii\base\Model;
use yii\validators\RequiredValidator;

class LoginForm extends Model
{
    public $login;
    public $password;

    public function attributeLabels()
    {
        return [
            'login' => \Yii::t('app', 'Email'),
            'password' => \Yii::t('app', 'Password')
        ];
    }


    public function rules()
    {
        return [
            [['login', 'password'], RequiredValidator::class],
            ['login', 'validateLogin'],
            ['password', 'validatePassword', 'when' => function(self $model) {
                return null !== $model->getUser();
            }]
        ];
    }

    public function validateLogin($attribute, $params)
    {
        if ($this->getUser() === null) {
            $this->addError($attribute, \Yii::t('app', "Unknown email"));
        }
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!password_verify($this->password, $this->getUser()->password_hash)) {
                $this->addError($attribute, 'Incorrect password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser());
        } else {
            return false;
        }
    }

    private function getUser(): ?User
    {
        return User::find()->andWhere(['email' => $this->login])->one();
    }




}