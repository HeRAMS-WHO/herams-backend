<?php
declare(strict_types=1);

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

    public function testRelations(): void
    {
        $this->testRelation('project', Project::class);
        $this->testRelation('parent', Page::class);
    }

    public function testImportExport(): void
    {
        $project = new Project();
        $project->base_survey_eid = 12345;
        $project->title = 'test';

        $project->save();
        $this->assertEmpty($project->errors, print_r($project->errors, true));

        $model = new Page();
        $model->project_id = $project->id;
        $model->title = 'test123';
        $data = $model->export();

        $imported = Page::import($project, $data);
        // ID is not imported so we set it manually so that we can test for equality
        $imported->id = $model->id;
        $this->assertSame($model->attributes, $imported->attributes);

        $exported = $imported->export();
        $this->assertSame($data, $exported);
    }

    public function testExportInvalid(): void
    {
        $page = new Page();
        $this->assertFalse($page->validate());
        $this->expectException(\LogicException::class);
        $page->export();
    }

    public function testTranslatedTitleOptions(): void
    {
        $page = new Page();
        $this->assertNotEmpty($page->titleOptions());
    }

    public function testImportInvalid()
    {
//        $this->tester
//            ->ski
    }
}
