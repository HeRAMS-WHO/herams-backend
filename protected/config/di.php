<?php
declare(strict_types=1);

use kartik\grid\ActionColumn;
use prime\assets\JqueryBundle;
use prime\assets\PjaxBundle;
use prime\models\ar\Permission;
use SamIT\abac\interfaces\PermissionRepository;
use SamIT\abac\repositories\CachedReadRepository;
use SamIT\abac\repositories\PreloadingSourceRepository;
use SamIT\Yii2\abac\ActiveRecordRepository;
use SamIT\Yii2\abac\ActiveRecordResolver;
use yii\di\Container;
use yii\web\JqueryAsset;
use yii\widgets\PjaxAsset;

return [
    \prime\helpers\LimesurveyDataLoader::class => \prime\helpers\LimesurveyDataLoader::class,
    JqueryAsset::class => JqueryBundle::class,
    PermissionRepository::class => PreloadingSourceRepository::class,
    PreloadingSourceRepository::class => function (Container $container) {
        return new PreloadingSourceRepository($container->get(CachedReadRepository::class));
    },
    CachedReadRepository::class => function (Container $container) {
        return new CachedReadRepository($container->get(ActiveRecordRepository::class));
    },
    \SamIT\abac\interfaces\RuleEngine::class => static function () {
        return new \SamIT\abac\engines\SimpleEngine(require __DIR__ . '/rule-config.php');
    },
    \SamIT\abac\interfaces\Resolver::class => static function (): \SamIT\abac\interfaces\Resolver {
        return new \SamIT\abac\resolvers\ChainedResolver(
            new \prime\components\SingleTableInheritanceResolver(),
            new ActiveRecordResolver(),
            new \prime\components\GlobalPermissionResolver()
        );
    },
    ActiveRecordRepository::class => static function () {
        return new ActiveRecordRepository(Permission::class, [
            ActiveRecordRepository::SOURCE_ID => ActiveRecordRepository::SOURCE_ID,
            ActiveRecordRepository::SOURCE_NAME => 'source',
            ActiveRecordRepository::TARGET_ID => ActiveRecordRepository::TARGET_ID,
            ActiveRecordRepository::TARGET_NAME => 'target',
            ActiveRecordRepository::PERMISSION => ActiveRecordRepository::PERMISSION
        ]);
    },
    ActionColumn::class => static function (Container $container, array $params = [], array $config = []): \yii\grid\ActionColumn {
        if (!isset($config['header'])) {
            $config['header'] = \Yii::t('app', 'Actions');
        }
        $result = new ActionColumn($config);
        return $result;
    },
    \kartik\switchinput\SwitchInput::class => static function (Container $container, array $params, array $config) {
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
