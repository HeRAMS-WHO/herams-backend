<?php

namespace prime\tests\codeception\unit\generators;

use prime\factories\GeneratorFactory;
use prime\interfaces\ConfigurableGeneratorInterface;
use prime\models\ar\UserData;
use prime\objects\Signature;

class GeneratorTest extends \yii\codeception\TestCase
{
    protected function _before()
    {

    }

    protected function _after()
    {
        
    }

    /**
     * @param $name
     * @throws \yii\web\HttpException
     * @dataProvider generatorProvider
     */
    public function testConfiguration($name, $class)
    {
        /** @var \prime\interfaces\ConfigurableGeneratorInterface $generator */
        $generator = \prime\factories\GeneratorFactory::get($name);
        $this->assertInstanceOf(\prime\interfaces\ReportGeneratorInterface::class, $generator);
        $this->assertInstanceOf($class, $generator);

        if(!$generator instanceof ConfigurableGeneratorInterface) {
            $this->markTestSkipped("Generator is not configurable.");
        }

        $result = $generator->renderConfiguration(
            new \prime\objects\ResponseCollection(),
            new \prime\objects\SurveyCollection(),
            \prime\models\ar\Project::find()->one(),
            new Signature('', 0, ''),
            new UserData()
        );

        // A configuration page is expected to have at least one html input field.
        $this->assertRegExp('/<input|<select|<textarea/', $result);
    }

    /**
     * @dataProvider generatorProvider
     * @throws \yii\web\HttpException
     */
    public function testRender($name, $class)
    {
        /** @var \prime\interfaces\ReportGeneratorInterface $generator */
        $generator = \prime\factories\GeneratorFactory::get($name);
        $this->assertInstanceOf(\prime\interfaces\ReportGeneratorInterface::class, $generator);
        $this->assertInstanceOf($class, $generator);
        $generator->render(
            new \prime\objects\ResponseCollection(),
            new \prime\objects\SurveyCollection(),
            \prime\models\ar\Project::find()->one(),
            new Signature('', 0, ''),
            new UserData()
        );
    }

    public static function generatorProvider()
    {
        return array_map(function($name, $class) {
            return [$name, $class];

        }, array_flip(GeneratorFactory::classes()), GeneratorFactory::classes());
    }
}