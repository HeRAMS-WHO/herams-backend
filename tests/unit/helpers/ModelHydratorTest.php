<?php

declare(strict_types=1);

namespace prime\tests\unit\helpers;

use Codeception\Test\Unit;
use herams\common\helpers\ModelHydrator;
use prime\objects\EnumSet;
use prime\objects\LanguageSet;
use yii\base\DynamicModel;
use yii\helpers\Inflector;
use yii\validators\SafeValidator;
use yii\web\Request;

/**
 * @covers \herams\common\helpers\ModelHydrator
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
                    'integer_property' => -13,
                    'integer_or_null_property' => 14,
                    'language_set' => ['en-US', 'fr-FR'],
                ],

            ],
            [
                [
                    'integer_property' => -13,
                    'integer_or_null_property' => 14,
                    'language_set' => ['en-US', 'fr-FR'],
                ],

            ],

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

        $this->targetType = new class(1, 1, LanguageSet::from([]), 1) {
            public int|null $integerOrNullProperty = 5;

            public function __construct(
                public int|null $unknownProperty,
                public int $integerProperty,
                public LanguageSet $languageSet,
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
        $this->doAssertions($sourceAttributes, $target);
    }

    private function doAssertions(array $sourceAttributes, object $target): void
    {
        $this->assertInstanceOf(get_class($this->targetType), $target);
        foreach ($sourceAttributes as $attribute => $expected) {
            $value = $target->{Inflector::variablize($attribute)};
            if ($value instanceof EnumSet) {
                $value = $value->toArray();
            }
            $this->assertSame($expected, $value);
        }
    }

    /**
     * @dataProvider camelCasedSourceAttributeProvider
     */
    public function testHydrateConstructorCamelCasedSource(array $sourceAttributes): void
    {
        $source = new DynamicModel($sourceAttributes);

        $target = $this->modelHydrator->hydrateConstructor($source, get_class($this->targetType));

        $this->doAssertions($sourceAttributes, $target);
    }

    public function testHydrateFromRequestArray(): void
    {
        $testName = 'Test name';
        $model = new DynamicModel([
            'name' => null,
        ]);
        $model->addRule(['name'], SafeValidator::class);
        $data = [
            'name' => $testName,
        ];

        $this->modelHydrator->hydrateFromRequestArray($model, $data);
        $this->assertEquals($testName, $model->name);
    }

    public function testHydrateFromRequestArrayInvalid(): void
    {
        $testName = 'Test name';
        $model = new DynamicModel([
            'name' => null,
        ]);
        $data = [
            'name' => $testName,
        ];

        $this->modelHydrator->hydrateFromRequestArray($model, $data);
        $this->assertEmpty($model->name);
    }

    public function testHydrateFromRequestBody(): void
    {
        $testName = 'Test name';
        $model = new DynamicModel([
            'name' => null,
        ]);
        $model->addRule(['name'], SafeValidator::class);
        $requestData = [
            $model->formName() => [
                'name' => $testName,
            ],
        ];
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->expects($this->once())
            ->method('getBodyParams')
            ->willReturn($requestData);
        $request->expects($this->any())
            ->method('getIsPost')
            ->willReturn(true);

        $this->modelHydrator->hydrateFromRequestBody($model, $request);
        $this->assertEquals($testName, $model->name);
    }

    public function testHydrateFromRequestBodyInvalid(): void
    {
        $model = new DynamicModel([
            'name' => null,
        ]);
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->expects($this->any())
            ->method('getIsPost')
            ->willReturn(false);
        $request->expects($this->any())
            ->method('getIsPut')
            ->willReturn(false);

        $this->expectException(\InvalidArgumentException::class);
        $this->modelHydrator->hydrateFromRequestBody($model, $request);
    }

    public function testHydrateFromRequestQuery(): void
    {
        $testName = 'Test name';
        $model = new DynamicModel([
            'name' => null,
        ]);
        $model->addRule(['name'], SafeValidator::class);
        $requestData = [
            $model->formName() => [
                'name' => $testName,
            ],
        ];
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->expects($this->once())
            ->method('getQueryParams')
            ->willReturn($requestData);

        $this->modelHydrator->hydrateFromRequestQuery($model, $request);
        $this->assertEquals($testName, $model->name);
    }

    public function testInvalidIntegerValue(): void
    {
        $source = new DynamicModel([
            'integerProperty' => 'abc',
        ]);
        $type = new class(null) {
            public function __construct(
                public int|null $integerProperty
            ) {
            }
        };
        $this->expectException(\Exception::class);
        $this->modelHydrator->hydrateConstructor($source, get_class($type));
    }

    public function testInvalidFloatValue(): void
    {
        $source = new DynamicModel([
            'floatProperty' => 'abc',
        ]);
        $type = new class(null) {
            public function __construct(
                public float|null $floatProperty
            ) {
            }
        };
        $this->expectException(\Exception::class);
        $this->modelHydrator->hydrateConstructor($source, get_class($type));
    }

    public function testFloatValue(): void
    {
        $source = new DynamicModel([
            'floatProperty1' => '5.13',
            'floatProperty2' => 5.14,
        ]);
        $type = new class(1, 1) {
            public function __construct(
                public float $floatProperty1,
                public float $floatProperty2
            ) {
            }
        };
        $model = $this->modelHydrator->hydrateConstructor($source, get_class($type));
        $this->assertEqualsWithDelta($source->floatProperty1, $model->floatProperty1, 0.0001);
        $this->assertEqualsWithDelta($source->floatProperty2, $model->floatProperty2, 0.0001);
    }

    public function testBoolean(): void
    {
        $source = new DynamicModel([
            'property1' => '1',
            'property2' => '0',
            'property3' => true,
            'property4' => false,
        ]);

        $type = new class(false, false, false, false) {
            public function __construct(
                public bool $property1,
                public bool $property2,
                public bool $property3,
                public bool $property4,
            ) {
            }
        };
        $model = $this->modelHydrator->hydrateConstructor($source, get_class($type));
        $this->assertTrue($model->property1);
        $this->assertFalse($model->property2);
        $this->assertTrue($model->property3);
        $this->assertFalse($model->property4);
    }
}
