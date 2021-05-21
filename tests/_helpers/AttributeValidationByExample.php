<?php
declare(strict_types=1);

namespace prime\tests\_helpers;

use yii\base\Model;
use yii\db\ActiveRecord;
use function iter\keys;

trait AttributeValidationByExample
{
    abstract private function getModel(): Model;

    /**
     * Must return an array of arrays containing the properties and values for the model.
     * If a value is a closure, it will be called and the result will be stored in the model.
     * Validation result must yield true
     */
    abstract public function validSamples(): iterable;

    /**
     * Must return an array of arrays containing the properties and values for the model.
     * If a value is a closure, it will be called and the result will be stored in the model.
     * Validation result must yield false
     */
    abstract public function invalidSamples(): iterable;


    public function testValidationRulesAreNotEmptyAndValid(): void
    {
        $model = $this->getModel();
        $class = get_class($model);
        $this->assertNotEmpty($model->rules(), "Model $class has no validation rules");
        $model->getValidators();
    }

    private function getHydratedModel(array $attributes, null|string $scenario): Model
    {
        $model = $this->getModel();
        $model->scenario = $scenario ?? Model::SCENARIO_DEFAULT;
        foreach ($attributes as $key => $value) {
            if ($value instanceof \Closure) {
                $model->$key = $value();
            } else {
                $model->$key = $value;
            }
        }
        return $model;
    }

    private static array $validAttributes;
    private static array $invalidAttributes;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$validAttributes = [];
        self::$invalidAttributes = [];
    }

    /**
     * @dataProvider validSamples
     * @depends testValidationRulesAreNotEmptyAndValid
     */
    public function testValidation(array $attributes, null|string $scenario): void
    {
        $model = $this->getHydratedModel($attributes, $scenario);

        $this->assertTrue($model->validate(), print_r($model->errors, true));

        foreach (keys($attributes) as $attribute) {
            self::$validAttributes[$attribute] = true;
        }
    }

    /**
     * @dataProvider invalidSamples
     * @depends testValidationRulesAreNotEmptyAndValid
     */
    public function testValidationInvalid(array $attributes, null|string $scenario = null, null|array $existing = null): void
    {
        if (isset($existing)) {
            $existingModel = $this->getHydratedModel($existing, $scenario);
            $this->assertTrue($existingModel->save(), "Failed to create existing record: " . print_r($existingModel->errors, true));
        }

        $model = $this->getHydratedModel($attributes, $scenario);
        $this->assertFalse($model->validate(), "Validation expected to fail with attributes: " . print_r($model->attributes, true));

        foreach (keys($model->getErrors()) as $attribute) {
            self::$invalidAttributes[$attribute] = true;
        }
    }

    /**
     * @depends testValidationInvalid
     * @depends testValidation
     */
    public function testAllAttributesAreValidated(): void
    {
        $model = $this->getModel();
        $attributes = $model->attributes;
        if ($model instanceof ActiveRecord) {
            unset($attributes[$model::primaryKey()[0]]);
        }

        foreach (keys($attributes) as $attribute) {
            if (!$model->isAttributeSafe($attribute)) {
                continue;
            }

            $this->assertArrayHasKey($attribute, self::$validAttributes, "Attribute {$attribute} does not appear in any valid example");
            $this->assertArrayHasKey($attribute, self::$invalidAttributes, "Attribute {$attribute} does not appear in any invalid example");
        }
    }
}
