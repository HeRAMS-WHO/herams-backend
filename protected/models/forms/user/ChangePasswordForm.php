<?php


namespace prime\models\forms\user;

use kartik\password\StrengthValidator;
use prime\models\ar\User;
use yii\base\Model;
use yii\validators\CompareValidator;

class ChangePasswordForm extends Model
{
    private $user;

    public $currentPassword;
    public $newPassword;
    public $newPasswordRepeat;

    public function __construct(User $user, array $config = [])
    {
        parent::__construct($config);
        $this->user = $user;
    }


    public function attributeLabels()
    {
        return [
            'currentPassword' => \Yii::t('app', 'Current password'),
            'newPassword' => \Yii::t('app', 'New password'),
            'newPasswordRepeat' => \Yii::t('app', 'Repeat password'),
        ];
    }


    public function rules()
    {
        return [
            [['newPasswordRepeat'], CompareValidator::class, 'compareAttribute' => 'newPassword'],
            [['newPassword'], StrengthValidator::class, 'usernameValue' => $this->user->email, 'preset' => 'normal'],
            [['currentPassword'], function($attribute, $params, $validator) {
                if (!password_verify($this->currentPassword, $this->user->password_hash)) {
                    $this->addError($attribute, "Incorrect password");
                }
            }]
        ];
    }


    public function run(): void
    {
        $this->user->setPassword($this->newPassword);
        $this->user->updateAttributes(['password_hash']);
    }


}