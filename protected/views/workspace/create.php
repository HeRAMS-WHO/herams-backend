<?php

declare(strict_types=1);

use prime\assets\ReactAsset;
use prime\components\View;
use prime\widgets\Section;
use prime\widgets\survey\SurveyFormWidget;
use yii\bootstrap\Html;

/**
 * @var \prime\interfaces\SurveyFormInterface $form
 * @var View $this
 */
assert($this instanceof View);
ReactAsset::register($this);

$this->title = \Yii::t('app', "Create new workspace");

$this->beginBlock('tabs');
$this->endBlock();

Section::begin();

$survey = new SurveyFormWidget();
$survey->withForm($form)->setConfig();
$config = $survey->getConfig();

?>
    <div id="CreateWorkspace" data-survey-settings="<?= Html::encode(base64_encode($config)) ?>"
    >
    </div>
<?php



Section::end();
