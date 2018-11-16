<?php


namespace prime\tests\unit\models\forms;


use prime\models\ar\Tool;
use prime\models\forms\Share;
use prime\tests\unit\models\ModelTest;
use yii\base\Model;

class ShareTest extends ModelTest
{

    protected function getModel(): Model
    {
        $baseModel = new Tool();
        $baseModel->title = __CLASS__;
        $baseModel->base_survey_eid = 12345;
        $this->assertTrue($baseModel->save());
        return new Share($baseModel);
    }

    /**
     * Must return an array of arrays containing the properties and values for the model.
     * If a value is a closure, it will be called and the result will be stored in the model.
     * Validation result must yield true
     * @return array
     */
    public function validSamples(): array
    {
        return [];
    }

    /**
     * Must return an array of arrays containing the properties and values for the model.
     * If a value is a closure, it will be called and the result will be stored in the model.
     * Validation result must yield false
     * @return array
     */
    public function invalidSamples(): array
    {
        return [];
    }
}