<?php

declare(strict_types=1);

use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use prime\helpers\Icon;
use prime\interfaces\UserNotificationInterface;
use prime\models\ar\User;
use prime\widgets\menu\UserTabMenu;
use prime\widgets\Section;
use yii\data\DataProviderInterface;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var User $model
 * @var DataProviderInterface $userNotificationsDataprovider
 */

$this->beginBlock('tabs');
echo UserTabMenu::widget([
    'user' => $model,
]);
$this->endBlock();

$this->title = \Yii::t('app', 'Notifications');

Section::begin()
    ->withHeader($this->title);

echo GridView::widget([
    'dataProvider' => $userNotificationsDataprovider,
    'columns' => [
        [
            'label' => \Yii::t('app', 'Title'),
            'value' => static fn(UserNotificationInterface $model) => $model->getTitle(),
        ],
        [
            'class' => ActionColumn::class,
            'buttons' => [
                'view' => static function ($url, UserNotificationInterface $model, $key) {
                    return Html::a(
                        Icon::eye(),
                        $model->getUrl(),
                        [
                            'title' => \Yii::t('app', 'View'),
                        ]
                    );
                }
            ],
            'visibleButtons' => [
                'view' => function (UserNotificationInterface $model) {
                    return $model->getUrl() !== null;
                },
            ]
        ]
    ]
]);

Section::end();
