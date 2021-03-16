<?php
declare(strict_types=1);

namespace prime\models\forms\user;

use kartik\password\StrengthValidator;
use prime\models\ar\User;
use SamIT\abac\values\Authorizable;
use SamIT\abac\AuthManager;
use yii\base\Model;
use yii\validators\CompareValidator;
use yii\validators\RegularExpressionValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

class ConfirmInvitationForm extends Model
{
    public string $password = '';
    public string $confirm_password = '';
    public string $name = '';

    public function __construct(
        private AuthManager $abacManager,
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
            'confirm_password' => \Yii::t('app', 'Confirm password'),
            'email' => \Yii::t('app', 'Email'),
            'password' => \Yii::t('app', 'Password'),
        ];
    }

    public function createAccount(): void
    {
        $user = new User();
        $user->email = $this->email;
        $user->name = $this->name;
        $user->setPassword($this->password);
        if (!$user->save()) {
            throw new \RuntimeException('Failed to create user');
        }

        $subjectAuthorizable = new Authorizable($this->subjectId, $this->subject);
        foreach ($this->permissions as $permission) {
            $this->abacManager->grant($user, $subjectAuthorizable, $permission);
        }
    }

    public function rules(): array
    {
        return [
            [['confirm_password', 'name', 'password'], RequiredValidator::class],
            [['name'], StringValidator::class, 'max' => 50],
            [['name'], RegularExpressionValidator::class, 'pattern' => User::NAME_REGEX],
            [['password'], StrengthValidator::class, 'usernameValue' => $this->email, 'preset' => StrengthValidator::NORMAL],
            [['confirm_password'], CompareValidator::class, 'compareAttribute' => 'password', 'message' => \Yii::t('app', "Passwords don't match")],
        ];
    }
}
