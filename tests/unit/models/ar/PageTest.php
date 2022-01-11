<?php


namespace prime\tests\unit\models\ar;

use prime\models\ar\Page;
use prime\models\ar\Project;

/**
 * @covers \prime\models\ar\Page
 */
class PageTest extends ActiveRecordTest
{
    /**
     * @inheritDoc
     */
    public function validSamples(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function invalidSamples(): array
    {
        return [];
    }


    public function testImportExport()
    {
        $project = new Project();
        $project->base_survey_eid = 12345;
        $project->title = 'test';
        $project->save();
        $this->tester->assertEmpty($project->errors, print_r($project->errors, true));

        $model = new Page();
        $model->project_id = $project->id;
        $model->title = 'test123';
        $data = $model->export();

        $imported = Page::import($project, $data);
        // ID is not imported so we set it manually so that we can test for equality
        $imported->id = $model->id;
        $this->tester->assertSame($model->attributes, $imported->attributes);

        $exported = $imported->export();
        $this->tester->assertSame($data, $exported);
    }

    public function testExportInvalid()
    {
        $page = new Page();
        $this->tester->assertFalse($page->validate());
        $this->tester->expectThrowable(\LogicException::class, function () use ($page) {
            $page->export();
        });
    }

    public function testImportInvalid()
    {
//        $this->tester
//            ->ski
    }
}
