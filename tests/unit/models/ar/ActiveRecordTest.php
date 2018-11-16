<?php


namespace prime\tests\unit\models\ar;

use prime\tests\unit\models\ModelTest;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * Class ActiveRecordTest
 * @package prime\tests\unit\models\ar
 */
abstract class ActiveRecordTest extends ModelTest
{
    public function testGetModel()
    {
        $model = $this->getModel();
        $this->assertInstanceOf(ActiveRecord::class, $model);
    }


    /**
     * @dataProvider validSamples
     * @depends testValidationRules
     */
    public function testSave(array $attributes, ?string $scenario)
    {
        $model = $this->getModel();
        $model->scenario = $scenario ?? Model::SCENARIO_DEFAULT;
        foreach($attributes as $key => $value) {
            if ($value instanceof \Closure) {
                $model->$key = $value();
            } else {
                $model->$key = $value;
            }
        }
        $this->assertTrue($model->validate(), 'ActiveRecord validation failed');
        $this->assertTrue($model->save(false), 'ActiveRecord save failed: ' . print_r($model->attributes, true));
    }

    /**
     * @return ActiveRecord
     */
    final protected function getModel(): Model
    {
        $class = strtr(get_class($this), [
            __NAMESPACE__ => 'prime\models\ar',
            'Test' => ''
        ]);
        return new $class;
    }

}