<?php

declare(strict_types=1);

use prime\components\View;
use prime\widgets\Section;
use prime\widgets\survey\SurveyFormWidget;
use yii\bootstrap\Html;

/**
 * @var \prime\interfaces\SurveyFormInterface $form
 * @var View $this
 */
assert($this instanceof View);

$this->title = \Yii::t('app', "Create a new project");

$this->beginBlock('tabs');
$this->endBlock();

Section::begin()
;

$survey = new SurveyFormWidget();
$survey->withForm($form)->setConfig();
$config = $survey->getConfig();

?>
    <div id="EditAdminSituation" data-survey-settings="<?= Html::encode(base64_encode($config)) ?>"
    >
    </div>
<?php

Section::end();
