<?php
declare(strict_types=1);

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var \prime\models\ar\User $model
 * @var \prime\models\forms\user\ChangePasswordForm $changePassword
 * @var \prime\models\forms\user\UpdateEmailForm $changeMail
 */

$this->title = Yii::t('app', 'Update account');
echo \prime\widgets\Tabs::widget([
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