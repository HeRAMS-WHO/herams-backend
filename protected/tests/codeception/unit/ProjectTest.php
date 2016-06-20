<?php
namespace prime\tests\codeception\unit;

class ProjectTest extends \yii\codeception\DbTestCase
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testCreate()
    {
        // Find a tool.
        $tool = \prime\models\ar\Tool::findOne(6);
        $project = new \prime\models\ar\Project();
        $project->title = 'Test sharing';
        $project->country_iso_3 = 'NLD';
        $project->tool_id = $tool->id;
        $project->description = 'test';
        $project->owner_id = \prime\models\ar\User::find()->one()->id;
        $project->data_survey_eid = $tool->base_survey_eid;
        $this->assertTrue($project->validate(), "Validating project");
        $project->validate();
        $this->assertTrue($project->save(false), "Failed to create project.");
        $this->assertNotNull($project->getAttribute('token'), "Token created.");
    }
}