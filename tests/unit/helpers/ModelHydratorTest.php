<?php
declare(strict_types=1);

namespace prime\tests\unit\helpers;

use Codeception\Test\Unit;
use prime\helpers\ModelHydrator;
use yii\base\DynamicModel;
use yii\base\Model;
use yii\helpers\Inflector;

/**
 * @covers \prime\helpers\ModelHydrator
 */
class ModelHydratorTest extends Unit
{
    private ModelHydrator $modelHydrator;
    private object $targetType;

    public function sourceAttributeProvider(): array
    {
        return [
            [
                [
                    'integer_property' => 13,
                    'integer_or_null_property' => 14
                ]

            ]


        ];
    }

    public function camelCasedSourceAttributeProvider(): array
    {
        $result = [];
        foreach ($this->sourceAttributeProvider() as $parameterSet) {
            $newParams = [];
            foreach ($parameterSet[0] as $key => $value) {
                $newParams[Inflector::variablize($key)] = $value;
            }
            $result[] = [$newParams];
        }
        return $result;
    }

    protected function _before()
    {
        parent::_before();
        $this->modelHydrator = new ModelHydrator();

        $this->targetType = new class(1, 1, 1) {
            public int|null $integerOrNullProperty = 5;
            public function __construct(
                public int|null $unknownProperty,
                public int $integerProperty,
                int|null $integerOrNullProperty
            ) {
                $this->integerOrNullProperty = $integerOrNullProperty;
            }
        };
    }

    /**
     * @dataProvider sourceAttributeProvider
     */
    public function testHydrateConstructorIdCasedSource(array $sourceAttributes): void
    {
        $source = new DynamicModel($sourceAttributes);

        $target = $this->modelHydrator->hydrateConstructor($source, get_class($this->targetType));
        $this->assertInstanceOf(get_class($this->targetType), $target);
        foreach ($sourceAttributes as $attribute => $value) {
            $this->assertSame($value, $target->{Inflector::variablize($attribute)});
        }
    }

    /**
     * @dataProvider camelCasedSourceAttributeProvider
     */
    public function testHydrateConstructorCamelCasedSource(array $sourceAttributes): void
    {
        $source = new DynamicModel($sourceAttributes);

        $target = $this->modelHydrator->hydrateConstructor($source, get_class($this->targetType));
        $this->assertInstanceOf(get_class($this->targetType), $target);
        foreach ($sourceAttributes as $attribute => $value) {
            $this->assertSame($value, $target->{Inflector::variablize($attribute)});
        }
    }
}
