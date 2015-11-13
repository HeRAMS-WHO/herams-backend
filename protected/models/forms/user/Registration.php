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
use dektrium\user\Module;
use prime\models\ar\Profile;
use prime\models\ar\User;

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
    public $organization;
    public $office;
    public $country;
    public $captcha;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $user = $this->module->modelMap['User'];

        return [
            // email rules
            'emailTrim'     => ['email', 'filter', 'filter' => 'trim'],
            'emailRequired' => ['email', 'required'],
            'emailPattern'  => ['email', 'email'],
            'emailUnique'   => [
                'email',
                'unique',
                'targetClass' => $user,
                'message' => \Yii::t('user', 'This email address has already been taken')
            ],
            // password rules
            'passwordRequired' => [['password', 'confirmPassword'], 'required', 'skipOnEmpty' => $this->module->enableGeneratingPassword],
            'passwordLength'   => ['password', 'string', 'min' => 6],
            ['confirmPassword', 'compare', 'compareAttribute' => 'password'],
            // profile rules
            [['first_name', 'last_name', 'organization', 'office', 'country', 'captcha'], 'required'],
            [['first_name', 'last_name', 'organization', 'office', 'country'], 'string'],
            [['captcha'], 'captcha']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $result = parent::attributeLabels();
        return array_merge($result,
            [
                'confirmPassword' => \Yii::t('app', 'Confirmation')
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
}
