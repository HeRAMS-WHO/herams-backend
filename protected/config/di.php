<?php
declare(strict_types=1);

use kartik\dialog\Dialog;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use kartik\switchinput\SwitchInput;
use prime\assets\JqueryBundle;
use prime\components\GlobalPermissionResolver;
use prime\components\ReadWriteModelResolver;
use prime\components\SingleTableInheritanceResolver;
use prime\helpers\AccessCheck;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use prime\objects\enums\Language;
use prime\repositories\FacilityRepository;
use prime\repositories\ProjectRepository;
use prime\repositories\ResponseRepository;
use prime\repositories\WorkspaceRepository;
use prime\widgets\LocalizableInput;
use SamIT\abac\engines\SimpleEngine;
use SamIT\abac\interfaces\PermissionRepository;
use SamIT\abac\interfaces\Resolver;
use SamIT\abac\interfaces\RuleEngine;
use SamIT\abac\repositories\CachedReadRepository;
use SamIT\abac\repositories\PreloadingSourceRepository;
use SamIT\abac\resolvers\ChainedResolver;
use SamIT\Yii2\abac\ActiveRecordRepository;
use SamIT\Yii2\abac\ActiveRecordResolver;
use yii\di\Container;
use yii\helpers\ArrayHelper;
use yii\web\JqueryAsset;
use function iter\filter;

return [
    \Psr\Http\Client\ClientInterface::class => \GuzzleHttp\Client::class,
    \Psr\Http\Message\RequestFactoryInterface::class => \Http\Factory\Guzzle\RequestFactory::class,
    \prime\helpers\ModelHydrator::class => \prime\helpers\ModelHydrator::class,
    LocalizableInput::class => function (Container $container, array $params, array $config) {
        if (!isset($config['languages'])) {
            $config['languages'] = Language::toLocalizedArrayWithoutSourceLanguage(\Yii::$app->language);
        }
        return new LocalizableInput($config);
    },
    Dialog::class => \yii\base\Widget::class,
    AccessCheckInterface::class => AccessCheck::class,
    AccessCheck::class => static function () {
        return new AccessCheck(\Yii::$app->user);
    },
    \prime\helpers\LimesurveyDataLoader::class => \prime\helpers\LimesurveyDataLoader::class,
    JqueryAsset::class => JqueryBundle::class,
    PermissionRepository::class => PreloadingSourceRepository::class,
    PreloadingSourceRepository::class =>
        fn (Container $container) => new PreloadingSourceRepository($container->get(CachedReadRepository::class)),
    CachedReadRepository::class => function (Container $container) {
        return new CachedReadRepository($container->get(ActiveRecordRepository::class));
    },
    RuleEngine::class => static fn() => new SimpleEngine(require __DIR__ . '/rule-config.php'),
    Resolver::class => static function (): Resolver {
        return new ChainedResolver(
            new SingleTableInheritanceResolver(),
            new ReadWriteModelResolver(),
            new ActiveRecordResolver(),
            new GlobalPermissionResolver()
        );
    },
    ProjectRepository::class => ProjectRepository::class,
    WorkspaceRepository::class => WorkspaceRepository::class,
    FacilityRepository::class => FacilityRepository::class,
    ResponseRepository::class => ResponseRepository::class,
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
        return new ActionColumn($config);
    },
    SwitchInput::class => static function (Container $container, array $params, array $config) {
        $config = ArrayHelper::merge([
            'pluginOptions' => [
                'offText' => \Yii::t('app', 'Off'),
                'onText' => \Yii::t('app', 'On'),
            ]
        ], $config);
        return new SwitchInput($config);
    },
    GridView::class => static function (Container $container, array $params, array $config): GridView {

        $result = new GridView(array_merge([
            'dataColumnClass' => \prime\widgets\FunctionGetterDataColumn::class,
        ], $config));
        $result->export = false;
        $result->toggleData = false;
        return $result;
    },


];
