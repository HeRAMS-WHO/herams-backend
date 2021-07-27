<?php
declare(strict_types=1);

use kartik\grid\BooleanColumn;
use kartik\grid\GridView;
use prime\helpers\Icon;
use prime\models\ar\AccessRequest;
use prime\models\ar\User;
use prime\widgets\menu\UserTabMenu;
use prime\widgets\Section;
use yii\data\DataProviderInterface;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var User $model
 * @var DataProviderInterface $respondedAccessRequestDataprovider
 * @var View $this
 * @var DataProviderInterface $userAccessRequestDataprovider
 */

$this->beginBlock('tabs');
echo UserTabMenu::widget([
    'user' => $model,
]);
$this->endBlock();

$this->title = \Yii::t('app', 'Access requests');

$iconCheck = Icon::check();
$iconClose = Icon::close();

Section::begin()
    ->withHeader(\Yii::t('app', 'Your access requests'));

echo GridView::widget([
    'dataProvider' => $userAccessRequestDataprovider,
    'columns' => [
        'subject',
        [
            'label' => \Yii::t('app', 'Target type'),
            'value' => fn(AccessRequest $model) => $model->targetClassOptions()[$model->target_class],
        ],
        [
            'label' => \Yii::t('app', 'Target title'),
            'value' => 'target.title',
        ],
        'created_at:dateTime',
        [
            'class' => BooleanColumn::class,
            'value' => 'accepted',
            'label' => \Yii::t('app', 'Granted'),
            'trueIcon' => $iconCheck,
            'falseIcon' => $iconClose,
        ],
        [
            'label' => \Yii::t('app', 'Responded by'),
            'value' => 'respondedByUser.name',
        ],
        'response',
        'responded_at:dateTime',
    ]
]);

Section::end();

Section::begin()
    ->withHeader(\Yii::t('app', 'Access requests you responded to'));

echo GridView::widget([
    'dataProvider' => $respondedAccessRequestDataprovider,
    'columns' => [
        [
            'label' => \Yii::t('app', 'Created by'),
            'value' => 'createdByUser.name',
        ],
        'subject',
        [
            'label' => \Yii::t('app', 'Target type'),
            'value' => fn(AccessRequest $model) => $model->targetClassOptions()[$model->target_class],
        ],
        [
            'label' => \Yii::t('app', 'Target title'),
            'value' => 'target.title',
        ],
        'created_at:dateTime',
        [
            'class' => BooleanColumn::class,
            'value' => 'accepted',
            'label' => \Yii::t('app', 'Granted'),
            'trueIcon' => $iconCheck,
            'falseIcon' => $iconClose,
        ],
        'response',
        'responded_at:dateTime',
    ]
]);

echo Html::tag('p', Html::a(\Yii::t('app', 'Access request history'), ['access-requests/index']));

Section::end();
