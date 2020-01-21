<?php


namespace prime\models\forms\user;


use Carbon\Carbon;
use kartik\password\StrengthValidator;
use prime\models\ar\User;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
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
        return [
            [['email', 'name', 'password'], RequiredValidator::class],
            ['email', UniqueValidator::class,
                'targetClass' => User::class,
                'targetAttribute' => 'email',
                'message' => \Yii::t('app', "Email already taken")
            ],
            ['name', StringValidator::class, 'max' => 50],
            ['name', RegularExpressionValidator::class, 'pattern' => '/^[\'\w\- ]+$/u'],
            [['password'], StrengthValidator::class, 'usernameValue' => $this->email, 'preset' => 'normal'],
            [['confirm_password'], CompareValidator::class, 'compareAttribute' => 'password',
            'message' => \Yii::t('app', "Passwords don't match")],
        ];
    }

    public function run()
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