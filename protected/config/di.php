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
    }

];
