<?php

declare(strict_types=1);

use prime\assets\DashboardElementUiBundle;
use prime\components\View;
use prime\objects\enums\ChartType;
use prime\objects\enums\DataSort;
use prime\widgets\Section;
use yii\bootstrap\Html;
use yii\helpers\Url;

/**
 * @var \prime\models\ar\elements\Svelte $model
 * @var \prime\values\ProjectId $projectId
 * @var View $this
 * @var \prime\values\PageId $pageId
 * @var string $endpointUrl
 */

$this->title = $model->isNewRecord
    ? \Yii::t('app', 'Create element')
    : \Yii::t('app', 'Update element');

Section::begin()
    ->withHeader($this->title);
$bundle = DashboardElementUiBundle::register($this);
\prime\assets\IframeResizeBundle::register($this);
echo Html::beginTag('div', [
    'style' => [
        'display' => 'flex',
    ],
]);
echo Html::beginTag('div', [
    'id' => 'app',
    'style' => [
        'min-width' => '40%',
    ],
]);
echo Html::endTag('div');

echo Html::tag('iframe', 'Your browser does not support iframes', [
    'id' => 'preview',
    'scrolling' => 'no',
    'src' => Url::to([
        'element/preview-for-survey-js',
        'projectId' => $projectId,
        'config' => json_encode($model->toConfigurationArray()),
    ], true),
    'style' => [
        'display' => 'block',
        'width' => '50%',
    ],
]);
echo Html::endTag('div');
$config = json_encode([
    'previewUrl' => Url::to([
        'element/preview-for-survey-js',
        'projectId' => $projectId,
        'config' => '__placeholder__',
    ], true),
    'props' => [
        'dataSortOptions' => DataSort::options(),
        'chartTypes' => ChartType::options(),
        'initialValues' => $model->toConfigurationArray(),
    ],
    'endpointUrl' => Url::to(
        $endpointUrl
    ),
], JSON_PRETTY_PRINT);

$this->registerJs(
    <<<JS
  const config = $config;
  {$bundle->getImport("DashboardElementUI")}
  
  iFrameResize({
    log: false
  }, document.getElementById('preview'));
  
  const props = {...config.props,
      onSubmit: (values) => {
          console.log(values);
          const data = {...values};
          data.pageId = {$pageId};
        
          // Submit to API.
          Herams.createElement(config.endpointUrl, data)
      },
      onChange: values => {
        const params = encodeURIComponent(JSON.stringify(values));
        console.log("onChange", values);
        document.getElementById('preview').src = config.previewUrl.replace('__placeholder__', params); 
      },
      variables: (async () => {
          const response = await fetch('/api/project/{$projectId}/variables');
          return response.json();
      })()
      
      
       
  };
  const app = new DashboardElementUI({
    target: document.getElementById('app'),
    props: props
  })
  JS,
    View::POS_MODULE
);
Section::end();
