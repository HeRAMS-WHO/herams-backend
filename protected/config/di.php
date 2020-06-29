<?php
declare(strict_types=1);

use yii\di\Container;

return [
    \kartik\grid\ActionColumn::class => static function (Container $container, array $params = [], array $config = []): \yii\grid\ActionColumn {
        if (!isset($config['header'])) {
            $config['header'] = \Yii::t('app', 'Actions');
        }
        $result = new \kartik\grid\ActionColumn($config);
        return $result;
    },
    \kartik\switchinput\SwitchInput::class => static function(Container $container, array $params, array $config) {
        $config = \yii\helpers\ArrayHelper::merge([
            'pluginOptions' => [
                'offText' => \Yii::t('app', 'Off'),
                'onText' => \Yii::t('app', 'On'),
            ]
        ], $config);
        return new \kartik\switchinput\SwitchInput($config);
    },
    \kartik\grid\GridView::class => [
        'export' => false
    ],


];
