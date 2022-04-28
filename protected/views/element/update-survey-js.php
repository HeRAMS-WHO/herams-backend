<?php

declare(strict_types=1);

use prime\assets\DashboardElementUiBundle;
use prime\components\View;
use prime\models\ar\Element as ARElement;
use prime\models\ar\Page;
use prime\models\ar\Project;
use prime\models\forms\Element as FormElement;
use prime\objects\enums\DataSort;
use prime\widgets\Section;
use yii\bootstrap\Html;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * @var ARElement|FormElement $model
 * @var Project $project
 * @var View $this
 * @var Page $page
 * @var string $url
 */

$this->title = $model->isNewRecord
    ? \Yii::t('app', 'Create element')
    : \Yii::t('app', 'Update element');

Section::begin()
    ->withHeader($this->title);
$bundle = DashboardElementUiBundle::register($this);
\prime\assets\IframeResizeBundle::register($this);
echo Html::beginTag('div', ['id' => 'app', 'style' => []]);
echo Html::endTag('div');

echo Html::tag('iframe', 'Your browser does not support iframes', [
    'id' => 'preview',
    'scrolling' => 'no',
    'src' => Url::to(['element/preview-for-survey-js', 'projectId' => $project->id, 'config' => $model->toArray()], true),
    'style' => [
        'width' => '100%'
    ]
]);

$dataSortOptions = Json::encode(DataSort::options());
$previewUrl = Json::encode(Url::to(['element/preview-for-survey-js', 'projectId' => $project->id, 'config' => '__placeholder__'], true));
$this->registerJs(<<<JS
  {$bundle->getImport("DashboardElementUI")}
  
  const previewUrl = $previewUrl;
  iFrameResize({
    log: false
  }, document.getElementById('preview'));
  const app = new DashboardElementUI({
    target: document.getElementById('app'),
    props: {
      initialValues: {
        width: 1,
        height: 5,
        title: "Preset",
        variables: [],
        colorMap: {},
        groupingVariable: ""
      },
      previewUrl: previewUrl,
      onSubmit: (values) => {
          console.log(values);
          const data = {...values};
          data.pageId = {$page->id};
        
          // Submit to API.
          Herams.createElement('/api/element', data)
      },
      onChange: values => {
        const params = encodeURIComponent(JSON.stringify(values));
        console.log("onChange", values);
        document.getElementById('preview').src = previewUrl.replace('__placeholder__', params); 
      },
      variables: (async () => {
          const response = await fetch('/api/project/{$project->id}/variables');
          return response.json();
      })(),
      dataSortOptions: ${dataSortOptions}
      
      
       
    }
  })
  JS, View::POS_MODULE
);
Section::end();
