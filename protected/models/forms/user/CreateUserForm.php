<?php


namespace prime\models\forms\user;


use Carbon\Carbon;
use kartik\password\StrengthValidator;
use prime\models\ar\User;
use yii\behaviors\TimestampBehavior;
use yii\validators\CompareValidator;

class CreateUserForm extends User
{
    public $confirm_password;
    private $_password;




    public function getDisplayName()
    {
        return $this->email;
    }

    public static function tableName()
    {
        return User::tableName();
    }

    public function rules()
    {
        $result = parent::rules();
        $result[] = [['newPassword'], StrengthValidator::class, 'usernameValue' => $this->user->email, 'preset' => 'normal'];
        $result[] = [['confirm_password'], CompareValidator::class, 'compareAttribute' => 'password',
            'message' => \Yii::t('app', "Passwords don't match")];
        return $result;
    }

    public function setPassword($value): void
    {
        $this->_password = $value;
        parent::setPassword($value);
    }

    public function getPassword()
    {
        return $this->_password;
    }

}