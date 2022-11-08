<?php

declare(strict_types=1);

use Collecthor\DataInterfaces\VariableInterface;
use herams\common\models\Permission;
use prime\components\View;
use prime\interfaces\FacilityForTabMenu;
use prime\widgets\menu\FacilityTabMenu;
use prime\widgets\Section;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\User;

/**
 * @var ActiveDataProvider $responseProvider
 * @var View $this
 * @var FacilityForTabMenu $facility
 * @var iterable<VariableInterface> $variables
 */

$this->title = \Yii::t('app', "Admin responses");

$this->beginBlock('tabs');
echo FacilityTabMenu::widget(
    [
        'facility' => $facility,
    ]
);
$this->endBlock();

Section::begin()
    ->withActions([
        [
            'label' => \Yii::t('app', 'Update admin for facility'),
            'link' => Url::to([
                'update',
                'id' => $facility->getId(),
            ]),
            'permission' => function (FacilityForTabMenu $facility, User $userComponent) {
                return $facility->canReceiveSituationUpdate() && $facility->canCurrentUser(Permission::PERMISSION_WRITE);
            },
        ],
    ])
    ->withSubject($facility)
    ->withHeader(\Yii::t('app', 'Admin responses'));

echo \prime\widgets\AgGrid\AgGrid::widget([
    'route' => $dataRoute,
    'columns' => [
        [

            'headerName' => \Yii::t('app', 'Name'),
            'field' => 'name',
        ],
        [

            'headerName' => \Yii::t('app', 'Id'),
            'field' => 'id',
            'filter' => 'agNumberColumnFilter',
        ],
        ...\iter\map(fn (VariableInterface $variable) => [
            'field' => $variable->getName(),
            'headerName' => $variable->getTitle(\Yii::$app->language),
        ], $variables),
    ],

]);
Section::end();
