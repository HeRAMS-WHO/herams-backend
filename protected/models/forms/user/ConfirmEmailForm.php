<?php

declare(strict_types=1);

namespace prime\models\forms\user;

use prime\models\ar\User;
use yii\base\Model;
use yii\validators\EmailValidator;
use yii\validators\RequiredValidator;
use yii\validators\UniqueValidator;

class ConfirmEmailForm extends Model
{
    public function __construct(
        private User $user,
        private string $newMail,
        private string $oldHash,
        array $config = [],
    ) {
        parent::__construct($config);
    }

    public function attributeLabels(): array
    {
        return [
            'newMail' => \Yii::t('app', 'New email address'),
            'oldMail' => \Yii::t('app', 'Old email address')
        ];
    }

    public function rules(): array
    {
        return [
            [['oldHash', 'newMail'], RequiredValidator::class],
            [['newMail'], function () {
                if (!password_verify($this->user->email, $this->oldHash)) {
                    $this->addError('newMail', \Yii::t('app', 'Your email address has already been changed'));
                }
            }],
            [['newMail'], EmailValidator::class],
            [['newMail'], UniqueValidator::class, 'targetClass' => User::class, 'targetAttribute'  => 'email'],

        ];
    }

    public function getOldHash(): string
    {
        return $this->oldHash;
    }
    public function getNewMail(): string
    {
        return $this->newMail;
    }

    public function getOldMail(): string
    {
        return $this->user->email;
    }
    public function run(): void
    {
        $this->user->email = $this->newMail;
        if (!$this->user->save()) {
            throw new \RuntimeException(\Yii::t('app', 'Failed to update email address'));
        }
    }
}
