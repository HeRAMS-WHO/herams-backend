<?php

namespace prime\tests\codeception\unit\generators;

use app\components\InlineView;
use prime\models\ar\UserData;
use prime\objects\Signature;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use SamIT\LimeSurvey\JsonRpc\Concrete\Response;

class ArrayResponse extends Response implements ResponseInterface {
    public function __construct(array $data, $surveyId)
    {
        $this->attributes = $data;
        $this->surveyId = $surveyId;
    }
}

class OscarTest extends \Codeception\Test\Unit
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
     */
//    public function testConfiguration($name, $class)
//    {
//        /** @var \prime\interfaces\ConfigurableGeneratorInterface $generator */
//        $generator = \prime\factories\GeneratorFactory::get($name);
//        $this->assertInstanceOf(\prime\interfaces\ReportGeneratorInterface::class, $generator);
//        $this->assertInstanceOf($class, $generator);
//
//        if(!$generator instanceof ConfigurableGeneratorInterface) {
//            $this->markTestSkipped("Generator is not configurable.");
//        }
//
//        $result = $generator->renderConfiguration(
//            new \prime\objects\ResponseCollection(),
//            new \prime\objects\SurveyCollection(),
//            \prime\models\ar\Project::find()->one(),
//            new Signature('', 0, ''),
//            new UserData()
//        );
//
//        // A configuration page is expected to have at least one html input field.
//        $this->assertRegExp('/<input|<select|<textarea/', $result);
//    }

    /**
     * @throws \yii\web\HttpException
     */
    public function testRender()
    {
        /** @var \prime\interfaces\ReportGeneratorInterface $generator */
        $generator = new \prime\reportGenerators\oscar\Generator(new InlineView());

        $responses = new \prime\objects\ResponseCollection();
        $responses->append(new ArrayResponse(array (
            'id' => '152',
            'submitdate' => NULL,
            'lastpage' => '4',
            'startlanguage' => 'en',
            'token' => 'b2ush39jmuvw92p',
            'startdate' => '2016-08-09 15:17:14',
            'datestamp' => '2016-08-18 14:27:46',
            'UOID' => '1755Um',
            'gi1' => '1.0000000000',
            'gi2' => '2016-08-01 00:00:00',
            'gi3' => '2016-08-04 00:00:00',
            'OBJDISPLAY' => 'SitRep # 1.0000000000',
            'genindic[SQ001]' => '120000.0000000000',
            'genindic[SQ002]' => '1200.0000000000',
            'genindic[SQ003]' => '52000.0000000000',
            'genindic[SQ004]' => '12000.0000000000',
            'genindic[SQ005]' => '12000.0000000000',
            'highlHTML' => '
hrthe


',
            'hi1' => '12.0000000000',
            'hi2' => '45.0000000000',
            'hi3' => '54.0000000000',
            'hri1[SQ001_SQ001]' => '6546',
            'hri1[SQ002_SQ001]' => '6544',
            'hri1[SQ003_SQ001]' => '6846',
            'hri1[SQ004_SQ001]' => '6879753',
            'hri1[SQ005_SQ001]' => '54654',
            'hri1[SQ006_SQ001]' => '54654',
            'hri1[SQ007_SQ001]' => '68434354',
            'hri1[SQ008_SQ001]' => '354354',
            'hri1[SQ009_SQ001]' => '354',
            'hri2' => '-oth-',
            'hri2[other]' => '3654',
            'hri3' => '-oth-',
            'hri3[other]' => '154',
            'hri4' => '-oth-',
            'hri4[other]' => '125',
            'hri5' => '-oth-',
            'hri5[other]' => '541',
            'hri6' => '-oth-',
            'hri6[other]' => '654',
            'hri7a' => '-oth-',
            'hri7a[other]' => '524',
            'hri7b' => NULL,
            'hri8a' => '-oth-',
            'hri8a[other]' => '874',
            'hri8b' => NULL,
            'hri9' => '-oth-',
            'hri9[other]' => '124',
            'ew1' => '-oth-',
            'ew1[other]' => '125',
            'ew2' => '-oth-',
            'ew2[other]' => '65',
            'ew3' => '-oth-',
            'ew3[other]' => '24',
            'situpHTML' => '
yersg


',
            'hnp1HTML' => '
jhd


',
            'hnp2HTML' => '',
            'hnp3HTML' => '',
            'hnp4HTML' => '',
            'hnp5HTML' => '',
            'hnp6HTML' => '',
            'hnp7HTML' => '',
            'hnp8' => '',
            'hnp8a' => NULL,
            'hnp8HTML' => NULL,
            'hca1HTML' => '
jedjy


',
            'hca2HTML' => '',
            'hca3HTML' => '',
            'hca4HTML' => '',
            'hca5HTML' => '',
            'hca6HTML' => '',
            'hca7HTML' => '',
            'hca8' => '',
            'hca8a' => NULL,
            'hca8HTML' => NULL,
            'resmob1HTML' => '
jdryr


',
            'resmob2[rmwho_SQ001]' => '12',
            'resmob2[rmwho_SQ002]' => '12',
            'resmob2[rmhc_SQ001]' => '12',
            'resmob2[rmhc_SQ002]' => '12',
            'resmob3' => '',
            'hr1[1]' => '12.0000000000',
            'hr1[2]' => '54.0000000000',
            'hr1[3]' => '54.0000000000',
            'hr1[4]' => '36.0000000000',
            'hr1[5]' => '5.0000000000',
            'hr1[6]' => '0.0000000000',
            'hr2' => '',
            'backgHTML' => '
ue56u5r


',
            'Cont1[SQ001_SQ001]' => '',
            'Cont1[SQ001_SQ002]' => '',
            'Cont1[SQ001_SQ003]' => '',
            'Cont1[SQ001_SQ004]' => '',
            'Cont1[SQ002_SQ001]' => '',
            'Cont1[SQ002_SQ002]' => '',
            'Cont1[SQ002_SQ003]' => '',
            'Cont1[SQ002_SQ004]' => '',
            'Cont1[SQ003_SQ001]' => '',
            'Cont1[SQ003_SQ002]' => '',
            'Cont1[SQ003_SQ003]' => '',
            'Cont1[SQ003_SQ004]' => '',
            'Cont1[SQ004_SQ001]' => '',
            'Cont1[SQ004_SQ002]' => '',
            'Cont1[SQ004_SQ003]' => '',
            'Cont1[SQ004_SQ004]' => '',
            'hi1x' => '',
            'hi2x' => '',
            'hi3x' => '',
            'hri1x' => '',
            'hri2x' => '',
            'hri3x' => '',
            'hri4x' => '',
            'hri5x' => '',
            'hri6x' => '',
            'hri7ax' => '',
            'hri7bx' => NULL,
            'hri8ax' => '',
            'hri8bx' => NULL,
            'hri9x' => '',
            'ew1x' => '',
            'ew2x' => '',
            'ew3x' => '',
            'resmob2x' => NULL,
            'hr1x' => '',
            'WHOInt1HTML' => '',
            'WHOInt2HTML' => '',
            'CHROMID' => NULL,
        ), 338754));



        $result = $generator->render(
            $responses,
            null,
            \prime\models\ar\Project::find()->one(),
            new Signature("test@test.com", 29099, "Tester", new \DateTimeImmutable()),
            new UserData()
        );

        $html = $result->getStream()->getContents();
        // Check for empty rows.
        $this->assertEquals(0, preg_match('-\<div class="row">\s*\</div\>-', $html));

        // Check for empty body.
        $this->assertEquals(0, preg_match('-\</style\>\s*\</body\>-', $html), "Report body seems empty.");
    }
}