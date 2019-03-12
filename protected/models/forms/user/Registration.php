<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace prime\models\forms\user;

use dektrium\user\models\RegistrationForm;
use prime\models\ar\Profile;
use prime\models\ar\User;
use prime\models\Country;
use yii\helpers\ArrayHelper;
use yii\validators\CompareValidator;
use yii\validators\EmailValidator;
use yii\validators\FilterValidator;
use yii\validators\RangeValidator;
use yii\validators\RegularExpressionValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;

class Registration extends RegistrationForm
{
    /**
     * @var string Password
     */
    public $confirmPassword;

    /**
     * @var string
     */
    public $first_name;
    public $last_name;
    public $captcha;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),
            [
                'confirmPassword' => \Yii::t('app', 'Password confirmation'),
            ]
        );
    }
    /**
     * Loads attributes to the user model. You should override this method if you are going to add new fields to the
     * registration form. You can read more in special guide.
     *
     * By default this method set all attributes of this model to the attributes of User model, so you should properly
     * configure safe attributes of your User model.
     *
     * @param User $user
     */
    protected function loadAttributes(\dektrium\user\models\User $user)
    {
        /**
         * @todo load profile!
         */
        $attributes = $this->attributes;
        unset($attributes['username']);
        $user->setAttributes($attributes);
        $profile = new Profile();
        $profile->setAttributes($this->attributes);
        $user->setProfile($profile);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // email rules
            'emailTrim'     => [['email'], FilterValidator::class, 'filter' => 'trim'],
            'emailRequired' => [['email'], RequiredValidator::class],
            'emailPattern'  => [['email'], EmailValidator::class],
            'emailUnique'   => [
                ['email'],
                UniqueValidator::class,
                'targetClass' => $this->module->modelMap['User'],
                'message' => \Yii::t('user', 'This email address has already been taken')
            ],
            // password rules
            'passwordRequired' => [['password', 'confirmPassword'], RequiredValidator::class, 'skipOnEmpty' => $this->module->enableGeneratingPassword],
            'passwordLength'   => ['password', StringValidator::class, 'min' => 6],
            ['confirmPassword', CompareValidator::class, 'compareAttribute' => 'password'],
            // profile rules
            [['first_name'], RequiredValidator::class],
            [['first_name', 'last_name'], 'string'],
        ];
    }
}
