<?php

declare(strict_types=1);

use prime\assets\ReactAsset;
use prime\widgets\Section;
use prime\widgets\surveyJs\Creator2 as Creator2;
use yii\helpers\Html;
use yii\web\View;

ReactAsset::register($this);

/**
 * @var View $this
 */

$this->title = \Yii::t('app', 'Create survey');

$this->registerCss(
    <<<CSS
:root {
    --max-site-width:calc(100vw - 40px);
}

div.content {
    border-radius: 0;
    padding: 0;
}

CSS
);

Section::begin()
    ->withHeader($this->title);

$creator = new Creator2();
$creator->setConfig();
$config = $creator->getConfig();

?>
    <!-- Mount point for the React component -->
    <div id="SurveyCreatorWidget" data-config="<?= Html::encode(base64_encode($config)) ?>">
    </div>
<?php

Section::end();
