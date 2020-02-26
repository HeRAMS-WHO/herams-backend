<?php


namespace prime\tests\unit\models;


use Codeception\Test\Unit;
use yii\base\Model;

abstract class ModelTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    abstract protected function getModel(): Model;

    /**
     * Must return an array of arrays containing the properties and values for the model.
     * If a value is a closure, it will be called and the result will be stored in the model.
     * Validation result must yield true
     * @return array
     */
    abstract public function validSamples(): array;

    /**
     * Must return an array of arrays containing the properties and values for the model.
     * If a value is a closure, it will be called and the result will be stored in the model.
     * Validation result must yield false
     * @return array
     */
    abstract public function invalidSamples(): array;

    public function testGetModel()
    {
        $model = $this->getModel();
        $this->assertInstanceOf(Model::class, $model);
    }

    /**
     * @depends testGetModel
     */
    public function testValidationRules()
    {
        $model = $this->getModel();
        $class = get_class($model);
        $this->assertNotEmpty($model->rules(), "Model $class has no validation rules");
    }

    /**
     * @dataProvider validSamples
     * @depends testValidationRules
     */
    public function testValidation(array $attributes, ?string $scenario)
    {
        $model = $this->getModel();
        $this->assertInstanceOf(Model::class, $model);
        $model->scenario = $scenario ?? Model::SCENARIO_DEFAULT;
        foreach($attributes as $key => $value) {
            if ($value instanceof \Closure) {
                $model->$key = $value();
            } else {
                $model->$key = $value;
            }

        }
        $this->assertTrue($model->validate(), print_r($model->errors, true));
    }

    /**
     * @dataProvider invalidSamples
     * @depends testValidationRules
     */
    public function testValidationInvalid(array $attributes, ?string $scenario)
    {
        $model = $this->getModel();
        $this->assertInstanceOf(Model::class, $model);
        $model->scenario = $scenario ?? Model::SCENARIO_DEFAULT;
        foreach($attributes as $key => $value) {
            if ($value instanceof \Closure) {
                $model->$key = $value();
            } else {
                $model->$key = $value;
            }

        }
        $this->assertFalse($model->validate(), "Validation expected to fail with attributes: " . print_r($model->attributes, true));
    }
}