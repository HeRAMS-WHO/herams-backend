<?php


namespace prime\tests\unit\models\ar;

use prime\models\ar\Favorite;
use prime\tests\unit\models\ModelTest;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;

/**
 * @covers \prime\models\ActiveRecord
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
    final protected function getModel(): \prime\models\ActiveRecord
    {
        $class = strtr(get_class($this), [
            __NAMESPACE__ => 'prime\models\ar',
            'Test' => ''
        ]);
        return new $class;
    }


    public function testGetDisplayField(): void
    {
        $this->assertNotEmpty($this->getModel()->getDisplayField());
    }

    public function testGetDisplayFieldCascade(): void
    {
        $model = new class extends \prime\models\ActiveRecord {
            public function attributes()
            {
                return ['name', 'email'];
            }

            public function getPrimaryKey($asArray = false)
            {
                throw new NotSupportedException('This should not be called from the test');
            }

            public function getAttribute($name)
            {
                switch ($name) {
                    case 'email':
                        return 'email';
                    case 'title':
                        throw new NotSupportedException();
                    case 'name':
                        return null;
                    default:
                        return parent::getAttribute($name);
                }
            }
        };
        $this->assertFalse($model->hasAttribute('title'));
        $this->assertTrue($model->hasAttribute('name'));
        $this->assertTrue($model->hasAttribute('email'));
        $this->assertSame('email', $model->displayField);
    }

}