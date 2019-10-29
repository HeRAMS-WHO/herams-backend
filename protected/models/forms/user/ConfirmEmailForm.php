<?php


namespace prime\models\forms\user;

use prime\models\ar\User;
use yii\base\Model;
use yii\validators\EmailValidator;
use yii\validators\RequiredValidator;
use yii\validators\UniqueValidator;

class ConfirmEmailForm extends Model
{
    private $member;
    private $newMail;
    private $oldHash;

    public function __construct(
        User $member,
        string $newMail,
        string $oldHash
    ) {
        parent::__construct();
        $this->member = $member;
        $this->newMail = $newMail;
        $this->oldHash = $oldHash;
    }

    public function attributeLabels()
    {
        return [
            'newMail' => \Yii::t('app', 'New email address'),
            'oldMail' => \Yii::t('app', 'Old email address')
        ];
    }


    public function rules()
    {
        return [
            [['oldHash', 'newMail'], RequiredValidator::class],
            [['newMail'], function() {
                if (!password_verify($this->member->email, $this->oldHash)) {
                    $this->addError('newMail', \Yii::t('app', 'Your email address has already been changed'));
                }
            }],
            [['newMail'], EmailValidator::class],
            [['newMail'], UniqueValidator::class, 'targetClass' => User::class, 'targetAttribute'  => 'email'],

        ];
    }

    public function getOldHash(): string {
        return $this->oldHash;
    }
    public function getNewMail(): string
    {
        return $this->newMail;
    }

    public function getOldMail(): string
    {
        return $this->member->email;
    }
    public function run(): void
    {
        $this->member->email = $this->newMail;
        if (!$this->member->save()) {
            throw new \RuntimeException(\Yii::t('app', 'Failed to update email address'));
        }
    }
}