<?php
declare(strict_types=1);

use prime\models\ar\User;
use prime\models\forms\user\ChangePasswordForm;
use prime\models\forms\user\UpdateEmailForm;
use prime\widgets\Section;
use prime\widgets\Tabs;

/**
 * @var yii\web\View $this
 * @var User $model
 * @var ChangePasswordForm $changePassword
 * @var UpdateEmailForm $changeMail
 */

$this->title = Yii::t('app', 'Update account');

Section::begin()
    ->withHeader($this->title);

echo Tabs::widget([
    'options' => [
        'style' => [
            'grid-column' => '1 / -1',
            'min-height' => '400px'
        ]
    ],
    'items' => [
        [
            'label' => \Yii::t('app', 'Profile'),
            'content' => $this->render('_accountForm', ['model' => $model])
        ],
        [
            'label' =>  \Yii::t('app', 'Password'),
            'content' => $this->render('update-password', ['model' => $changePassword])
        ],
        [
            'label' =>  \Yii::t('app', 'Email'),
            'content' => $this->render('update-email', ['model' => $changeMail])
        ]
    ]
]);

Section::end();
